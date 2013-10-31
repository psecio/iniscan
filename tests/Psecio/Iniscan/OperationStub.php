<?php

namespace Psecio\Iniscan;

class OperationStub extends \Psecio\Iniscan\Operation
{
    public function execute($key, $value, $ini)
    {
        return true;
    }
}