<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowCollection;

class DailyTvTorrentProvider extends FeedProviderAbstract
{
    CONST FEED_PREFER_720 = 'http://www.dailytvtorrents.org/rss/allshows?prefer=720';

    public function __construct($browser, $showClass = null, ShowCollection $showCollection = null, GuessInfo $guessInfo = null)
    {
        $showClass = $showClass ?: 'Series\Show\Upstream\ShowTorrent';

        parent::__construct(self::FEED_PREFER_720, $browser, $showClass, $showCollection, $guessInfo);
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

            $show->setTitle((string) $item->title);
            $show->setVersion($this->getGuessInfo()->extractVersion((string) $item->title));
            $show->setTorrent((string) $item->enclosure['url']);

            $this->getShowCollection()->addShow($show);
        }
    }

}
