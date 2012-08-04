<?php

namespace Series\Tests\Provider\Upstream;

use Series\Provider\Upstream\Torrenthound720TorrentProvider;
use Series\Show\Upstream\ShowCollection;

class Torrenthound720TorrentProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testFetch()
    {
        $response = new \Buzz\Message\Response();
        $response->setContent(file_get_contents(__DIR__.'/Fixtures/Torrenthound.xml'));
        $response->setHeaders(array('HTTP/1.1 200 OK'));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($response))
        ;

        $showCollection = new ShowCollection();
        $provider = new Torrenthound720TorrentProvider($browser, '\\Series\\Show\\Upstream\\ShowTorrent', $showCollection);
        $provider->fetch();

        $this->assertCount(0, $showCollection->getShows());

        $response->setContent(file_get_contents(__DIR__.'/Fixtures/Torrenthound720.xml'));
        $provider->fetch();
        $this->assertCount(1, $showCollection->getShows());
    }

}
