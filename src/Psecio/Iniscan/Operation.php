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
	 * Current Cast instance
	 * @var \Psecio\Iniscan\Cast
	 */
	private $cast;

	/**
	 * Init the Operation and set the section
	 *
	 * @param string $section Section name
	 */
	public function __construct($section)
	{
		$this->setSection($section);
		$this->setCast(new \Psecio\Iniscan\Cast());
	}

	/**
	 * Set the current Cast instance
	 *
	 * @param \Psecio\Iniscan\Cast $cast Cast object instance
	 * @return \Psecio\Iniscan\Operation instance
	 */
	public function setCast(\Psecio\Iniscan\Cast $cast)
	{
		$this->cast = $cast;
		return $this;
	}

	/**
	 * Get the current Cast instance
	 *
	 * @return \Psecio\Iniscan\Cast instance
	 */
	public function getCast()
	{
		return $this->cast;
	}

	/**
	 * Set the section name
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
	 * @param string $path INI "path" for settings
	 * @return string
	 */
	public function getSection($path)
	{
		$parts = explode('.', $path);
		return (count($parts) === 1)
			? 'PHP' : ucwords(strtolower($parts[0]));
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
	 *   If not found, returns the currently set value
	 *
	 * @param string $path "Path" to the value
	 * @param array $ini Current INI settings
	 * @return string Found INI value
	 */
	public function findValue($path, &$ini)
	{
		$value = false;

		if (array_key_exists($path, $ini)) {
			$value = $ini[$path];
		} else {
			// not in the file, pull out the default
			$value = ini_get($path);
			$ini[$path] = $value;
		}

		return $value;
	}
}
