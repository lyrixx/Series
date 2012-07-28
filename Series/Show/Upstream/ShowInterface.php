<?php

namespace Series\Show\Upstream;

interface ShowInterface
{
    const TYPE_TORRENT = 1;

    public function getTitle();

    public function setTitle($title);

    public function getVersion();

    public function setVersion($version);

    public function getType();

    public function setType($type);

    public function __toString();

}
