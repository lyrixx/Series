<?php

namespace Series\Tests\Provider\Upstream;

use Series\Provider\Upstream\DailyTvTorrentProvider;
use Series\Show\Upstream\ShowCollection;

class DailyTvTorrentProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testFetch()
    {
        $response = new \Buzz\Message\Response();
        $response->setContent(file_get_contents(__DIR__.'/Fixtures/DailyTvTorrent.xml'));
        $response->setHeaders(array('HTTP/1.1 200 OK'));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response))
        ;

        $showCollection = new ShowCollection();
        $provider = new DailyTvTorrentProvider($browser);
        $provider->setShowCollection($showCollection);
        $provider->fetch();

        $this->assertCount(100, $showCollection->getShows());
    }

}
