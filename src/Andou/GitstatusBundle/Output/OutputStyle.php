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

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Outputter
 *
 * @author andou
 */
class OutputStyle {

  /**
   *
   * @var string
   */
  protected static $status_good_style_fg_color = "green";

  /**
   *
   * @var string
   */
  protected static $status_good_style_bg_color = "black";

  /**
   *
   * @var string
   */
  protected static $status_good_style_options = "bold";

  /**
   *
   * @var string
   */
  protected static $status_minor_style_fg_color = "yellow";

  /**
   *
   * @var string
   */
  protected static $status_minor_style_bg_color = "black";

  /**
   *
   * @var string
   */
  protected static $status_minor_style_options = "bold;underscore";

  /**
   *
   * @var string
   */
  protected static $status_major_style_fg_color = "red";

  /**
   *
   * @var string
   */
  protected static $status_major_style_bg_color = "black";

  /**
   *
   * @var string
   */
  protected static $status_major_style_options = "bold;blink";

  /**
   *
   * @var string
   */
  protected static $option_delimiter = ";";

  /**
   * 
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   * @return \Andou\GitstatusBundle\Output\OutputStyle
   */
  public function initStyles($output) {
    $output->getFormatter()->setStyle('git_major', $this->getStatusMajorStyle());
    $output->getFormatter()->setStyle('git_minor', $this->getStatusMinorStyle());
    $output->getFormatter()->setStyle('git_good', $this->getStatusGoodStyle());
    return $this;
  }

  /**
   * 
   * @param type $foreground
   * @param type $background
   * @param type $options
   * @return \Symfony\Component\Console\Formatter\OutputFormatterStyle
   */
  public function getStyle($foreground, $background, $options) {
    return new OutputFormatterStyle($foreground, $background, explode(self::$option_delimiter, $options));
  }

  /**
   * 
   * @return \Symfony\Component\Console\Formatter\OutputFormatterStyle
   */
  public function getStatusMajorStyle() {
    return $this->getStyle(self::$status_major_style_fg_color, self::$status_major_style_bg_color, self::$status_major_style_options);
  }

  /**
   * 
   * @return \Symfony\Component\Console\Formatter\OutputFormatterStyle
   */
  public function getStatusMinorStyle() {
    return $this->getStyle(self::$status_minor_style_fg_color, self::$status_minor_style_bg_color, self::$status_minor_style_options);
  }

  /**
   * 
   * @return \Symfony\Component\Console\Formatter\OutputFormatterStyle
   */
  public function getStatusGoodStyle() {
    return $this->getStyle(self::$status_good_style_fg_color, self::$status_good_style_bg_color, self::$status_good_style_options);
  }

}
