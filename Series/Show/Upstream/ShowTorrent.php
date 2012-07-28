<?php

namespace Series\Show\Upstream;

class ShowTorrent extends ShowAbstract implements ShowTorrentInterface
{

    private $torrent;

    public function __construct($title = null, $version = null, $torrent = null)
    {
        parent::__construct($title, $version, ShowInterface::TYPE_TORRENT);

        $this->torrent = $torrent;
    }

    public function getTorrent()
    {
        return $this->torrent;
    }

    public function setTorrent($newTorrent)
    {
        $this->torrent = $newTorrent;

        return $this;
    }

}
