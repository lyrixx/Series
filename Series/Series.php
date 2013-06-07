<?php

namespace Series;

use Series\Downloader\DownloadInterface;
use Series\Downloader\DownloadRegistry;

use Series\Provider\Mine\ProviderInterface as MineProviderInterface;
use Series\Provider\Mine\ProviderRegistry as MineProviderRegistry;

use Series\Provider\Upstream\ProviderInterface as UpstreamProviderInterface;
use Series\Provider\Upstream\ProviderRegistry as UpstreamProviderRegistry;

use Series\Show\Mine\ShowCollection as MineShowCollection;
use Series\Show\Status\StatusRegistry;
use Series\Show\Status\StatusInterface;
use Series\Show\Upstream\ShowCollection as UpstreamShowCollection;
use Series\Matcher\Matcher;
use Series\Matcher\MatchedShowCollection;

class Series
{
    private $mineShowCollection;
    private $upstreamShowCollection;
    private $mineProvider;
    private $upstreamProvider;
    private $matcher;
    private $downloader;
    private $showStatus;

    public function __construct(
        MineProviderInterface $mineProvider            = null,
        UpstreamProviderInterface $upstreamProvider    = null,
        DownloadInterface $downloader                  = null,
        StatusInterface $showStatus                    = null,
        Matcher $matcher                               = null,
        MineShowCollection $mineShowCollection         = null,
        UpstreamShowCollection $upstreamShowCollection = null
    ) {
        $this->mineProvider           = $mineProvider ?: new MineProviderRegistry();
        $this->upstreamProvider       = $upstreamProvider ?: new UpstreamProviderRegistry();
        $this->downloader             = $downloader ?: new DownloadRegistry();
        $this->showStatus             = $showStatus ?: new StatusRegistry();
        $this->matcher                = $matcher ?: new Matcher();
        $this->mineShowCollection     = $mineShowCollection ?: new MineShowCollection();
        $this->upstreamShowCollection = $upstreamShowCollection ?: new UpstreamShowCollection();

        $this->matcher->setMineShowCollection($this->mineShowCollection);
        $this->matcher->setUpstreamShowCollection($this->upstreamShowCollection);
        $this->matcher->setShowStatus($this->showStatus);

    }

    public function getDownloadableShowCollection($byPassShowStatus = false)
    {
        $this->getMineProvider()->setShowCollection($this->getMineShowCollection());
        $this->getMineProvider()->fetch();

        $this->getUpstreamProvider()->setShowCollection($this->getUpstreamShowCollection());
        $this->getUpstreamProvider()->fetch();

        $this->matcher->setMineShowCollection($this->getMineShowCollection());
        $this->matcher->setUpstreamShowCollection($this->getUpstreamShowCollection());

        return $this->matcher->getMatchedShowCollection($byPassShowStatus);
    }

    public function download(MatchedShowCollection $showCollection, $downloadAll = true)
    {
        foreach ($showCollection->getCollection() as $show) {
            foreach ($show->getMatched() as $upstreamShow) {
                if ($upstreamShow->getType() !=$this->downloader->getSupportedType()) {
                    throw new \RunTimeException(sprintf('There is no downloaders for "%s" type', $upstreamShow->getType()));
                }
                $this->downloader->download($upstreamShow);
                $this->showStatus->setMarkAsDownloaded($show->getMineShow());
                if (!$downloadAll) {
                    break;
                }
            }
        }
    }

    public function getMineProvider()
    {
        return $this->mineProvider;
    }

    public function setMineProvider(MineProviderInterface $mineProvider)
    {
        $this->mineProvider = $mineProvider;

        return $this;
    }

    public function getUpstreamProvider()
    {
        return $this->upstreamProvider;
    }

    public function setUpstreamProvider(UpstreamProviderInterface $upstreamProvider)
    {
        $this->upstreamProvider = $upstreamProvider;

        return $this;
    }

    public function getMineShowCollection()
    {
        return $this->mineShowCollection;
    }

    public function setMineShowCollection(MineShowCollection $newMineShowCollection)
    {
        $this->mineShowCollection = $newMineShowCollection;

        return $this;
    }

    public function getUpstreamShowCollection()
    {
        return $this->upstreamShowCollection;
    }

    public function setUpstreamShowCollection(UpstreamShowCollection $newUpstreamShowCollection)
    {
        $this->upstreamShowCollection = $newUpstreamShowCollection;

        return $this;
    }

    public function getDownloader()
    {
        return $this->downloader;
    }

    public function setDownloader(DownloadInterface $downloader)
    {
        $this->downloader = $downloader;

        return $this;
    }

    public function getShowStatus()
    {
        return $this->showStatus;
    }

    public function setShowStatus(StatusInterface $newShowStatus)
    {
        $this->showStatus = $newShowStatus;

        return $this;
    }

    public function getMatcher()
    {
        return $this->matcher;
    }

    public function setMatcher($matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }

}
