<?php

namespace JLaso\SimpleMemoryDb\Tests;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    protected $tmpFile;

    protected function setUp()
    {
        $this->tmpFile = __DIR__.'/tmp.json';
        $this->cleanTmp();
    }

    protected function tearDown()
    {
        $this->cleanTmp();
    }

    protected function cleanTmp()
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
    }
}
