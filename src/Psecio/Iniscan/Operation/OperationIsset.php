<?php

namespace Psecio\Iniscan\Operation;

class OperationIsset extends \Psecio\Iniscan\Operation
{
	public function execute($key, $value, $ini)
	{
		$found = $this->findValue($key, $ini);
		if (empty($found)) {
			return false;
		}
		return true;
	}
}