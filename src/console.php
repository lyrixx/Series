<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('My Downloader Application', '0.1');

$console
    ->register('download')
    ->setDescription('Download latest episodes')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $series = $app['series.serie'];

        $showsDownloadable = $series->getDownloadableShowCollection();

        if (!count($showsDownloadable->getCollection())) {
            return $output->write('<info>No new show to download</info>', true);
        }

        $output->write('<info>The following show will be downloaded:</info>', true);
        $output->writeln($showsDownloadable->getCollection());

        $series->download($showsDownloadable, $app['series.download_all']);

        return $output->write('<info>Bye bye</info>', true);

    })
;

return $console;
