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

    $deploymanager = $this->getContainer()->get('app.deploy.manager');
    $deploymanager->deploy();
    
    return;

    $downloader = $this->getContainer()->get('app.operations.downloader');
    $downloader
            //->setUser("gitmama")
            //->setPassword("gitmama12")
            ->setName("wed")
            ->setDestination("/tmp")
            ->setUrl("https://api.github.com/repos/andou/wed/tarball/master")
    ;
    $file = $downloader->run();
    $output->writeln($file);

    //
    $extractor = $this->getContainer()->get('app.operations.extractor');
    $folder = $extractor->setFile($file)->setDestination('/tmp')->run();
    $output->writeln($folder);

    $rsync = $this->getContainer()->get('app.operations.rsync');
    $out = $rsync->setExtractDir($folder . '/')->setAppPath('/tmp/deploy/')->run();
    $output->writeln($out);


    $chown = $this->getContainer()->get('app.operations.chown');
    $chown->setFile('/tmp/deploy/')->setUser('www-data:www-data')->run();
//
//    //
//    $remover = $this->getContainer()->get('app.operations.remover');
//    $out = $remover->setFile($folder)->run();
//    $output->writeln($out ? 'removed' : 'not removed');
  }

}