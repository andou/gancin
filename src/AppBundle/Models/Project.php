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

namespace AppBundle\Models;

use AppBundle\Models\Repository;

/**
 * Project
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
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
  protected $default_branch = NULL;

  /**
   *
   * @var \AppBundle\Models\Repository
   */
  protected $repository;

  /**
   *
   * @var \AppBundle\Models\LocalData
   */
  protected $localdata;

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

  /**
   * 
   * @return \AppBundle\Models\LocalData
   */
  public function getLocaldata() {
    return $this->localdata;
  }

  /**
   * 
   * @param \AppBundle\Models\LocalData $localdata
   * @return \AppBundle\Models\Project
   */
  public function setLocaldata(\AppBundle\Models\LocalData $localdata) {
    $this->localdata = $localdata;
    return $this;
  }

  public function __toString() {
    return sprintf("Project: [%s] default branch: [%s]", $this->name, isset($this->default_branch) ? $this->default_branch : "no");
  }

}

