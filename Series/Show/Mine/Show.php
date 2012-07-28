<?php

namespace Series\Show\Mine;

class Show implements ShowInterface
{

    private $title;
    private $version;

    public function __construct($title = null, $version = null)
    {
        $this->title   = $title;
        $this->version = $version;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($newTitle)
    {
        $this->title = $newTitle;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($newVersion)
    {
        $this->version = $newVersion;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s---%s', $this->title, $this->version);
    }
}
