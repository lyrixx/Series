<?php

namespace Series\Show\Upstream;

interface ShowTorrentInterface extends ShowInterface
{
    public function getTorrent();

    public function setTorrent($torrent);
}
