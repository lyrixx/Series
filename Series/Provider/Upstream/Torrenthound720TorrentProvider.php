<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowInterface;

class Torrenthound720TorrentProvider extends TorrenthoundTorrentProvider
{
    const HQ_720 = '720';

    public function addshow(ShowInterface $show)
    {
        if (false !== strpos($show->getTitle(), self::HQ_720)) {
            $this->getShowCollection()->addShow($show);
        }
    }

}
