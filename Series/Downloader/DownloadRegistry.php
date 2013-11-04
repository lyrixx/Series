<?php

namespace Series\Downloader;

use Series\Show\Upstream\ShowInterface;

class DownloadRegistry implements DownloadInterface
{
    private $downloaders;

    public function __construct(array $downloaders = array())
    {
        $this->downloaders = array();

        foreach ($downloaders as $downloader) {
            $this->addDownloader($downloader);
        }
    }

    public function download(ShowInterface $show)
    {
        foreach ($this->downloaders as $downloader) {
            if ($downloader->supports($show->getType())) {
                $downloader->download($show);

                return;
            }
        }

        throw new \RunTimeException(sprintf('There is no downloaders for "%s" type', $show->getType()));
    }

    public function supports($type)
    {
        return true;
    }

    public function getDownloaders()
    {
        return $this->downloaders;
    }

    public function addDownloader(DownloadInterface $downloaders)
    {
        $this->downloaders[] = $downloaders;
    }

    public function setDownloaders($downloaders)
    {
        $this->downloaders = array();

        foreach ($downloaders as $downloader) {
            $this->addDownloader($downloader);
        }

        return $this;
    }
}
