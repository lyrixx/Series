<?php

namespace Series\Show\Status;

use Series\Show\Mine\ShowInterface;

interface StatusInterface
{
    public function isAlreadyDownloaded(ShowInterface $show);

    public function setMarkAsDownloaded(ShowInterface $show);
}
