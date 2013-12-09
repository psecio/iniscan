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
	 */
	public function __construct($section)
	{
		$this->setSection($section);
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
		$section = $this->getSection($path);

		if (array_key_exists($section, $ini)) {
			if (array_key_exists($path, $ini[$section])) {
				$value = $ini[$section][$path];
			}
		} else {
			// not in the file, pull out the default
			$value = ini_get($path);
			$ini[$section][$path] = $value;
		}

		return $value;
	}

	/**
	 * Cast the values from php.ini to a standard format
	 *
	 * @param mixed $value php.ini setting value
	 * @return mixed "Casted" result
	 */
	public function castValue($value)
	{
		if ($value === 'Off' || $value === '' || $value === 0 || $value === '0') {
			$casted = 0;
		} elseif ($value === 'On' || $value === '1' || $value === 1) {
			$casted = 1;
		} else {
			$casted = $value;
		}

		$casted = $this->castPowers($casted);

		return $casted;
	}

    /**
     * Cast the byte values ending with G, M or K to full integer values
     *
     * @param $casted
     * @internal param $value
     * @return mixed "Casted" result
     */
	public function castPowers ($casted) {
		$postfixes = array(
			'K' => 1024,
			'M' => 1024 * 1024,
			'G' => 1024 * 1024 * 1024,
		);
		$matches = array();
		if (preg_match('/^([0-9]+)([' . implode('', array_keys($postfixes)) . '])$/', $casted, $matches)) {
			$casted = $matches[1] * $postfixes[$matches[2]];
		}
		return $casted;
	}
}
