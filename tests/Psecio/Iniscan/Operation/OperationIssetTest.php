<?php

namespace Psecio\Iniscan\Operation;

class OperationIssetTest extends \PHPUnit_Framework_TestCase
{
    private $operation;
    private $ini = array(
        'session.save_path' => '/tmp',
        'session.empty_path' => ''
    );

    public function setUp()
    {
        $this->operation = new OperationIsset('Session');
    }

    /**
     * Test that the return is true when the path is currently set
     *
     * @covers \Psecio\Iniscan\Operation\OperationIsset::execute
     */
    public function testExecuteValueSet()
    {
        $key = 'session.save_path';
        $value = null;

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertTrue($result);
    }

    /**
     * Test that the return is false when the path given is empty
     *
     * @covers \Psecio\Iniscan\Operation\OperationIsset::execute
     */
    public function testExecuteNotValueNotSet()
    {
        $key = 'session.empty_path';
        $value = null;

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertFalse($result);
    }
}