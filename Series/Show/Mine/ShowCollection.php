<?php

namespace Series\Show\Mine;

class ShowCollection implements \Iterator
{
    private $shows     = array();
    private $position  = 0;

    public function __construct(array $shows = array())
    {
        $this->setShows($shows);
    }

    public function addShow(ShowInterface $show)
    {
      $this->shows[] = $show;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->shows[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return array_key_exists($this->position, $this->shows);
    }

    public function getShows()
    {
        return $this->shows;
    }

    public function setShows(array $newShows)
    {
        foreach ($newShows as $show) {
            $this->addShow($show);
        }

        return $this;
    }
}
