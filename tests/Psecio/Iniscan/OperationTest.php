<?php

namespace Psecio\Iniscan;

require_once 'OperationStub.php';

class OperationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test the "casting of a value to a consistent output.
     *
	 * @covers \Psecio\Iniscan\Operation::castValue
     * @dataProvider castDataProvider
     */
    public function testCastValue($input, $expectedValue, $expectedType)
    {
        $operation = new OperationStub('test');
        $result = $operation->castValue($input);

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
     * Test the locating of a value in the given INI settings
     * @covers \Psecio\Iniscan\Operation::findValue
     */
    public function testFndValue()
    {
    	$value = 'baz';
    	$ini = array(
    		'PHP' => array(
    			'foo.bar' => $value
    		)
    	);
    	$operation = new OperationStub('PHP');
    	$result = $operation->findValue('foo.bar', $ini);
    	$this->assertEquals($result, $value);
    }
}
