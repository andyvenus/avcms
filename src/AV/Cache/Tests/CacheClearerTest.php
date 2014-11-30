<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 22:59
 */

namespace AV\Cache\Tests;

use AV\Cache\CacheClearer;
use org\bovigo\vfs\vfsStream;

class CacheClearerTest extends \PHPUnit_Framework_TestCase
{
    private $rootDir;

    /**
     * @var CacheClearer
     */
    private $cacheClearer;

    public function setUp()
    {
        $this->rootDir = vfsStream::setup('root', 0777);
        $this->rootDir = vfsStream::create(array(
            'cacheDir' => array(
                'foo' => array(
                    'resources' => array(
                        'text' => array(
                            'foo.txt' => 'foo',
                            'bar.txt' => 'bar'
                        )
                    ),
                    'baz.txt'
                ),
                'bar' => array(
                    'resources' => array(
                        'text' => array(
                            'fizz.txt' => 'fizz'
                        )
                    )
                ),
                'file.txt' => 'file'
            )
        ));

        $this->cacheClearer = new CacheClearer(vfsStream::url('root/cacheDir'));
    }

    public function testClearCache()
    {
        $this->assertFileExists(vfsStream::url('root/cacheDir/foo/resources/text/foo.txt'));

        $this->cacheClearer->clearCaches(['foo']);

        $this->assertFileExists(vfsStream::url('root/cacheDir/bar'));
        $this->assertFileExists(vfsStream::url('root/cacheDir/file.txt'));
        $this->assertFileNotExists(vfsStream::url('root/cacheDir/foo/resources/text/foo.txt'));

        $this->cacheClearer->clearCaches(['main']);

        $this->assertFileExists(vfsStream::url('root/cacheDir/bar'));
        $this->assertFileNotExists(vfsStream::url('root/cacheDir/file.txt'));
    }

    public function testClearAllCaches()
    {
        $this->cacheClearer->clearCaches();

        $this->assertFileNotExists(vfsStream::url('root/cacheDir/bar'));
        $this->assertFileNotExists(vfsStream::url('root/cacheDir/file.txt'));
        $this->assertFileNotExists(vfsStream::url('root/cacheDir/file.txt'));
    }
}
 