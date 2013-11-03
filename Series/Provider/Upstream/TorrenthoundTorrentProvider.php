<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowCollection;
use Series\Show\Upstream\ShowInterface;
use Series\Utils\GuessInfo;

class TorrenthoundTorrentProvider extends FeedProviderAbstract
{
    CONST FEED        = 'http://www.torrenthound.com/rss.php?s=latest';
    CONST TORRENT_URL = 'http://www.torrenthound.com/torrent/%s';
    CONST CATEGORY    = 'Video > TV shows';

    public function __construct($browser, $showClass = null, ShowCollection $showCollection = null, GuessInfo $guessInfo = null)
    {
        $showClass = $showClass ?: 'Series\Show\Upstream\ShowTorrent';

        parent::__construct(self::FEED, $browser, $showClass, $showCollection, $guessInfo);
    }

    public function fetch()
    {
        $response = $this->getBrowser()->get($this->getFeed());

        if (200 != $code = $response->getStatusCode()) {
            throw new \RunTimeException(sprintf('Can not fetch rss feed (return code : "%s", url : "%s")', $code, $this->getFeed()));
        }

        $x = simplexml_load_string($response->getContent());

        if (0 == count($x)) {
            return ;
        }

        $showClass = $this->getShowClass();
        foreach ($x->channel->item as $item) {
            $show = new $showClass();

            if (self::CATEGORY != (string) $item->category) {
                continue;
            }

            $show->setTitle((string) $item->title);
            $show->setVersion($this->getGuessInfo()->extractVersion((string) $item->title));
            $show->setTorrent(sprintf(self::TORRENT_URL, (string) $item->info_hash));

            $this->addShow($show);
        }
    }

    public function addshow(ShowInterface $show)
    {
        $this->getShowCollection()->addShow($show);
    }

}
