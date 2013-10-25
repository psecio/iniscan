<?php

namespace Psecio\Iniscan;

abstract class Operation
{
	public abstract function execute($key, $value, $ini);

	public function findValue($path, $ini)
	{
		list($section, $path) = explode('|', $path);
		if (!array_key_exists($section, $ini)) {
			throw new \InvalidArgumentException('Unknown section '.$section);
		}
		if (!array_key_exists($path, $ini[$section])) {
			throw new \InvalidArgumentException('Unknown path '.$path);
		}
		return $ini[$section][$path];
	}
}