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
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $name = $input->getArgument('name');
    $output->writeln('Deploying [' . $name . ']');
  }

}