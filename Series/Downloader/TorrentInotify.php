<?php

namespace Series\Downloader;

use Series\Show\Upstream\ShowInterface;

class TorrentInotify implements DownloadInterface
{

    private $downloadDir;

    public function __construct($downloadDir)
    {
        if (!file_exists($downloadDir) && !mkdir($downloadDir)) {
            throw new \RuntimeException(sprintf('Can not create folder "%s"', $downloadDir));
        }

        $this->downloadDir = $downloadDir;
    }

    public function download(ShowInterface $show)
    {
        return copy($show->getTorrent(), $this->downloadDir.$show->__toString().'.torrent');
    }

    public function supports($type)
    {
        return $type === ShowInterface::TYPE_TORRENT;
    }

}
