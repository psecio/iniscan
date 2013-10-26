<?php

namespace Psecio\Iniscan;

abstract class Operation
{
	public $section;

	public function __construct($section)
	{
		$this->setSection($section);
	}

	public function setSection($section)
	{
		$this->section = $section;
	}
	public function getSection()
	{
		return $this->section;
	}

	public abstract function execute($key, $value, $ini);

	public function findValue($path, $ini)
	{
		$section = $this->getSection();
		if (!array_key_exists($section, $ini)) {
			throw new \InvalidArgumentException('Unknown section '.$section);
		}
		if (!array_key_exists($path, $ini[$section])) {
			throw new \InvalidArgumentException('Unknown path '.$path);
		}
		return $ini[$section][$path];
	}
}