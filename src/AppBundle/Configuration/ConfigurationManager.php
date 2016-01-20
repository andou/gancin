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
   * 
   * @param string $project_name
   * @return \AppBundle\Models\Project
   */
  public function getProject($project_name) {
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
  public function getLocalData($project_name) {
    if (isset($this->conf['projects'][$project_name])) {
      $project_data = $this->conf['projects'][$project_name];
      $localdata = new LocalData();
      $localdata->setAppPath($project_data['local_data']['app_path']);
      $localdata->setExtractDir($project_data['local_data']['extract_dir']);
      $localdata->setUser($project_data['local_data']['user']);
      if (isset($project_data['local_data']['rsync_exclude'])) {
        $localdata->setRsyncexclude($project_data['local_data']['rsync_exclude']);
      }
      return $localdata;
    }
    return FALSE;
  }

  protected function parseConfFile() {
    $file_name = sprintf("%s/%s", $this->kernel->getRootDir(), $this->file);
    if (file_exists($file_name)) {
      $decode = json_decode(file_get_contents($file_name), TRUE);
      if ($decode) {
        $this->conf = $decode;
      }
    }
  }

}

