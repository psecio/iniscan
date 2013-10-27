<?php

namespace Psecio\Iniscan;

abstract class Operation
{
	/**
	 * Section of the ini file
	 * @var string
	 */
	public $section;

	/**
	 * Init the Operation and set the section
	 * 
	 * @param string $section Section name
	 * @return type
	 */
	public function __construct($section)
	{
		$this->setSection($section);
	}

	/**
	 * Set the setion name
	 * 
	 * @param string $section 
	 * @return \Psecio\Iniscan\Operation instance
	 */
	public function setSection($section)
	{
		$this->section = $section;
		return $this;
	}

	/**
	 * Get the current section name
	 * 
	 * @return string
	 */
	public function getSection()
	{
		return $this->section;
	}

	/**
	 * Execute the operation
	 * 
	 * @param string $key INI key name
	 * @param string $value Value to match
	 * @param array $ini Current INI settings
	 * @return boolean Pass/fail
	 */
	public abstract function execute($key, $value, $ini);

	/**
	 * Find the given value in the INI array
	 * 
	 * @param string $path "Path" to the value
	 * @param array $ini Current INI settings
	 * @throws \InvalidArgumentException If the section is unknown
	 * @throws \InvalidArgumentException If the path is not found
	 * @return string Found INI value
	 */
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