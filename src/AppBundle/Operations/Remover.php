<?php

namespace AppBundle\Operations;

/**
 * Description of Extractor
 *
 * @author andou
 */
class Remover {

  /**
   *
   * @var type 
   */
  protected $file;

  /**
   * 
   */
  public function run() {
    if (is_dir($this->file)) {
      return $this->rrmdir($this->file);
    } else {
      return unlink($this->file);
    }
  }

  protected function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir . "/" . $object) == "dir")
            $this->rrmdir($dir . "/" . $object);
          else
            unlink($dir . "/" . $object);
        }
      }
      reset($objects);
      return rmdir($dir);
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

}
