<?php

namespace Psecio\Iniscan\Operation;

class OperationNotequalsTest extends \PHPUnit_Framework_TestCase
{
    private $operation;
    private $ini = array(
        'session.save_path' => '/tmp',
        'session.empty_path' => ''
    );

    public function setUp()
    {
        $this->operation = new OperationNotequals('Session');
    }

    /**
     * Test that the return is true when the path doesn't equal the value
     *
     * @covers \Psecio\Iniscan\Operation\OperationNotEquals::execute
     */
    public function testExecuteValueNotEqual()
    {
        $key = 'session.save_path';
        $value = 'other value';

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertTrue($result);
    }

    /**
     * Test that the return is false when the path is equal to the value
     *
     * @covers \Psecio\Iniscan\Operation\OperationNotEquals::execute
     */
    public function testExecuteNotValueIsEqual()
    {
        $key = 'session.save_path';
        $value = '/tmp';

        $result = $this->operation->execute($key, $value, $this->ini);
        $this->assertFalse($result);
    }
}