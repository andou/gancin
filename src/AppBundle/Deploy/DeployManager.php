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
use AppBundle\Deploy\Exceptions\RsyncOperationErrorException;
use AppBundle\Deploy\Exceptions\ExtractorOperationErrorException;
use AppBundle\Deploy\Exceptions\ChownOperationErrorException;
use Monolog\Logger;

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

  /**
   *
   * @var Monolog\Logger 
   */
  protected $logger;

  function __construct(\AppBundle\Deploy\DeployTask $deploy_task, \AppBundle\Configuration\ConfigurationManager $configuration_manager, Logger $logger) {
    $this->deploy_task = $deploy_task;
    $this->configuration_manager = $configuration_manager;
    $this->logger = $logger;
  }

  public function deploy($project_name, $branch, $usegrunt = FALSE, $silent_download = FALSE) {
    $this->log("Deploying |$project_name|");
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
                ->setUsegrunt($usegrunt)
                ->run($branch);
      } catch (RepositoryNotFoundException $e) {
        $this->addError(Error::REPOSITORY_NOT_FOUND());
        return;
      } catch (RsyncOperationErrorException $e) {
        $this->addError(Error::RSYNC_OPERATION_ERROR($e->getMessage()));
        return;
      } catch (ExtractorOperationErrorException $e) {
        $this->addError(Error::EXTRACTOR_OPERATION_ERROR($e->getMessage()));
        return;
      } catch (ChownOperationErrorException $e) {
        $this->addError(Error::CHOWN_OPERATION_ERROR());
        return;
      }
    } else {
      $this->addError(Error::WRONG_PROJECT_NAME());
    }
    $this->log("Done Without Errors");
  }

  public function listProjects() {
    $this->log("Listing projects");
    return $this->configuration_manager->getAllProjects();
  }

  protected function log($message, $error = FALSE) {
    if ($error) {
      $this->logger->error($message);
    } else {
      $this->logger->info($message);
    }
  }

  /**
   * 
   * @param string $repo
   * @return string
   */
  public function getProjectFromRepo($repo) {
    return $this->configuration_manager->getLocalNameFromRemoteName($repo);
  }

  /**
   * 
   * @param string $project
   * @param string $type
   * @param string $branch
   * @return boolean
   */
  public function remoteDeployAllowed($project, $type, $branch) {
    $local_project = $this->configuration_manager->getProject($project);
    if ($local_project->getLocaldata()->getRemote()) {
      $events = $local_project->getLocaldata()->getRemoteEvents();
      switch ($type) {
        case Events\Github::GITHUB_REF_TAG_TYPE:
          return in_array($type, $events);
          break;
        case Events\Github::GITHUB_REF_BRANCH_TYPE:
        default :
          $allowed_branches = $local_project->getLocaldata()->getRemoteBranches();
          return in_array($type, $events) && in_array($branch, $allowed_branches);
          break;
      }
    }
    return FALSE;
  }

  /**
   * 
   * @param string $project
   * @return boolean
   */
  public function remoteDeployUseGrunt($project) {
    return $this->configuration_manager->getProject($project)->getLocaldata()->getRemoteGrunt();
  }

  /**
   * 
   * @param string $project
   * @return string
   */
  public function getRemoteSecret($project) {
    return $this->configuration_manager->getProject($project)->getLocaldata()->getRemoteSecret();
  }

  public function hasRemoteSecret($project) {
    return $this->configuration_manager->getProject($project)->getLocaldata()->hasSecret();
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
    $this->log($error->getMessage(), TRUE);
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

