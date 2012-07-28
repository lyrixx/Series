<?php

namespace Series\Show\Upstream;

abstract class ShowAbstract implements ShowInterface
{

    private $title;
    private $version;
    private $type;

    public function __construct($title = null, $version = null, $type = null)
    {
        $this->title   = $title;
        $this->version = $version;
        $this->type    = $type;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($newType)
    {
        $this->type = $newType;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s---%s', $this->title, $this->version);
    }

}
