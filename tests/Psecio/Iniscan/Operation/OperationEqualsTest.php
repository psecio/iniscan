<?php

namespace Psecio\Iniscan\Operation;

class OperationEqualsTest extends \PHPUnit_Framework_TestCase
{
    private $operation;
    private $ini = array(
        'session.save_path' => '/tmp'
    );

    public function setUp()
    {
        $this->operation = new OperationEquals('Session');
    }

    /**
     * Test that the return is true when the values do match
     *
     * @covers \Psecio\Iniscan\Operation\OperationEquals::execute
     */
    public function testExecuteEquals()
    {
        $key = 'session.save_path';
        $value = '/tmp';

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertTrue($result);
    }

    /**
     * Test that the return is false when the values don't match
     *
     * @covers \Psecio\Iniscan\Operation\OperationEquals::execute
     */
    public function testExecuteNotEquals()
    {
        $key = 'session.save_path';
        $value = '/foobar';

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertFalse($result);
    }
}