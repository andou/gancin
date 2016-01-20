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

use AppBundle\Deploy\Exceptions\RsyncOperationErrorException;

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
   *
   * @var array
   */
  protected $exit_codes = array(
      "0" => "Success",
      "1" => "Syntax or usage error",
      "2" => "Protocol incompatibility",
      "3" => "Errors selecting input/output files, dirs",
      "4" => "Requested  action not supported: an attempt was made to manipulate 64-bit files on a platform that cannot support them; or an option was specified that is supported by the client and not by the server.",
      "5" => "Error starting client-server protocol",
      "6" => "Daemon unable to append to log-file",
      "10" => "Error in socket I/O",
      "11" => "Error in file I/O",
      "12" => "Error in rsync protocol data stream",
      "13" => "Errors with program diagnostics",
      "14" => "Error in IPC code",
      "20" => "Received SIGUSR1 or SIGINT",
      "21" => "Some error returned by waitpid()",
      "22" => "Error allocating core memory buffers",
      "23" => "Partial transfer due to error",
      "24" => "Partial transfer due to vanished source files",
      "25" => "The --max-delete limit stopped deletions",
      "30" => "Timeout in data send/receive",
      "35" => "Timeout waiting for daemon connection",
  );

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
    $return_val = null;
    $output = array();
    exec(sprintf("%s %s%s %s %s > /dev/null 2>&1", $command, $options, $exclude, $this->extract_dir, $this->app_path), $output, $return_val);
    if ($return_val) {
      throw new RsyncOperationErrorException($this->mapExitCode($return_val), $return_val);
    }
    return $output;
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

  protected function mapExitCode($code) {
    return $this->exit_codes[(string) $code];
  }

  //rsync -a --progress --compress --delete --exclude-from=/root/scripts/orosaiwa/deploy/rsync_exclude.txt $DIREXTRACT/ $APPPATH/
}
