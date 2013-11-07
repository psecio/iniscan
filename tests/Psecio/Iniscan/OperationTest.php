<?php

namespace Psecio\Iniscan;

require_once 'OperationStub.php';

class OperationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the "casting" of a value to a consistent output
	 * given a string
	 * 
	 * @covers \Psecio\Iniscan\Operation::castValue
	 */
    public function testCastValueString()
    {
        $input = 'Off';
        $operation = new OperationStub('test');
        $result = $operation->castValue($input);

        $this->assertEquals($result, '0');
        $this->assertEquals(gettype($result), 'string');
    }

    /**
	 * Test the "casting" of a value to a consistent output
	 * given an integer
	 * 
	 * @covers \Psecio\Iniscan\Operation::castValue
	 */
    public function testCastValueInteger()
    {
    	$input = 1;
        $operation = new OperationStub('test');
        $result = $operation->castValue($input);

        $this->assertEquals($result, '1');
        $this->assertEquals(gettype($result), 'string');
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