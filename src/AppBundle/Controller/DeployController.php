<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Deploy\Events\Github;

class DeployController extends Controller {

  /**
   *
   * @var \AppBundle\Controller\AppBundle\Deploy\DeployManager
   */
  protected $deploymanager = NULL;

  /**
   * @Route("/deploy-git", name="deploy-git")
   */
  public function indexAction(Request $request) {
    $this->initResponse();
    $deploymanager = $this->getDeployManager();
    $github_event = new Github($request);

//Checking formal request
    if ($request->getMethod() != 'POST') {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Not a POST request :(')
                      ->out(Response::HTTP_BAD_REQUEST);
    }
    if (!$github_event->isGithubEvent()) {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Not a Github Event :(')
                      ->out(Response::HTTP_BAD_REQUEST);
    }

    if (!$github_event->isGithubPush()) {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Not a Github push event :(')
                      ->out(Response::HTTP_BAD_REQUEST);
    }

//Retrieve information from request to match with local data
    $deploy_request = $this->extractInfoFromRequest($github_event);
    list($repository_name, $ref_type, $ref_name, $project) = array_values($deploy_request);
    $this->populateResponseData($deploy_request);

//Check request against local data
    if (!$project) {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Can\'t find a project related to: ' . $repository_name)
                      ->out(Response::HTTP_NOT_ACCEPTABLE);
    }

    if (!$deploymanager->remoteDeployAllowed($project, $ref_type, $ref_name)) {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Remote deploy not allowed :(')
                      ->out(Response::HTTP_NOT_ACCEPTABLE);
    }

    if ($deploymanager->hasRemoteSecret($project)) {
      if (!$github_event->validateGithubSignature($deploymanager->getRemoteSecret($project))) {
        return $this->responseSuccess(FALSE)
                        ->setErrorData('Mismatched signature :(')
                        ->out(Response::HTTP_FORBIDDEN);
      }
    }

    $deploymanager->deploy($project, $ref_name, $deploymanager->remoteDeployUseGrunt($project));
    if (!$deploymanager->hasErrors()) {
      return $this->responseSuccess(TRUE)
                      ->out(Response::HTTP_OK);
    } else {
      $this->responseSuccess(FALSE)
              ->setErrorData('Oops!! It seems we done something wrong. Please check errors :(');
      $errors = array();
      foreach ($deploymanager->getErrors() as $error) {
        $errors = $error->getMessage();
      }
      $this->setResponseData('errors', $errors);
      return $this->out(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return $this->out();
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////  SERVICES  ////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Returns a deploy manager
   * 
   * @return \AppBundle\Controller\AppBundle\Deploy\DeployManager
   */
  protected function getDeployManager() {
    if (!isset($this->deploymanager)) {
      $this->deploymanager = $this->get('app.deploy.manager');
    }
    return $this->deploymanager;
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////  REQUEST MANAGEMENT  ///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /**
   * 
   * @param type $github_event
   * @return type
   */
  protected function extractInfoFromRequest($github_event) {
    $res = array();
    $res['project_name'] = $repo_name = $github_event->getRepositoryName();
    $res['repository_name'] = $github_event->getRefType();
    $res['ref_type'] = $github_event->getRefName();
    $res['ref_name'] = $this->getDeployManager()->getProjectFromRepo($repo_name);
    return $res;
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////  RESPONSE MANAGEMENT  ///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $response_data = array();

  /**
   *
   * @var Symfony\Component\HttpFoundation\Response
   */
  protected $response;

  /**
   * Initizialize the reponse of this controller
   */
  protected function initResponse() {
    $this->response = new Response();
    $this->response->headers->set('Content-Type', 'application/json');
    $this->response_data = array();
  }

  /**
   * Sets the success for this response
   * 
   * @param string $success
   * @return \AppBundle\Controller\DefaultController
   */
  protected function responseSuccess($success) {
    $this->response_data['success'] = $success;
    return $this;
  }

  /**
   * Populate the error data for the response
   * 
   * @param string $value
   * @return \AppBundle\Controller\DefaultController
   */
  protected function setErrorData($value) {
    return $this->setResponseData('error', $value);
  }

  /**
   * 
   * @param array $data
   * @return \AppBundle\Controller\DefaultController
   */
  protected function populateResponseData($data) {
    foreach ($data as $name => $value) {
      $this->setResponseData($name, $value);
    }
    return $this;
  }

  /**
   * Populates a segment of the JSON Response
   * 
   * @param string $segment
   * @param string $value
   * @return \AppBundle\Controller\DefaultController
   */
  protected function setResponseData($segment, $value) {
    $this->response_data[$segment] = $value;
    return $this;
  }

  /**
   * Returns a response
   * 
   * @param int $status_code
   * @return Symfony\Component\HttpFoundation\Response
   */
  protected function out($status_code) {
    $this->response->setStatusCode($status_code);
    $this->response->setContent(json_encode($this->response_data));
    return $this->response;
  }

}

