<?php

namespace Psecio\Iniscan;

require_once 'OperationStub.php';

class OperationTest extends \PHPUnit_Framework_TestCase
{
    public function testCastValue()
    {
        $input = 'Off';
        $operation = new OperationStub('test');

        $result = $operation->castValue($input);
        $this->assertEquals($result, '0');
    }
}