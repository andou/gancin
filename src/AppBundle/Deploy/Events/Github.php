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

namespace AppBundle\Deploy\Events;

use Symfony\Component\HttpFoundation\Request;

/**
 * Github event
 * 
 * @todo Contollare il Github secret
 * @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class Github {

  const GITHUB_EVENT_HEADER = "X-Github-Event";
  const GITHUB_EVENT_PUSH = "push";
  const GITHUB_REF_BRANCH_TYPE = "heads";
  const GITHUB_REF_TAG_TYPE = "tags";

  /**
   *
   * @var Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   *
   * @var array
   */
  protected $request_content;

  /**
   *
   * @var array
   */
  protected $headers;

  public function __construct(Request $request) {
    $this->request = $request;
    $this->parseRequest();
  }

  /**
   * Parses a request
   * 
   * @return \AppBundle\Events\Github
   */
  protected function parseRequest() {
    $content = $this->request->getContent();
    $this->request_content = json_decode($content, TRUE);
    if (!$this->request_content) {
      //@todo -> throw exception
    }
    $this->headers = apache_request_headers();
    return $this;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////    GITHUB REFS    ///////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Returns the tag name for this event
   * 
   * @return string|null
   */
  public function getTagName() {
    if ($this->isTag()) {
      $this->getRefName();
    } else {
      return NULL;
    }
  }

  /**
   * Returns the branch name for this event
   * 
   * @return string|null
   */
  public function getBranchName() {
    if ($this->isBranch()) {
      $this->getRefName();
    } else {
      return NULL;
    }
  }

  /**
   * Returns the ref name
   * 
   * @return string
   */
  public function getRefName() {
    $ref_parts = $this->explodeRef();
    return $ref_parts[2];
  }

  /**
   * Checks if this is a tag
   * 
   * @return boolean
   */
  public function isTag() {
    return $this->getRefType() == self::GITHUB_REF_TAG_TYPE;
  }

  /**
   * Checks if this is a branch
   * 
   * @return boolean
   */
  public function isBranch() {
    return $this->getRefType() == self::GITHUB_REF_BRANCH_TYPE;
  }

  /**
   * Returns the ref type for this event
   * 
   * @return string
   */
  public function getRefType() {
    $ref_parts = $this->explodeRef();
    return $ref_parts[1];
  }

  /**
   * Explodes the ref in parts
   * 
   * @return array
   */
  protected function explodeRef() {
    return explode("/", $this->request_content['ref']);
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////    EVENTS    /////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Returns the repository name
   * 
   * @return string
   */
  public function getRepositoryName() {
    return $this->request_content['repository']['name'];
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////    EVENTS    /////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /**
   * Checks if this is a github event
   * 
   * @return boolean
   */
  public function isGithubEvent() {
    return $this->hasHeader(self::GITHUB_EVENT_HEADER);
  }

  /**
   * Checks if this is a github push
   * 
   * @return boolean
   */
  public function isGithubPush() {
    return $this->isGithubEvent() && $this->getHeader(self::GITHUB_EVENT_HEADER) == self::GITHUB_EVENT_PUSH;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////    HEADERS    ////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Checks if a specific header is present
   * 
   * @param string $header
   * @return boolean
   */
  protected function hasHeader($header) {
    return isset($this->headers[$header]) && !empty($this->headers[$header]);
  }

  /**
   * Returns a specific header content
   * 
   * @param string $header
   * @return string
   */
  protected function getHeader($header) {
    return $this->hasHeader($header) ? $this->headers[$header] : NULL;
  }

}

