<?php

namespace AppBundle\Deploy;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeployManager
 *
 * @author andou
 */
class DeployTask {

  protected static $default_branch = 'master';

  /**
   *
   * @var \AppBundle\Deploy\Operations\Downloader
   */
  protected $downloader;

  /**
   *
   * @var \AppBundle\Deploy\Operations\Extractor
   */
  protected $extractor;

  /**
   *
   * @var \AppBundle\Deploy\Operations\Rsync
   */
  protected $rsync;

  /**
   *
   * @var \AppBundle\Deploy\Operations\Chown
   */
  protected $chown;

  /**
   *
   * @var \AppBundle\Deploy\Operations\Remover
   */
  protected $remover;

  /**
   *
   * @var \AppBundle\Models\Project
   */
  protected $project;

  /**
   *
   * @var \AppBundle\Models\LocalData
   */
  protected $localdata;

  function __construct(\AppBundle\Deploy\Operations\Downloader $downloader, \AppBundle\Deploy\Operations\Extractor $extractor, \AppBundle\Deploy\Operations\Rsync $rsync, \AppBundle\Deploy\Operations\Chown $chown, \AppBundle\Deploy\Operations\Remover $remover) {
    $this->downloader = $downloader;
    $this->extractor = $extractor;
    $this->rsync = $rsync;
    $this->chown = $chown;
    $this->remover = $remover;
  }

  public function run($branch = NULL) {
    $this->configureDownloader($branch);
    $tarball = $this->download();
    $extract_folder = $this->extract($tarball);
    $this->syncFolders($extract_folder);
    $this->setPermission();
    $this->clean($extract_folder);
  }

  protected function download() {
    return $this->downloader->run();
  }

  protected function configureDownloader($branch = NULL) {
    if (empty($branch)) {
      $branch = $this->project->getDefaultBranch();
    }
    if (empty($branch)) {
      $branch = self::$default_branch;
    }

    $repo_user = $this->project->getRepository()->getUser();
    $repo_password = $this->project->getRepository()->getPassword();

    if (!empty($repo_user) && !empty($repo_password)) {
      $this->downloader
              ->setUser($repo_user)
              ->setPassword($repo_password);
    }
    $this->downloader
            ->setName($this->project->getName())
            ->setDestination($this->localdata->getExtractDir())
            ->setUrl($this->project->getRepository()->getUrl() . "/$branch");
  }

  protected function extract($tarball) {
    return $this->extractor->setFile($tarball)->setDestination($this->localdata->getExtractDir())->run();
  }

  protected function syncFolders($source_folder) {
    return $this->rsync->setExtractDir($source_folder . '/')->setAppPath($this->localdata->getAppPath())->run();
  }

  protected function setPermission() {
    $this->chown->setFile($this->localdata->getAppPath())->setUser($this->localdata->getUser())->run();
  }

  protected function clean($extract_folder) {
    return $this->remover->setFile($extract_folder)->run();
  }

  public function getProject() {
    return $this->project;
  }

  public function setProject(\AppBundle\Models\Project $project) {
    $this->project = $project;
    return $this;
  }

  public function getLocaldata() {
    return $this->localdata;
  }

  public function setLocaldata(\AppBundle\Models\LocalData $localdata) {
    $this->localdata = $localdata;
    return $this;
  }

}

