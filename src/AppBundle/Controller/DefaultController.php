<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Deploy\Events\Github;

class DefaultController extends Controller {

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
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request) {
    // replace this example code with whatever you need
    return $this->render('default/index.html.twig', [
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
    ]);
  }

  protected function initResponse() {
    $this->response = new Response();
    $this->response->headers->set('Content-Type', 'application/json');
    $this->response_data = array();
  }

  protected function responseSuccess($success) {
    $this->response_data['success'] = $success;
    return $this;
  }

  protected function setErrorData($value) {
    return $this->setResponseData('error', $value);
  }

  protected function setResponseData($segment, $value) {
    $this->response_data[$segment] = $value;
    return $this;
  }

  protected function out($status_code) {
    $this->response->setStatusCode($status_code);
    $this->response->setContent(json_encode($this->response_data));
    return $this->response;
  }

  /**
   * @Route("/deploy-git", name="deploy-git")
   */
  public function deployAction(Request $request) {
    $this->initResponse();
    $deploymanager = $this->get('app.deploy.manager');
    $github_event = new Github($request);

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

    $res = array();
    $res['repository_name'] = $github_event->getRepositoryName();
    $res['ref_type'] = $github_event->getRefType();
    $res['ref_name'] = $github_event->getRefName();
    $project = $deploymanager->getProjectFromRepo($res['repository_name']);

    $this->setResponseData('repository_name', $res['repository_name'])
            ->setResponseData('ref_type', $res['ref_type'])
            ->setResponseData('ref_name', $github_event->getRefName());

    if (!$project) {
      return $this->responseSuccess(FALSE)
                      ->setErrorData('Can\'t find the project: ' . $res['repository_name'])
                      ->out(Response::HTTP_NOT_ACCEPTABLE);
    }
    $this->setResponseData('project_name', $project);

    if (!$deploymanager->remoteDeployAllowed($project, $github_event->getRefType(), $res['ref_name'])) {
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


    $deploymanager->deploy($project, $res['ref_name'], $deploymanager->remoteDeployUseGrunt($project));
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

}

