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
 * Repository
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
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

  public function __toString() {
    $using_password = isset($this->user) && isset($this->password) ? 'yes' : 'no';
    $res = sprintf("Repo: [%s] using password: [%s] ", $this->url, $using_password);
    if ($using_password == 'yes') {
      $res.=" {$this->user}:{$this->password}";
    }
    return $res;
  }

}

