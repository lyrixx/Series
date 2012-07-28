<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowCollection;
use Series\Utils\GuessInfo;

abstract class FeedProviderAbstract implements ProviderInterface
{

    private $feed;
    private $browser;
    private $showCollection;
    private $showClass;
    private $guessInfo;

    public function __construct($feed, $browser, $showClass, ShowCollection $showCollection = null, GuessInfo $guessInfo = null)
    {
        $this->feed           = $feed;
        $this->browser        = $browser;
        $this->showClass      = $showClass;
        $this->showCollection = $showCollection ?: new ShowCollection();
        $this->guessInfo      = $guessInfo ?: new GuessInfo();
    }

    public function getFeed()
    {
        return $this->feed;
    }

    public function setFeed($newFeed)
    {
        $this->feed = $newFeed;

        return $this;
    }

    public function getbrowser()
    {
        return $this->browser;
    }

    public function setbrowser($newbrowser)
    {
        $this->browser = $newbrowser;

        return $this;
    }

    public function getShowCollection()
    {
        return $this->showCollection;
    }

    public function setShowCollection(ShowCollection $newShowCollection)
    {
        $this->showCollection = $newShowCollection;

        return $this;
    }

    public function getShowClass()
    {
        return $this->showClass;
    }

    public function setShowClass($newShowClass)
    {
        $this->showClass = $newShowClass;

        return $this;
    }

    public function getGuessInfo()
    {
        return $this->guessInfo;
    }

    public function setGuessInfo($newGuessInfo)
    {
        $this->guessInfo = $newGuessInfo;

        return $this;
    }
}
