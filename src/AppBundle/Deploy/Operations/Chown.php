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

use AppBundle\Deploy\Exceptions\ChownOperationErrorException;

/**
 * Chown operation
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class Chown {

  /**
   * The file on which to perform the chown
   *
   * @var string 
   */
  protected $file;

  /**
   * User for which to grant the chown
   *
   * @var string 
   */
  protected $user;

  /**
   * Performs a chown operation
   * 
   * @return string
   */
  public function run() {
    $command = sprintf('chown -R  %s %s', $this->user, $this->file);
    $return_val = null;
    $output = array();
    exec($command, $output, $return_val);
    if ($return_val) {
      throw new ChownOperationErrorException();
    }
    return $output;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////  GETTER AND SETTER  //////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /**
   * 
   * @return string
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * 
   * @param string $file
   * @return \AppBundle\Models\Extractor
   */
  public function setFile($file) {
    $this->file = $file;
    return $this;
  }

  /**
   * 
   * @return string
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * 
   * @param string $user
   * @return \AppBundle\Deploy\Operations\Chown
   */
  public function setUser($user) {
    $this->user = $user;
    return $this;
  }

}
