<?php

namespace Psecio\Iniscan\Operation;

class OperationEquals extends \Psecio\Iniscan\Operation
{
	/**
	 * Execute the "equals" operation
	 * 	If the value and the ini setting don't equal, returns false
	 *
	 * @param string $test Test information
	 * @param string $value Value to match on
	 * @param array $ini Current php.ini settings
	 * @return boolean Pass/fail of operation
	 */
	public function execute($test, $value, $ini)
	{
		$key = $test->key;
		$found = $this->findValue($key, $ini);
		if ($this->castValue($found) !== $this->castValue($value)) {
			return false;
		}
		return true;
	}
}