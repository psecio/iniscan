<?php

namespace Psecio\Iniscan;

class ScanTest extends \PHPUnit_Framework_TestCase
{
    private $scan;

    public function setUp()
    {
        $this->scan = new Scan();
    }
    public function tearDown()
    {
        unset($this->scan);
    }

    /**
     * Test that an exception is thrown when a bad file path
     *     is given
     *
     * @expectedException \InvalidArgumentException
     */
    public function testBadPath()
    {
        $this->scan->setPath('foobar');
    }

    /**
     * Test the getter/setter for context data
     */
    public function testGetSetContext()
    {
        $context = array('foo' => 'test');
        $this->scan->setContext($context);

        $this->assertEquals(
            $context,
            $this->scan->getContext()
        );
    }

    /**
     * Test the getter/setter for threshold value
     */
    public function testGetSetThreshold()
    {
        $threshold = 1234;
        $this->scan->setThreshold($threshold);

        $this->assertEquals(
            $threshold,
            $this->scan->getThreshold()
        );
    }

    /**
     * Test the getter/setter for version information
     */
    public function testGetSetVersion()
    {
        $version = 1;
        $this->scan->setVersion($version);

        $this->assertEquals(
            $version,
            $this->scan->getVersion()
        );
    }

    /**
     * Test the adding of a "marked" key name
     */
    public function testAddMarkedKey()
    {
        $this->scan->markKey('test');
        $this->assertTrue(in_array(
            'test',
            $this->scan->getMarked()
        ));
    }

    /**
     * Test the getter/setter for the configuration data
     */
    public function testGetSetConfig()
    {
        $config = array('foo' => 'bar');
        $this->scan->setConfig($config);

        $this->assertEquals(
            $config,
            $this->scan->getConfig()
        );
    }
}