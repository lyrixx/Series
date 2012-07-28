Series
======

[![Build Status](https://secure.travis-ci.org/lyrixx/Series.png?branch=master)](http://travis-ci.org/lyrixx/Series)

Series is a software able to download automatically your favorite TV shows.

Basically, Series will lookup into different sources (e.g. a rss feed)
and will compare these feeds with shows you want to download.

Installation
------------

install composer then run :

    php composer.phar create-project lyrixx/Series

Then you have to setup some configurations. If you want, you can use the
default configuration :

    cp config/config.php-dist config/config.php
    cp config/show.yml-dist config/show.yml

With the default configuration, Series will try to download tv show listed
in `cp config/show.yml` from the rss feed of `http://www.dailytvtorrents.org`.
If it find something, it will copy the `.torrent` to a local folder. You can
customize the location thanks to `config/config.php`. Just replace the value
of `$app['series.extension.downloader.torrent_inotify.path']`.

Then, if you have a good torrent downloader, you have to configure it to make
it scan the folder :

- For transmission : http://www.makeuseof.com/tag/how-to-automate-organize-torrents-with-the-transmission-downloader-mac/

Usage
-----

just run :

    ./console download
