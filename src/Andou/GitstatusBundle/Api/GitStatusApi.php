<?php

/**
 * This file is part of Andou\GitstatusBundle
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

namespace Andou\GitstatusBundle\Api;

use Andou\GitstatusBundle\Model\Status;
use Andou\GitstatusBundle\Model\Message;

class GitStatusApi {

  const STATUS_GOOD = 'good';
  const STATUS_MINOR = 'minor';
  const STATUS_MAJOR = 'major';
  const JSON_STATUS = 'status';
  const JSON_BODY = 'body';
  const JSON_CREATED = 'created_on';
  const JSON_LASTUPDATE = 'last_updated';

  /**
   *
   * @var string
   */
  protected $baseurl;

  /**
   *
   * @var string 
   */
  protected $statusurl;

  /**
   *
   * @var string
   */
  protected $messagesurl;

  /**
   *
   * @var string
   */
  protected $lastmessageurl;

  /**
   * 
   * @param string $baseurl
   * @param string $statusurl
   * @param string $messagesurl
   * @param string $lastmessageurl
   */
  function __construct($baseurl, $statusurl, $messagesurl, $lastmessageurl) {
    $this->baseurl = $baseurl;
    $this->statusurl = $statusurl;
    $this->messagesurl = $messagesurl;
    $this->lastmessageurl = $lastmessageurl;
  }

  /**
   * 
   * @return \Andou\GitstatusBundle\Model\Status
   */
  public function getStatus() {
    $json = $this->call($this->composeUrl($this->statusurl));
    $res = $this->parse($json);
    return new Status($res[self::JSON_STATUS], $res[self::JSON_LASTUPDATE]);
  }

  /**
   * 
   * @return string
   */
  public function getMessages() {
    $return = array();
    $json = $this->call($this->composeUrl($this->messagesurl));
    $res = $this->parse($json);
    foreach ($res as $r) {
      $return[] = new Message($r[self::JSON_STATUS], $r[self::JSON_CREATED], $r[self::JSON_BODY]);
    }
    return $return;
  }

  /**
   * 
   * @return \Andou\GitstatusBundle\Model\Message
   */
  public function getMessage() {
    $json = $this->call($this->composeUrl($this->lastmessageurl));
    $res = $this->parse($json);
    return new Message($res[self::JSON_STATUS], $res[self::JSON_CREATED], $res[self::JSON_BODY]);
  }

  /**
   * 
   * @param type $url
   * @return type
   */
  protected function call($url) {
    return file_get_contents($url);
  }

  /**
   * 
   * @param type $json
   * @return type
   */
  protected function parse($json) {
    return json_decode($json, TRUE);
  }

  /**
   * 
   * @param type $url
   * @return type
   */
  protected function composeUrl($url) {
    return sprintf("%s/%s", $this->baseurl, $url);
  }

}
