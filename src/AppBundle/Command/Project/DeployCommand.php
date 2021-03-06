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

namespace AppBundle\Command\Project;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Deploy command
 * 
 *  @author Antonio Pastorino <antonio.pastorino@gmail.com>
 */
class DeployCommand extends ContainerAwareCommand {

  protected function configure() {
    $this
            ->setName('project:deploy')
            ->setDescription('Deploys a project')
            ->addArgument(
                    'name', InputArgument::OPTIONAL, 'Project you want to deploy'
            )
            ->addArgument(
                    'branch', InputArgument::OPTIONAL, 'If set, we will deploy this instead of a default one'
            )
            ->addOption(
                    'grunt', null, InputOption::VALUE_NONE, 'If set, after the deploy grunt will be ran'
            )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $deploymanager = $this->getContainer()->get('app.deploy.manager');
    $name = $input->getArgument('name');
    if (!$name) {
      $output->writeln(sprintf('<error>%s</error>', "Missing project name parameter"));
      $projects = $deploymanager->listProjects();
      if (count($projects)) {
        $output->writeln(sprintf('<comment>%s</comment>', "Available projects are:"));
        foreach ($projects as $p) {
          $output->writeln(sprintf('<comment>%s</comment>', $p->getName()));
        }
      } else {
        $output->writeln(sprintf('<comment>%s</comment>', "No projects available"));
      }
      return;
    }

    $branch = $input->getArgument('branch');
    if ($branch) {
      $output->writeln('Deploying [' . $name . '] with [' . $branch . ']');
    } else {
      $output->writeln('Deploying [' . $name . '] with [default branch]');
      $branch = NULL;
    }
    $usegrunt = FALSE;
    if ($input->getOption('grunt')) {
      $usegrunt = TRUE;
    }



    $deploymanager->deploy($name, $branch, $usegrunt);

    if (!$deploymanager->hasErrors()) {
      $output->writeln('Done!');
    } else {
      foreach ($deploymanager->getErrors() as $error)
        $output->writeln(sprintf('<error>%s</error>', $error->getMessage()));
    }
  }

}