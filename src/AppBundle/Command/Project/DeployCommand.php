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

    $repo = new \AppBundle\Models\Repository();
    $repo->setUrl("https://api.github.com/repos/andou/wed/tarball");

    $project = new \AppBundle\Models\Project();
    $project->setName("Wed");
    $project->setDefaultBranch('master');
    $project->setRepository($repo);

    $localdata = new \AppBundle\Models\LocalData();
    $localdata->setAppPath('/tmp/deploy/');
    $localdata->setExtractDir("/tmp");
    $localdata->setUser("www-data:www-data");

    $deploymanager = $this->getContainer()->get('app.deploy.manager');
    $deploymanager->setProject($project);
    $deploymanager->setLocaldata($localdata);

    $deploymanager->deploy($branch);
    $output->writeln('deployed');
//    return;
//
//    $downloader = $this->getContainer()->get('app.operations.downloader');
//    $downloader
//            //->setUser("gitmama")
//            //->setPassword("gitmama12")
//            ->setName("wed")
//            ->setDestination("/tmp")
//            ->setUrl("https://api.github.com/repos/andou/wed/tarball/master")
//    ;
//    $file = $downloader->run();
//    $output->writeln($file);
//
//    //
//    $extractor = $this->getContainer()->get('app.operations.extractor');
//    $folder = $extractor->setFile($file)->setDestination('/tmp')->run();
//    $output->writeln($folder);
//
//    $rsync = $this->getContainer()->get('app.operations.rsync');
//    $out = $rsync->setExtractDir($folder . '/')->setAppPath('/tmp/deploy/')->run();
//    $output->writeln($out);
//
//
//    $chown = $this->getContainer()->get('app.operations.chown');
//    $chown->setFile('/tmp/deploy/')->setUser('www-data:www-data')->run();
//
//    //
//    $remover = $this->getContainer()->get('app.operations.remover');
//    $out = $remover->setFile($folder)->run();
//    $output->writeln($out ? 'removed' : 'not removed');
  }

}