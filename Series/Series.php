<?php

namespace Series;

use Series\Downloader\DownloadInterface;
use Series\Downloader\DownloadRegistry;
use Series\Downloader\TorrentInotify;
use Series\Matcher\MatchedShowCollection;
use Series\Matcher\Matcher;
use Series\Provider\Mine\ProviderInterface as MineProviderInterface;
use Series\Provider\Mine\ProviderRegistry as MineProviderRegistry;
use Series\Provider\Mine\Yaml;
use Series\Provider\Upstream\ProviderInterface as UpstreamProviderInterface;
use Series\Provider\Upstream\ProviderRegistry as UpstreamProviderRegistry;
use Series\Provider\Upstream\Torrenthound720TorrentProvider;
use Series\Provider\Upstream\TorrenthoundTorrentProvider;
use Series\Show\Mine\ShowCollection as MineShowCollection;
use Series\Show\Status\Filesystem;
use Series\Show\Status\StatusInterface;
use Series\Show\Status\StatusRegistry;
use Series\Show\Upstream\ShowCollection as UpstreamShowCollection;

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
        MineProviderInterface $mineProvider = null,
        UpstreamProviderInterface $upstreamProvider = null,
        DownloadInterface $downloader = null,
        StatusInterface $showStatus = null,
        Matcher $matcher = null
    ) {
        $this->mineProvider = $mineProvider ?: new MineProviderRegistry();
        $this->upstreamProvider = $upstreamProvider ?: new UpstreamProviderRegistry();
        $this->downloader = $downloader ?: new DownloadRegistry();
        $this->showStatus = $showStatus ?: new StatusRegistry();
        $this->matcher = $matcher ?: new Matcher();

        $this->mineShowCollection = new MineShowCollection();
        $this->upstreamShowCollection = new UpstreamShowCollection();

        $this->matcher->setMineShowCollection($this->mineShowCollection);
        $this->matcher->setUpstreamShowCollection($this->upstreamShowCollection);
        $this->matcher->setShowStatus($this->showStatus);
    }

    public static function createWithConfig($configFile = null)
    {
        $series = new self();

        $app = new \Pimple();
        $series->loadDefaultServices($app);
        $series->loadUserConfig($app, $configFile);

        return $series;
    }

    public function getDownloadableShowCollection($byPassShowStatus = false)
    {
        $this->mineProvider->setShowCollection($this->mineShowCollection);
        $this->mineProvider->fetch();

        $this->upstreamProvider->setShowCollection($this->upstreamShowCollection);
        $this->upstreamProvider->fetch();

        $this->matcher->setMineShowCollection($this->mineShowCollection);
        $this->matcher->setUpstreamShowCollection($this->upstreamShowCollection);

        return $this->matcher->getMatchedShowCollection($byPassShowStatus);
    }

    public function download(MatchedShowCollection $showCollection, $downloadAll = true)
    {
        foreach ($showCollection->getCollection() as $show) {
            foreach ($show->getMatched() as $upstreamShow) {
                if (!$this->downloader->supports($upstreamShow->getType())) {
                    throw new \RunTimeException(sprintf('There is no downloaders for "%s" type', $upstreamShow->getType()));
                }
                $this->downloader->download($upstreamShow);
                $this->showStatus->markAsDownloaded($show->getMineShow());
                if (!$downloadAll) {
                    break;
                }
            }
        }
    }

    private function loadDefaultServices(\Pimple $app)
    {
        $app['series.provider.mine.yaml.path'] = __DIR__.'/../config/show.yml';
        $app['series.provider.mine.yaml'] = function ($app) {
            return new Yaml($app['series.provider.mine.yaml.path']);
        };

        $app['series.provider.upstream.torrent_hound'] = function () {
            return new TorrenthoundTorrentProvider(new \Buzz\Browser());
        };
        $app['series.provider.upstream.torrent_hound_720'] = function () {
            return new Torrenthound720TorrentProvider(new \Buzz\Browser());
        };

        $app['series.downloader.torrent_inotify.path'] = __DIR__.'/../cache/torrent/';
        $app['series.downloader.torrent_inotify'] = function ($app) {
            return new TorrentInotify($app['series.downloader.torrent_inotify.path']);
        };

        $app['series.status.filesystem.file'] = __DIR__.'/../cache/downloadedTorrent.txt';
        $app['series.status.filesystem'] = function ($app) {
            return new Filesystem($app['series.status.filesystem.file']);
        };
    }

    private function loadUserConfig($app, $configFile = null)
    {
        if (!$configFile) {
            $configFile = __DIR__.'/../config/config.php-dist';
        }

        if (!file_exists($configFile) || !is_readable($configFile)) {
            throw new \InvalidArgumentException(sprintf('Unable to read %s', $configFile));
        }

        require $configFile;

        /* Mine provider */
        if (!isset($app['series.provider.mine'])) {
            throw new \InvalidArgumentException('You must defined the key "series.provider.mine".');
        }
        if (!is_array($app['series.provider.mine'])) {
            $app['series.provider.mine'] = array($app['series.provider.mine']);
        }
        $this->mineProvider->setProviders($app['series.provider.mine']);

        /* Upstream provider */
        if (!isset($app['series.provider.upstream'])) {
            throw new \InvalidArgumentException('You must defined the key "series.provider.upstream".');
        }
        if (!is_array($app['series.provider.upstream'])) {
            $app['series.provider.upstream'] = array($app['series.provider.upstream']);
        }
        $this->upstreamProvider->setProviders($app['series.provider.upstream']);

        /* Downloader */
        if (!isset($app['series.downloader'])) {
            throw new \InvalidArgumentException('You must defined the key "series.downloader".');
        }
        if (!is_array($app['series.downloader'])) {
            $app['series.downloader'] = array($app['series.downloader']);
        }
        $this->downloader->setDownloaders($app['series.downloader']);

        /* Show Status */
        if (!isset($app['series.status'])) {
            throw new \InvalidArgumentException('You must defined the key "series.status".');
        }
        if (!is_array($app['series.status'])) {
            $app['series.status'] = array($app['series.status']);
        }
        $this->showStatus->setStatuses($app['series.status']);

        /*
            Share the same show status between the Matcher (will call isDownloaded)
            And the Serie Object (will call markAsDownloaded)
         */
        $this->matcher->setShowStatus($this->showStatus);
    }
}
