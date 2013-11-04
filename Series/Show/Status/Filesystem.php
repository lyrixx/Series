<?php

namespace Series\Show\Status;

use Series\Show\Mine\ShowInterface;

class Filesystem implements StatusInterface
{
    private $showCollectionPath;
    private $showCollection;

    public function __construct($showCollectionPath = null)
    {
        $showCollectionPath = $showCollectionPath ?: __DIR__.'/../../../cache/downloadTorrent.txt';

        if (!file_exists($showCollectionPath) && !touch($showCollectionPath)) {
            throw new \RuntimeException(sprintf('Can not create file "%"', $showCollectionPath));
        }

        $this->showCollectionPath = $showCollectionPath;
        $this->showCollection     = file($showCollectionPath, FILE_IGNORE_NEW_LINES);
    }

    public function isDownloaded(ShowInterface $show)
    {
        return in_array($show->__toString(), $this->showCollection);
    }

    public function markAsDownloaded(ShowInterface $show)
    {
        file_put_contents($this->showCollectionPath, $show->__toString().PHP_EOL, FILE_APPEND);
    }
}
