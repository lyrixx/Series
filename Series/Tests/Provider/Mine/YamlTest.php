<?php

namespace Series\Tests\Provider\Mine;

use Series\Provider\Mine\Yaml;

class YamlTest extends \PHPUnit_Framework_TestCase
{

    public function testFetch()
    {
        $yaml = new Yaml(__DIR__.'/Fixtures/file01.yml');

        foreach ($yaml->fetch() as $show) {
            $this->assertEquals('foo', $show->getTitle());
            $this->assertEquals('1.3', $show->getVersion());
        }
    }

    public function testFetchWithoutVersion()
    {
        $yaml = new Yaml(__DIR__.'/Fixtures/file02.yml');

        foreach ($yaml->fetch() as $show) {
            $this->assertTrue(in_array($show->getTitle(), array('foo', 'bar')));
            $this->assertEquals('0.0', $show->getVersion());
        }
    }
}
