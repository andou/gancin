<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Deploy\Events\Github;

class DefaultController extends Controller {

  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request) {
    // replace this example code with whatever you need
    return $this->render('default/index.html.twig', [
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
    ]);
  }

  /**
   * @Route("/deploy-git", name="deploy-git")
   */
  public function deployAction(Request $request) {
    $response = new Response();
    $res = array("success" => FALSE);
    $deploymanager = $this->get('app.deploy.manager');


    if ($request->getMethod() == 'POST') {
      $github_event = new Github($request);
      if ($github_event->isGithubEvent()) {
        if ($github_event->isGithubPush()) {
          $res['repository_name'] = $github_event->getRepositoryName();
          $res['ref_type'] = $github_event->getRefType();
          $res['ref_name'] = $github_event->getRefName();
          $project = $deploymanager->getProjectFromRepo($res['repository_name']);
          if ($deploymanager->remoteDeployAllowed($project, $github_event->getRefType(), $res['ref_name'])) {
            $deploymanager->deploy($project, $res['ref_name'], $deploymanager->remoteDeployUseGrunt($project));
            if (!$deploymanager->hasErrors()) {
              $response->setStatusCode(Response::HTTP_OK);
              $res['success'] = TRUE;
            } else {
              $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
              $res['errors'] = array();
              foreach ($deploymanager->getErrors() as $error)
                $res['errors'][] = $error->getMessage();
            }
          } else {
            $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            $res['error'] = 'Remote deploy not allowed :(';
          }
        } else {
          $response->setStatusCode(Response::HTTP_BAD_REQUEST);
          $res['error'] = 'Not a Github push event :(';
        }
      } else {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $res['error'] = 'Not a Github Event :(';
      }
    } else {
      $response->setStatusCode(Response::HTTP_BAD_REQUEST);
      $res['error'] = 'Not a POST request :(';
    }
    $response->headers->set('Content-Type', 'application/json');
    $response->setContent(json_encode($res));
    return $response;
  }

}
