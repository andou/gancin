<?php

namespace AppBundle\Deploy\Operations;

/**
 * Description of Extractor
 *
 * @author andou
 */
class Extractor {

  /**
   *
   * @var type 
   */
  protected $file;

  /**
   *
   * @var type 
   */
  protected $destination;

  /**
   * 
   */
  public function run() {
    $command = sprintf('tar -zxvf "%s" --directory "%s"', $this->file, $this->destination);
    $out = exec($command);
    $destination = pathinfo($out);
    if (isset($destination['dirname'])) {
      $dirname = $destination['dirname'];
      $directories = explode("/", $dirname);
      return sprintf("%s/%s", $this->destination, array_shift($directories));
    } else {
      return FALSE;
    }
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

  /**
   * 
   * @return type
   */
  public function getDestination() {
    return $this->destination;
  }

  /**
   * 
   * @param \AppBundle\Models\type $destination
   * @return \AppBundle\Models\Extractor
   */
  public function setDestination($destination) {
    $this->destination = $destination;
    return $this;
  }

}
