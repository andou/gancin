<?php

namespace AppBundle\Configuration;

use \AppKernel;
use AppBundle\Models\Repository;
use AppBundle\Models\Project;
use AppBundle\Models\LocalData;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigurationManager
 *
 * @author andou
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

