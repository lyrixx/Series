<?php

namespace Series\Tests\Provider\Upstream;

use Series\Provider\Upstream\ProviderRegistry;
use Series\Provider\Upstream\ProviderInterface;
use Series\Show\Upstream\ShowCollection;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testFetch()
    {
        $showCollection = new ShowCollection();

        $providerFake1 = $this->getMock('Series\Provider\Upstream\ProviderInterface');
        $providerFake1
            ->expects($this->exactly(1))
            ->method('fetch')
            ->will($this->returnValue($showCollection))
        ;
        $providerFake2 = $this->getMock('Series\Provider\Upstream\ProviderInterface');
        $providerFake2
            ->expects($this->exactly(1))
            ->method('fetch')
            ->will($this->returnValue($showCollection))
        ;

        $registry = new ProviderRegistry(array($providerFake1, $providerFake2), $showCollection);

        $ret = $registry->fetch();

        $this->assertEquals($showCollection, $ret);
    }

}
