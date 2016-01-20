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

use AppBundle\Deploy\Exceptions\RepositoryNotFoundException;

/**
 * Downloader operation
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class Downloader {

  /**
   *
   * @var string
   */
  protected static $user_agent = 'Php-Project-Deployer';

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
   * @var string 
   */
  protected $destination;

  /**
   *
   * @var string 
   */
  protected $name;

  /**
   *
   * @var string 
   */
  protected $url;

  /**
   *
   * @var boolean 
   */
  protected $progress = TRUE;

  /**
   *
   * @var string
   */
  protected $downloaded_file;

  /**
   * Downloads a project tarball
   * 
   * @return string
   */
  public function run() {
    $ch = curl_init($this->url);
    $fp = fopen($this->composeDestination(), "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if (!empty($this->user) && !empty($this->password)) {
      curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->password}");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
    if ($this->progress) {
      curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
    }
    curl_exec($ch);

    $info = curl_getinfo($ch);
    if (!isset($info['http_code']) || $info['http_code'] != 200) {
      throw new RepositoryNotFoundException();
    }

    curl_close($ch);
    fclose($fp);
    return $this->downloaded_file;
  }

  /**
   * Composes the destination file name
   * 
   * @return string
   */
  public function composeDestination() {
    $this->downloaded_file = sprintf("%s/%s-%s.tar.gz", $this->destination, $this->name, date("Ymd-His"));
    return $this->downloaded_file;
  }

  /**
   * Returns the destionation file name
   * 
   * @return string
   */
  public function getDownloadedFile() {
    return $this->downloaded_file;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////  GETTER AND SETTER  //////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

  public function getDestination() {
    return $this->destination;
  }

  public function setDestination($destination) {
    $this->destination = $destination;
    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function getProgress() {
    return $this->progress;
  }

  public function setProgress($progress) {
    $this->progress = $progress;
    return $this;
  }

}
