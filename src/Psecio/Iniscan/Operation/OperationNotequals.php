<?php

namespace Psecio\Iniscan\Operation;

class OperationNotequals extends \Psecio\Iniscan\Operation
{
	/**
	 * Execute the "not equals" operation
	 * 	If the value does equal the ini setting, return false
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
		if ($this->castValue($found) == $this->castValue($value)) {
			return false;
		}
		return true;
	}
}