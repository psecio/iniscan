<?php

namespace Psecio\Iniscan;

require_once 'OperationStub.php';

class OperationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test the "casting of a value to a consistent output.
     *
	 * @covers \Psecio\Iniscan\Cast::castValue
     * @dataProvider castDataProvider
     */
    public function testCastValue($input, $expectedValue, $expectedType)
    {
        $operation = new OperationStub('test');
        $result = $operation->getCast()->castValue($input);

        $this->assertEquals($result, $expectedValue);
        $this->assertEquals(gettype($result), $expectedType);
    }

    /**
     * Data provider for the cast tests
     */
    public function castDataProvider()
    {
        return array(
            array('Off', 0, 'integer'),
            array('On', 1, 'integer'),
            array(0, 0, 'integer'),
            array(1, 1, 'integer'),
            array('0', 0, 'integer'),
            array('1', 1, 'integer'),
        );
    }

    /**
     * Data provider for the cast "powers" test
     */
    public function powersDataProvider()
    {
        return array(
            array('1K', 1024),
            array('1M', (1024*1024*1)),
            array('1G', (1024*1024*1024*1))
        );
    }

    /**
     * Test the locating of a value in the given INI settings
     * @covers \Psecio\Iniscan\Operation::findValue
     */
    public function testFindValue()
    {
    	$value = 'baz';
    	$ini = array(
            'bar' => $value
    	);
        $operation = new OperationStub('PHP');
        $result = $operation->findValue('bar', $ini);
    	$this->assertEquals($result, $value);
    }

    /**
     * Test that false is returned when a key is not found
     *
     * @covers \Psecio\Iniscan\Operation::findValue
     */
    public function testKeyNotFound()
    {
        $ini = array();
        $operation = new OperationStub('PHP');
        $result = $operation->findValue('foo.bar', $ini);
        $this->assertFalse($result);
    }

    /**
     * Test that false is returned when the section isn't found
     *   (and doesn't exist in the default PHP config)
     *
     * @covers \Psecio\Iniscan\Operation::findValue
     */
    public function testSectionNotFound()
    {
        $ini = array(
            'PHP' => array()
        );
        $operation = new OperationStub('BAR');
        $this->assertFalse(
            $operation->findValue('foo.bar', $ini)
        );
    }

    /**
     * Test the "casting" of the size measurements (ex. MB, G, etc)
     *
     * @covers \Psecio\Iniscan\Cast::castPowers
     * @dataProvider powersDataProvider
     */
    public function testCastPowers($input, $expectedValue)
    {
        $operation = new OperationStub('test');
        $result = $operation->getCast()->castPowers($input);

        $this->assertEquals($result, $expectedValue);
    }

    /**
     * Test the getter/setter for casting
     */
    public function testGetSetCast()
    {
        $cast = new \Psecio\Iniscan\Cast();
        $operation = new OperationStub('test');
        $operation->setCast($cast);

        $this->assertEquals(
            $operation->getCast(),
            $cast
        );
    }

    /**
     * Test the getting of a section returns "PHP"
     *     as it assumes it's the top level
     */
    public function testGetSectionSingle()
    {
        $path = 'testing';
        $operation = new OperationStub('test');

        $this->assertEquals(
            $operation->getSection($path),
            'PHP'
        );
    }

    /**
     * Get the section when a path is given in a section
     *     ex. "session.save_path"
     */
    public function testGetSectionMultiple()
    {
        $path = 'session.save_path';
        $operation = new OperationStub('test');

        $this->assertEquals(
            $operation->getSection($path),
            'Session'
        );
    }
}
