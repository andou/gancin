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
   * @var \AppBundle\Deploy\DeployTask
   */
  protected $deploy_task;

  /**
   *
   * @var \AppBundle\Configuration\ConfigurationManager
   */
  protected $configuration_manager;

  function __construct(\AppBundle\Deploy\DeployTask $deploy_task, \AppBundle\Configuration\ConfigurationManager $configuration_manager) {
    $this->deploy_task = $deploy_task;
    $this->configuration_manager = $configuration_manager;
  }

  public function deploy($project_name, $branch) {
    $this->deploy_task
            ->setProject($this->configuration_manager->getProject($project_name))
            ->setLocaldata($this->configuration_manager->getLocalData($project_name))
            ->run($branch);
  }

}

