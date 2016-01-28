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

namespace Andou\GitstatusBundle\Output;

use Andou\GitstatusBundle\Model\Status;
use Andou\GitstatusBundle\Model\Message;
use Andou\GitstatusBundle\Api\GitStatusApi;
use Symfony\Component\Console\Output\OutputInterface;

class Renderer {

  /**
   *
   * @var \Symfony\Component\Console\Output\OutputInterface
   */
  protected $output;

  /**
   * Renders a status
   * 
   * @param \Andou\GitstatusBundle\Model\Status $status
   */
  public function renderStatus(Status $status) {
    $this->printStatus($status->getStatus());
    $this->output->writeln(" [" . $status->getLastupdate() . "]");
  }

  /**
   * Renders a message
   * 
   * @param \Andou\GitstatusBundle\Model\Message $message
   */
  public function renderMessage(Message $message) {
    $this->printStatus($message->getStatus());
    $this->output->write(" [" . $message->getCreated() . "] -> ");
    $this->output->writeln($message->getMessage());
  }

  /**
   * Renders an array of messages
   * 
   * @param array $messages
   */
  public function renderMessages($messages) {
    foreach ($messages as $message) {
      $this->printStatus($message->getStatus());
      $this->output->write(" [" . $message->getCreated() . "] -> ");
      $this->output->writeln($message->getMessage());
    }
  }

  /**
   * 
   * @param string $status
   */
  public function printStatus($status) {
    switch ($status) {
      case GitStatusApi::STATUS_MAJOR:
        $this->output->write('<git_major>' . $status . '</>');
        break;
      case GitStatusApi::STATUS_MINOR:
        $this->output->write('<git_minor>' . $status . '</>');
        break;
      case GitStatusApi::STATUS_GOOD:
      default:
        $this->output->write('<git_good>' . $status . '</>');
        break;
    }
  }

  /**
   * 
   * @return \Symfony\Component\Console\Output\OutputInterface
   */
  public function getOutput() {
    return $this->output;
  }

  /**
   * 
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   * @return \Andou\GitstatusBundle\Output\Renderer
   */
  public function setOutput(OutputInterface $output) {
    $this->output = $output;
    return $this;
  }

}

