<?php

namespace Psecio\Iniscan\Operation;

class OperationSmaller extends \Psecio\Iniscan\Operation
{
	/**
	 * Execute the "smaller than" operation
	 * 	If the value and the ini setting isn't smaller than the value
	 *
	 * @param string $key Key name of setting
	 * @param string $value Value to match on
	 * @param array $ini Current php.ini settings
	 * @return boolean Pass/fail of operation
	 */
	public function execute($key, $value, $ini)
	{
		$found = $this->findValue($key, $ini);
		if ($this->castValue($value) < $this->castValue($found)) {
			return false;
		}
		return true;
	}
}