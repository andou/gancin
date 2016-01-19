<?php

namespace AppBundle\Deploy\Operations;

/**
 * Description of Extractor
 *
 * @author andou
 */
class Chown {

  /**
   *
   * @var type 
   */
  protected $file;

  /**
   *
   * @var type 
   */
  protected $user;

  /**
   * 
   */
  public function run() {
    $command = sprintf('chown -R  %s %s', $this->user, $this->file);
    return exec($command);
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////  GETTER AND SETTER  //////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /**
   * 
   * @return type
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * 
   * @param \AppBundle\Models\type $file
   * @return \AppBundle\Models\Extractor
   */
  public function setFile($file) {
    $this->file = $file;
    return $this;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser($user) {
    $this->user = $user;
    return $this;
  }

}
