<?php

namespace AppBundle\Operations;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rsync
 *
 * @author andou
 */
class Rsync {

  /**
   *
   * @var string
   */
  protected $exclude_from = NULL;

  /**
   *
   * @var string
   */
  protected $extract_dir;

  /**
   *
   * @var string 
   */
  protected $app_path;

  /**
   * Performs an rsync
   */
  public function run() {
    $command = "rsync";
    $options = "-a --progress --compress --delete";
    $exclude = "";
    if ($this->exclude_from) {
      $exclude = sprintf(" --exclude-from=%s", $this->exclude_from);
    }
    return exec(sprintf("%s %s%s %s %s", $command, $options, $exclude, $this->extract_dir, $this->app_path));
  }

  /**
   * 
   * @return type
   */
  public function getExtractDir() {
    return $this->extract_dir;
  }

  /**
   * 
   * @param type $extract_dir
   * @return \AppBundle\Operations\Rsync
   */
  public function setExtractDir($extract_dir) {
    $this->extract_dir = $extract_dir;
    return $this;
  }

  /**
   * 
   * @return type
   */
  public function getAppPath() {
    return $this->app_path;
  }

  /**
   * 
   * @param type $app_path
   * @return \AppBundle\Operations\Rsync
   */
  public function setAppPath($app_path) {
    $this->app_path = $app_path;
    return $this;
  }

  /**
   * 
   * @return string
   */
  public function getExcludeFrom() {
    return $this->exclude_from;
  }

  /**
   * 
   * @param string $exclude_from
   * @return \AppBundle\Operations\Rsync
   */
  public function setExcludeFrom($exclude_from) {
    $this->exclude_from = $exclude_from;
    return $this;
  }

  //rsync -a --progress --compress --delete --exclude-from=/root/scripts/orosaiwa/deploy/rsync_exclude.txt $DIREXTRACT/ $APPPATH/
}
