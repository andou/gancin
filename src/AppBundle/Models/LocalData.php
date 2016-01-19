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

namespace AppBundle\Models;

/**
 * Local Data
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class LocalData {

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
   *
   * @var string
   */
  protected $user;

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

  public function getUser() {
    return $this->user;
  }

  public function setUser($user) {
    $this->user = $user;
    return $this;
  }

}

