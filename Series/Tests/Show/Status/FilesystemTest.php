<?php

namespace Series\Tests\Show\Status;

use Series\Show\Status\Filesystem;
use Series\Show\Mine\Show as UpstreamShow;

class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    public function testIsNotAlreadyDownloaded()
    {
        $showCollectionPath = sprintf(__DIR__.'/../../../../cache/downloadTorrent-test-f-%s.txt', md5(time()));

        $show1 = new UpstreamShow('foo', '1.0');
        $show2 = new UpstreamShow('foo', '1.1');

        $statusFilesystem = new Filesystem($showCollectionPath);

        $this->assertFalse($statusFilesystem->isAlreadyDownloaded($show1));
        $this->assertFalse($statusFilesystem->isAlreadyDownloaded($show2));

        unlink($showCollectionPath);
    }

    public function testIsAlreadyDownloaded()
    {
        $showCollectionPath = sprintf(__DIR__.'/../../../../cache/downloadTorrent-test-t-%s.txt', md5(time()));

        $show1 = new UpstreamShow('foo', '1.0');
        $show2 = new UpstreamShow('foo', '1.1');

        file_put_contents($showCollectionPath, $show1->__toString().PHP_EOL, FILE_APPEND );
        file_put_contents($showCollectionPath, $show2->__toString().PHP_EOL, FILE_APPEND );

        $statusFilesystem = new Filesystem($showCollectionPath);

        $this->assertTrue($statusFilesystem->isAlreadyDownloaded($show1));
        $this->assertTrue($statusFilesystem->isAlreadyDownloaded($show2));

        unlink($showCollectionPath);
    }
}
