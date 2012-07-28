<?php

namespace Series\Downloader;

use Series\Show\Upstream\ShowInterface;

interface DownloadInterface
{
    public function download(ShowInterface $show);

    public function getSupportedType();
}
