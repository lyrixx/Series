<?php

namespace Series\Tests\Matcher;

use Series\Matcher\MatchedShowCollection;
use Series\Matcher\MatchedShow;

use Series\Show\Mine\Show as MineShow;

class MatchedShowCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchedShowCollection()
    {
        $matchedShowCollections = new MatchedShowCollection() ;

        $show1 = new MineShow('foo', 1);
        $matchedShow1 = new MatchedShow($show1);

        $matchedShowCollections->add($matchedShow1);

        $this->assertTrue($matchedShowCollections->contains($show1));

        $this->assertEquals($matchedShow1, $matchedShowCollections->get($show1));

        $show2 = new MineShow('bar', 1);
        $matchedShow2 = new MatchedShow($show2);

        $this->assertFalse($matchedShowCollections->contains($show2));

        $matchedShowCollections->add($matchedShow2);

        $this->assertTrue($matchedShowCollections->contains($show2));

        $this->assertEquals($matchedShow2, $matchedShowCollections->get($show2));

    }

}
