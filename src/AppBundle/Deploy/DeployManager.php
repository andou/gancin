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
class DeployManager {

  /**
   *
   * @var \AppBundle\Operations\Downloader
   */
  protected $downloader;

  /**
   *
   * @var \AppBundle\Operations\Extractor
   */
  protected $extractor;

  /**
   *
   * @var \AppBundle\Operations\Rsync
   */
  protected $rsync;

  /**
   *
   * @var \AppBundle\Operations\Chown
   */
  protected $chown;

  /**
   *
   * @var \AppBundle\Operations\Remover
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

  function __construct(\AppBundle\Operations\Downloader $downloader, \AppBundle\Operations\Extractor $extractor, \AppBundle\Operations\Rsync $rsync, \AppBundle\Operations\Chown $chown, \AppBundle\Operations\Remover $remover) {
    $this->downloader = $downloader;
    $this->extractor = $extractor;
    $this->rsync = $rsync;
    $this->chown = $chown;
    $this->remover = $remover;
  }

  public function deploy() {
    $this->configureDownloader();
    $tarball = $this->download();
    $extract_folder = $this->extract($tarball);
    $this->syncFolders($extract_folder);
    $this->setPermission();
    $this->clean($extract_folder);
  }

  protected function download() {
    return $this->downloader->run();
  }

  protected function configureDownloader() {
//    $this->downloader
//            ->setUser("gitmama")
//            ->setPassword("gitmama12");

    $this->downloader
            ->setName("wed")
            ->setDestination("/tmp")
            ->setUrl("https://api.github.com/repos/andou/wed/tarball/master");
  }

  protected function extract($tarball) {
    return $this->extractor->setFile($tarball)->setDestination('/tmp')->run();
  }

  protected function syncFolders($source_folder) {
    return $this->rsync->setExtractDir($source_folder . '/')->setAppPath('/tmp/deploy/')->run();
  }

  protected function setPermission() {
    $this->chown->setFile('/tmp/deploy/')->setUser('www-data:www-data')->run();
  }

  protected function clean($extract_folder) {
    return $this->remover->setFile($extract_folder)->run();
  }

}

