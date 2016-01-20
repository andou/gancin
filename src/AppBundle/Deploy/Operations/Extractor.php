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

use AppBundle\Deploy\Exceptions\ExtractorOperationErrorException;

/**
 * Extractor operation
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class Extractor {

  /**
   *
   * @var string 
   */
  protected $file;

  /**
   *
   * @var string 
   */
  protected $destination;

  /**
   *
   * @var array
   */
  protected $exit_codes = array(
      "0" => "Success",
      "1" => "Some files differ",
      "2" => "Fatal error",
  );

  /**
   * Extract a tarball
   * 
   * @return boolean
   */
  public function run() {
    $command = sprintf('tar -zxvf "%s" --directory "%s"', $this->file, $this->destination);
    $return_val = null;
    $output = array();
    $out = exec($command, $output, $return_val);
    if ($return_val) {
      throw new ExtractorOperationErrorException($this->mapExitCode($return_val), $return_val);
    }
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

  protected function mapExitCode($code) {
    return $this->exit_codes[(string) $code];
  }

}
