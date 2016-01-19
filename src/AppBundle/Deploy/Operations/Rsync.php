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

namespace AppBundle\Deploy\Operations;

/**
 * Rsync operation
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
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
