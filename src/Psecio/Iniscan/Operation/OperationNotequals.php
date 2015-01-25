<?php

namespace Psecio\Iniscan\Operation;

class OperationNotequals extends \Psecio\Iniscan\Operation
{
	/**
	 * Execute the "not equals" operation
	 * 	If the value does equal the ini setting, return false
	 *
	 * @param string $key Key name of setting
	 * @param string $value Value to match on
	 * @param array $ini Current php.ini settings
	 * @return boolean Pass/fail of operation
	 */
	public function execute($key, $value, $ini)
	{
		$found = $this->findValue($key, $ini);
		if ($this->getCast()->castValue($found) == $this->getCast()->castValue($value)) {
			return false;
		}
		return true;
	}
}
