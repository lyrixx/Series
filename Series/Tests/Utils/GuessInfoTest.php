<?php

namespace Series\Tests\Utils;

use Series\Utils\GuessInfo;

class GuessInfoTest extends \PHPUnit_Framework_TestCase
{
    private $guessInfo;

    public function setup()
    {
        $this->guessInfo = new GuessInfo();
    }

    /**
    * @dataProvider getExtractVersionTests
    */
    public function testExtractVersion($input, $expected)
    {
        $this->assertEquals($expected, $this->guessInfo->extractVersion($input));
    }

    public function getExtractVersionTests()
    {
        return array(
            array('foo bar S01E02', '1.2'),
            array('foo bar s01e02', '1.2'),
            array('foo bar s01e02', '1.2'),
            array('foo S01E02 bar ', '1.2'),
            array('foo bar 1x02', '1.2'),
            array('foo 1x02 bar ', '1.2'),
            array('foo 1x02 bar ', '1.2'),
            array('????', '0.0'),
        );
    }

    /**
     * @dataProvider getIsSameShowTests
     */
    public function testIsSameShow($show1, $show2, $expected)
    {
        $this->assertEquals($expected, $this->guessInfo->isSameShow($show1, $show2));
    }

    public function getIsSameShowTests()
    {
        return array(
            array('Lost', 'Lost', true),
            array('Lost', 'lost', true),
            array('Lost', 'fooLostbar', true),
            array('Lost', 'foolostbar', true),
            array('Lost', 'foo', false),
            array('Lost', 'foo', false),
            array('C.S.I', 'C-S-I', true),
        );
    }

}
