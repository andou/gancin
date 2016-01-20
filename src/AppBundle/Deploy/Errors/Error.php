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

namespace AppBundle\Deploy\Errors;

/**
 * Chown operation
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class Error {

  /**
   *
   * @var string
   */
  protected $code;

  /**
   *
   * @var string
   */
  protected $message;

  /**
   * Class constructor
   * 
   * @param type $code
   * @param type $message
   */
  function __construct($code, $message) {
    $this->code = $code;
    $this->message = $message;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////  FACTORY  /////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  const WRONG_PROJECT_NAME_CODE = 1;
  const WRONG_PROJECT_NAME_MESSAGE = 'Specified project name does not exists';

  public static function WRONG_PROJECT_NAME() {
    return new Error(self::WRONG_PROJECT_NAME_CODE, self::WRONG_PROJECT_NAME_MESSAGE);
  }

  const RSYNC_FILE_DOES_NOT_EXISTS_CODE = 1;
  const RSYNC_FILE_DOES_NOT_EXISTS_MESSAGE = 'Missing rsync file';

  public static function RSYNC_FILE_DOES_NOT_EXISTS() {
    return new Error(self::RSYNC_FILE_DOES_NOT_EXISTS_CODE, self::RSYNC_FILE_DOES_NOT_EXISTS_MESSAGE);
  }

  const APP_PATH_FOLDER_DOES_NOT_EXISTS_CODE = 1;
  const APP_PATH_FOLDER_DOES_NOT_EXISTS_MESSAGE = 'App path folder does not exists';

  public static function APP_PATH_FOLDER_DOES_NOT_EXISTS() {
    return new Error(self::APP_PATH_FOLDER_DOES_NOT_EXISTS_CODE, self::APP_PATH_FOLDER_DOES_NOT_EXISTS_MESSAGE);
  }

  const EXTRACT_FOLDER_DOES_NOT_EXISTS_CODE = 1;
  const EXTRACT_FOLDER_DOES_NOT_EXISTS_MESSAGE = 'Extract folder does not exists';

  public static function EXTRACT_FOLDER_DOES_NOT_EXISTS() {
    return new Error(self::EXTRACT_FOLDER_DOES_NOT_EXISTS_CODE, self::EXTRACT_FOLDER_DOES_NOT_EXISTS_MESSAGE);
  }

  const REPOSITORY_NOT_FOUND_CODE = 1;
  const REPOSITORY_NOT_FOUND_MESSAGE = 'Specified repository was not found';

  public static function REPOSITORY_NOT_FOUND() {
    return new Error(self::REPOSITORY_NOT_FOUND_CODE, self::REPOSITORY_NOT_FOUND_MESSAGE);
  }

  const RSYNC_OPERATION_ERROR_CODE = 1;

  public static function RSYNC_OPERATION_ERROR($message) {
    return new Error(self::RSYNC_OPERATION_ERROR_CODE, $message);
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////  GETTER AND SETTER  //////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   * @return string
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * 
   * @param string $code
   * @return \AppBundle\Deploy\Errors\Error
   */
  protected function setCode($code) {
    $this->code = $code;
    return $this;
  }

  /**
   * 
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * 
   * @param string $message
   * @return \AppBundle\Deploy\Errors\Error
   */
  protected function setMessage($message) {
    $this->message = $message;
    return $this;
  }

}
