<?php

namespace AppBundle\Command\Project;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends ContainerAwareCommand {

  protected function configure() {
    $this
            ->setName('project:deploy')
            ->setDescription('Deploys a project')
            ->addArgument(
                    'name', InputArgument::REQUIRED, 'Project you want to deploy'
            )
            ->addArgument(
                    'branch', InputArgument::OPTIONAL, 'If set, we will deploy this instead of a default one'
            )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $name = $input->getArgument('name');

    $branch = $input->getArgument('branch');
    if ($branch) {
      $output->writeln('Deploying [' . $name . '] with [' . $branch . ']');
    } else {
      $output->writeln('Deploying [' . $name . '] with [default branch]');
      $branch = NULL;
    }


    $deploymanager = $this->getContainer()->get('app.deploy.manager');
    $deploymanager->deploy($name, $branch);
  }

}