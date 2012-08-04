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

    /**
     * @dataProvider getDownloadTest
     */
    public function testDownload($i, $downloadAll)
    {
        $series = new Series();

        $downloadFake = $this->getMock('Series\Downloader\DownloadInterface');
        $downloadFake
            ->expects($this->exactly($i))
            ->method('getSupportedType')
            ->will($this->returnValue('FAKE'))
        ;
        $downloadFake
            ->expects($this->exactly($i))
            ->method('download')
        ;
        $series->setDownloader($downloadFake);

        $statusFake = $this->getMock('Series\Show\Status\StatusInterface');
        $statusFake
            ->expects($this->exactly($i))
            ->method('setMarkAsDownloaded')
        ;
        $series->setShowStatus($statusFake);

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

    public function getDownloadTest()
    {
        return array(
            array(1, false),
            array(4, true),
        );
    }
}

class UpstreamShow extends UpstreamShowAbstract
{
    public function __construct($title = null, $version = null)
    {
        parent::__construct($title, $version, 'FAKE');
    }
}
