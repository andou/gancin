<?php

/**
 * This file is part of php-project-deployer (https://github.com/andou/php-project-deployer)
 * 
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Antonio Pastorino <antonio.pastorino@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * 
 * @author Antonio Pastorino <antonio.pastorino@gmail.com>
 * @copyright MIT License (http://opensource.org/licenses/MIT)
 */

namespace AppBundle\Deploy;

/**
 * Deploy Task
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
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

  /**
   * Class constructor
   * 
   * @param \AppBundle\Deploy\Operations\Downloader $downloader
   * @param \AppBundle\Deploy\Operations\Extractor $extractor
   * @param \AppBundle\Deploy\Operations\Rsync $rsync
   * @param \AppBundle\Deploy\Operations\Chown $chown
   * @param \AppBundle\Deploy\Operations\Remover $remover
   */
  function __construct(\AppBundle\Deploy\Operations\Downloader $downloader, \AppBundle\Deploy\Operations\Extractor $extractor, \AppBundle\Deploy\Operations\Rsync $rsync, \AppBundle\Deploy\Operations\Chown $chown, \AppBundle\Deploy\Operations\Remover $remover) {
    $this->downloader = $downloader;
    $this->extractor = $extractor;
    $this->rsync = $rsync;
    $this->chown = $chown;
    $this->remover = $remover;
  }

  /**
   * Performs a deploy task
   * 
   * @param string $branch
   */
  public function run($branch = NULL) {
    $this->configureDownloader($branch);
    $tarball = $this->download();
    $extract_folder = $this->extract($tarball);
    $this->syncFolders($extract_folder);
    $this->setPermission();
    $this->clean($extract_folder);
    $this->clean($tarball);
  }

  /**
   * Configure the downloader in order to retrieve the correct tarball
   * 
   * @param string $branch
   */
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

  /**
   * Downloads the tarball
   * 
   * @return string
   */
  protected function download() {
    return $this->downloader->run();
  }

  /**
   * Extract a tarball
   * 
   * @param string $tarball
   * @return string
   */
  protected function extract($tarball) {
    return $this->extractor->setFile($tarball)->setDestination($this->localdata->getExtractDir())->run();
  }

  /**
   * Syncs folders
   * 
   * @param type $source_folder
   * @return type
   */
  protected function syncFolders($source_folder) {
    if ($this->localdata->getRsyncexclude()) {
      $this->rsync->setExcludeFrom($this->localdata->getRsyncexclude());
    }
    return $this->rsync->setExtractDir($source_folder . '/')->setAppPath($this->localdata->getAppPath())->run();
  }

  /**
   * Sets permissions
   */
  protected function setPermission() {
    $this->chown->setFile($this->localdata->getAppPath())->setUser($this->localdata->getUser())->run();
  }

  /**
   * Deletes files and/or directories
   * 
   * @param type $extract_folder
   * @return type
   */
  protected function clean($extract_folder) {
    return $this->remover->setFile($extract_folder)->run();
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////  GETTER AND SETTER  //////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   * @return type
   */
  public function getProject() {
    return $this->project;
  }

  /**
   * 
   * @param \AppBundle\Models\Project $project
   * @return \AppBundle\Deploy\DeployTask
   */
  public function setProject(\AppBundle\Models\Project $project) {
    $this->project = $project;
    return $this;
  }

  /**
   * 
   * @return type
   */
  public function getLocaldata() {
    return $this->localdata;
  }

  /**
   * 
   * @param \AppBundle\Models\LocalData $localdata
   * @return \AppBundle\Deploy\DeployTask
   */
  public function setLocaldata(\AppBundle\Models\LocalData $localdata) {
    $this->localdata = $localdata;
    return $this;
  }

}

