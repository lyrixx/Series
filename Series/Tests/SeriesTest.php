<?php

namespace Series\Tests;

use Series\Series;
use Series\Downloader\DownloadInterface;
use Series\Show\Upstream\ShowAbstract as UpstreamShowAbstract;
use Series\Show\Mine\Show as MineShow;
use Series\Matcher\MatchedShowCollection;
use Series\Matcher\MatchedShow;

class SeriesTests extends \PHPUnit_Framework_TestCase
{
    public function getDownloadTest()
    {
        return array(
            array(1, false),
            array(4, true),
        );
    }

    /**
     * @dataProvider getDownloadTest
     */
    public function testDownload($i, $downloadAll)
    {
        $downloader = $this->getMock('Series\Downloader\DownloadInterface');
        $downloader
            ->expects($this->exactly($i))
            ->method('supports')
            ->will($this->returnValue(true))
        ;
        $downloader
            ->expects($this->exactly($i))
            ->method('download')
        ;

        $status = $this->getMock('Series\Show\Status\StatusInterface');
        $status
            ->expects($this->exactly($i))
            ->method('markAsDownloaded')
        ;

        $series = new Series(null, null, $downloader, $status);

        $show1 = new MineShow('foo', 1.0);
        $matchedShow1 = new MatchedShow($show1);

        $matchedShow1->addMatchedShow(new UpstreamShow('foo-1', '1.0'));
        $matchedShow1->addMatchedShow(new UpstreamShow('foo-2', '1.0'));
        $matchedShow1->addMatchedShow(new UpstreamShow('foo-3', '1.0'));
        $matchedShow1->addMatchedShow(new UpstreamShow('foo-4', '1.0'));

        $showCollection = new MatchedShowCollection();
        $showCollection->add($matchedShow1);

        $series->download($showCollection, $downloadAll);
    }

    public function testCreateWithConfig()
    {
        $series = Series::createWithConfig();
    }
}

class UpstreamShow extends UpstreamShowAbstract
{
    public function __construct($title = null, $version = null)
    {
        parent::__construct($title, $version, 'FAKE');
    }
}
