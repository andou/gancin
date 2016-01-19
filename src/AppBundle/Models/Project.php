<?php

namespace AppBundle\Models;

use AppBundle\Models\Repository;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Project
 *
 * @author andou
 */
class Project {

  /**
   *
   * @var string
   */
  protected $name;

  /**
   *
   * @var string 
   */
  protected $default_branch;

  /**
   *
   * @var \AppBundle\Models\Repository
   */
  protected $repository;

  /**
   * 
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * 
   * @param type $name
   * @return \AppBundle\Models\Project
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getDefaultBranch() {
    return $this->default_branch;
  }

  public function setDefaultBranch($default_branch) {
    $this->default_branch = $default_branch;
    return $this;
  }

  /**
   * 
   * @return type
   */
  public function getRepository() {
    return $this->repository;
  }

  /**
   * 
   * @param \AppBundle\Models\Repository $repository
   * @return \AppBundle\Models\Project
   */
  public function setRepository(\AppBundle\Models\Repository $repository) {
    $this->repository = $repository;
    return $this;
  }

}

