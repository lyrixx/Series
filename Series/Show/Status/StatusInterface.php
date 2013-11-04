<?php

namespace Series\Show\Status;

use Series\Show\Mine\ShowInterface;

interface StatusInterface
{
    public function isDownloaded(ShowInterface $show);

    public function markAsDownloaded(ShowInterface $show);
}
