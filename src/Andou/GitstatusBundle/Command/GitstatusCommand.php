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

namespace Andou\GitstatusBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GitstatusCommand extends ContainerAwareCommand {

  /**
   * 
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   * @return \Andou\GitstatusBundle\Command\GitstatusCommand
   */
  protected function initStyles(OutputInterface $output) {
    $this->getStyles()->initStyles($output);
    return $this;
  }

  /**
   * 
   * @return \Andou\GitstatusBundle\Api\GitStatusApi
   */
  protected function getApi() {
    return $this->getContainer()->get('andou_gitstatus.api');
  }

  /**
   * 
   * @return \Andou\GitstatusBundle\Output\OutputStyle
   */
  protected function getStyles() {
    return $this->getContainer()->get('andou_gitstatus.styles');
  }

  /**
   * 
   * @return \Andou\GitstatusBundle\Output\Renderer
   */
  protected function getRenderer($output) {
    return $this->getContainer()->get('andou_gitstatus.renderer')->setOutput($output);
  }

}
