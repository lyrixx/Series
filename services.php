<?php

use Series\Series;
use Series\Downloader\TorrentInotify;
use Series\Provider\Upstream\DailyTvTorrentProvider;
use Series\Provider\Upstream\TorrenthoundTorrentProvider;
use Series\Provider\Upstream\Torrenthound720TorrentProvider;
use Series\Provider\Mine\Yaml;
use Series\Show\Status\Filesystem;

// Global
$app['series.download_all'] = false;

// series
$app['series.serie'] = function($app) {
    $series = new Series();

    /** Provider */
    if (isset($app['series.provider.mine'])) {
        if (is_array($app['series.provider.mine'])) {
            foreach ($app['series.provider.mine'] as $provider) {
                $series->getMineProvider()->addProvider($provider);
            }
        } else {
            $series->setMineProvider($app['series.provider.mine']);
        }
    }

    if (isset($app['series.provider.upstream'])) {
        if (is_array($app['series.provider.upstream'])) {
            foreach ($app['series.provider.upstream'] as $provider) {
                $series->getUpstreamProvider()->addProvider($provider);
            }
        } else {
            $series->setUpstreamProvider($app['series.provider.upstream']);
        }
    }

    /** Downloader */
    if (isset($app['series.downloader'])) {
        if (is_array($app['series.downloader'])) {
            foreach ($app['series.downloader'] as $downloader) {
                $series->getDownloader()->addDownloader($downloader);
            }
        } else {
            $series->setDownloader($app['series.downloader']);
        }
    }

    /** Show Status */
    if (isset($app['series.show.status'])) {
        if (is_array($app['series.show.status'])) {
            foreach ($app['series.show.status'] as $status) {
                $series->getShowStatus()->addStatus($status);
            }
        } else {
            $series->setShowStatus($app['series.show.status']);
        }

        // Share the same show status between the Matcher (will call isAlreadyDownloaded)
        // And the Serie Object (will call setMarkAsDownloaded)
        $series->getMatcher()->setShowStatus($series->getShowStatus());
    }

    return $series;

};

// Declaration of core/default services

$app['series.extension.provider.mine.yaml.path'] = __DIR__.'/config/show.yml';
$app['series.extension.provider.mine.yaml']      = function ($app) {
    return new Yaml($app['series.extension.provider.mine.yaml.path']);
};

$app['series.extension.provider.upsteam.daily_tv_torrent'] = function() {
    return new DailyTvTorrentProvider(new Buzz\Browser());
};
$app['series.extension.provider.upsteam.torrent_hound'] = function() {
    return new TorrenthoundTorrentProvider(new Buzz\Browser());
};
$app['series.extension.provider.upsteam.torrent_hound_720'] = function() {
    return new Torrenthound720TorrentProvider(new Buzz\Browser());
};

$app['series.extension.downloader.torrent_inotify.path'] = __DIR__.'/cache/torrent/';
$app['series.extension.downloader.torrent_inotify']      = function($app) {
    return new TorrentInotify($app['series.extension.downloader.torrent_inotify.path']);
};

$app['series.extension.show.status.filesystem.file'] = __DIR__.'/cache/downloadTorrent.txt';
$app['series.extension.show.status.filesystem']      = function($app) {
    return new Filesystem($app['series.extension.show.status.filesystem.file']);
};
