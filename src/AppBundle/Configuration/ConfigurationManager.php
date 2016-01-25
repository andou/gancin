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

namespace AppBundle\Configuration;

use \AppKernel;
use AppBundle\Models\Repository;
use AppBundle\Models\Project;
use AppBundle\Models\LocalData;
use AppBundle\Deploy\Exceptions\RsyncFileDoesNotExistsException;
use AppBundle\Deploy\Exceptions\AppPathFolderDoesNotExistsException;
use AppBundle\Deploy\Exceptions\ExtractFolderDoesNotExistsException;

/**
 * App Configuration Manager
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class ConfigurationManager {

  /**
   *
   * @var string
   */
  protected $file;

  /**
   *
   * @var array
   */
  protected $conf;

  /**
   * The kernel
   *
   * @var \AppKernel 
   */
  protected $kernel;

  /**
   *
   * @var array
   */
  protected $local_datas = NULL;

  /**
   *
   * @var array
   */
  protected $local_projects = NULL;

  function __construct(AppKernel $kernel, $file) {
    $this->file = $file;
    $this->kernel = $kernel;
    $this->parseConfFile();
  }

  /**
   * 
   * @return array
   */
  public function getAllProjects() {
    $res = array();
    foreach ($this->conf['projects'] as $project_name => $proj) {
      $res[] = $this->getProject($project_name);
    }
    return $res;
  }

  /**
   * Returns a local project name from a remote repo name
   * 
   * @param string $remote_name
   * @return string|boolean
   */
  public function getLocalNameFromRemoteName($remote_name) {
    foreach ($this->getAllProjects() as $project) {
      $_rn = $project->getLocaldata()->getRemoteName();
      if ($_rn == $remote_name) {
        return $project->getName();
      }
    }
    return FALSE;
  }

  /**
   * Caching wrapper for getting projects
   * 
   * @param string $project_name
   * @return \AppBundle\Models\Project
   */
  public function getProject($project_name) {
    if (!isset($this->local_projects[$project_name])) {
      $this->local_projects[$project_name] = $this->_getProject($project_name);
    }
    return $this->local_projects[$project_name];
  }

  /**
   * Caching wrapper for getting local datas
   * 
   * @param string $project_name
   * @return \AppBundle\Models\LocalData
   */
  public function getLocalData($project_name) {
    if (!isset($this->local_datas [$project_name])) {
      $this->local_datas [$project_name] = $this->_getLocalData($project_name);
    }
    return $this->local_datas [$project_name];
  }

  /**
   * 
   * @param string $project_name
   * @return \AppBundle\Models\Project
   */
  protected function _getProject($project_name) {
    if (isset($this->conf['projects'][$project_name])) {
      $project_data = $this->conf['projects'][$project_name];

      $repo = new Repository();
      $repo->setUrl($project_data['repo']['url']);
      if (isset($project_data['repo']['user']) && isset($project_data['repo']['password'])) {
        $repo->setUser($project_data['repo']['user']);
        $repo->setPassword($project_data['repo']['password']);
      }
      $project = new Project();
      $project->setName($project_name);
      if (isset($project_data['project']['default_branch'])) {
        $project->setDefaultBranch($project_data['project']['default_branch']);
      }
      $project->setRepository($repo);
      $project->setLocaldata($this->getLocalData($project_name));
      return $project;
    }
    return FALSE;
  }

  /**
   * 
   * @param string $project_name
   * @return \AppBundle\Models\LocalData
   */
  protected function _getLocalData($project_name) {
    if (isset($this->conf['projects'][$project_name])) {
      $project_data = $this->conf['projects'][$project_name];
      $this->checkFolders($project_data);
      //
      $localdata = new LocalData();
      $localdata->setAppPath($project_data['local_data']['app_path']);
      $localdata->setExtractDir($project_data['local_data']['extract_dir']);
      $localdata->setUser($project_data['local_data']['user']);
      if (!empty($project_data['local_data']['rsync_exclude'])) {
        $rsync_file = $this->getFilePath($project_data['local_data']['rsync_exclude']);
        if (!file_exists($rsync_file)) {
          throw new RsyncFileDoesNotExistsException;
        }
        $localdata->setRsyncexclude($rsync_file);
      }

      //REMOTE CONFIGURATIONS
      if (!empty($project_data['remote_synch'])) {
        if (!empty($project_data['remote_synch']["enabled"])) {
          $localdata->setRemote($project_data['remote_synch']["enabled"]);
          if (!empty($project_data['remote_synch']["name"])) {
            $localdata->setRemoteName($project_data['remote_synch']["name"]);
          }
          if (!empty($project_data['remote_synch']["events"])) {
            $localdata->setRemoteEvents($project_data['remote_synch']["events"]);
          }
          if (!empty($project_data['remote_synch']["branches"])) {
            $localdata->setRemoteBranches($project_data['remote_synch']["branches"]);
          }
          if (!empty($project_data['remote_synch']["grunt"])) {
            $localdata->setRemoteGrunt($project_data['remote_synch']["grunt"]);
          }
        }
      }

      return $localdata;
    }
    return FALSE;
  }

  public function checkFolders($project_data) {
    if (!file_exists($project_data['local_data']['app_path'])) {
      throw new AppPathFolderDoesNotExistsException;
    }
    if (!file_exists($project_data['local_data']['extract_dir'])) {
      throw new ExtractFolderDoesNotExistsException;
    }
  }

  /**
   * 
   */
  protected function parseConfFile() {
    $this->getFilePath($this->file);
    $file_name = sprintf("%s/%s", $this->kernel->getRootDir(), $this->file);
    if (file_exists($file_name)) {
      $decode = json_decode(file_get_contents($file_name), TRUE);
      if ($decode) {
        $this->conf = $decode;
      }
    }
  }

  /**
   * 
   * @param type $filename
   * @return type
   */
  protected function getFilePath($filename) {
    return sprintf("%s/%s", $this->kernel->getRootDir(), $filename);
  }

}

