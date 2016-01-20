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

use AppBundle\Deploy\Errors\Error;
use AppBundle\Deploy\Exceptions\RsyncFileDoesNotExistsException;
use AppBundle\Deploy\Exceptions\AppPathFolderDoesNotExistsException;
use AppBundle\Deploy\Exceptions\ExtractFolderDoesNotExistsException;
use AppBundle\Deploy\Exceptions\RepositoryNotFoundException;

/**
 * Deploy Manager
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
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

  public function deploy($project_name, $branch, $silent_download = FALSE) {

    try {
      $project = $this->configuration_manager->getProject($project_name);
    } catch (RsyncFileDoesNotExistsException $e) {
      $this->addError(Error::RSYNC_FILE_DOES_NOT_EXISTS());
      return;
    } catch (ExtractFolderDoesNotExistsException $e) {
      $this->addError(Error::EXTRACT_FOLDER_DOES_NOT_EXISTS());
      return;
    } catch (AppPathFolderDoesNotExistsException $e) {
      $this->addError(Error::APP_PATH_FOLDER_DOES_NOT_EXISTS());
      return;
    }

    if ($project) {
      try {
        $this->deploy_task
                ->setProject($project)
                ->setLocaldata($project->getLocaldata())
                ->setSilentDownload($silent_download)
                ->run($branch);
      } catch (RepositoryNotFoundException $e) {
        $this->addError(Error::REPOSITORY_NOT_FOUND());
        return;
      }
    } else {
      $this->addError(Error::WRONG_PROJECT_NAME());
    }
  }

  public function listProjects() {
    return $this->configuration_manager->getAllProjects();
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////  ERROR HANDLING  ///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $errors = array();

  /**
   * 
   * @param AppBundle\Deploy\Errors\Error $error
   */
  protected function addError(Error $error) {
    $this->errors[] = $error;
  }

  /**
   * 
   * @return array
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * 
   * @return boolean
   */
  public function hasErrors() {
    return (boolean) count($this->errors);
  }

}

