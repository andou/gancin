<?php

namespace AppBundle\Models;

/**
 * Description of Repository
 *
 * @author andou
 */
class Repository {

  /**
   *
   * @var string
   */
  protected $url;

  /**
   *
   * @var string
   */
  protected $user = NULL;

  /**
   *
   * @var string
   */
  protected $password = NULL;

  /**
   * 
   * @return type
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * 
   * @param type $url
   * @return \AppBundle\Models\Repository
   */
  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser($user) {
    $this->user = $user;
    return $this;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }

}

