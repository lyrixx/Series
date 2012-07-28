<?php

namespace Series\Show\Mine;

interface ShowInterface
{
    public function getTitle();

    public function setTitle($title);

    public function getVersion();

    public function setVersion($version);

    public function __toString();
}
