<?php

namespace Psecio\Iniscan\Operation;

class OperationNotequals extends \Psecio\Iniscan\Operation
{
	public function execute($key, $value, $ini)
	{
		$found = $this->findValue($key, $ini);
		if ($found == $value) {
			return false;
		}
		return true;
	}
}