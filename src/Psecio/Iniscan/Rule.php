<?php

namespace Psecio\Iniscan;

class Rule
{
	/**
	 * Severity level of the rule
	 * @var string
	 */
	private $level;

	/**
	 * Description of rule
	 * @var string
	 */
	private $description;

	/**
	 * Name of the rule
	 * @var string
	 */
	private $name;

	/**
	 * Test to evaluate the rule
	 * @var object
	 */
	private $test;

	/**
	 * Pass/fail status of the rule
	 * @var boolean
	 */
	private $status = true;

	/**
	 * Section in the php.ini the rule's key matches
	 * @var string
	 */
	private $section;

	/**
	 * The version of PHP being tested for
	 * @var string
	 */
	private $version;

	/**
	 * The current value of the setting
	 * @var string
	 */
	private $value;

	/**
	 * Current Cast instance
	 * @var \Psecio\Iniscan\Cast
	 */
	private $cast;

	/**
	 * Init the object with the given config and section
	 *
	 * @param array $config Configuration settings
	 * @param string $section Section name
	 */
	public function __construct($config, $section)
	{
		$this->setConfig($config);
		$this->setSection($section);
		$this->setCast(new \Psecio\Iniscan\Cast());
	}

	/**
	 * Set the current Cast instance
	 *
	 * @param Psecio\Iniscan\Cast $cast Cast class instance
	 * @return \Psecio\Iniscan\Rule
	 */
	public function setCast(\Psecio\Iniscan\Cast $cast)
	{
		$this->cast = $cast;
		return $this;
	}

	/**
	 * Get the current Cast instance
	 *
	 * @return \Psecio\Iniscan\Cast
	 */
	public function getCast()
	{
		return $this->cast;
	}

	/**
	 * Set the configuration values to the class properties
	 *
	 * @param array $config Configuration values
	 */
	public function setConfig($config)
	{
		if (is_object($config)) {
			$config = get_object_vars($config);
		}
		foreach ($config as $index => $value) {
			$this->$index = $value;
		}
	}

	/**
	 * Get the current "name" value
	 *
	 * @return string Name value
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the current "name" value
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Set the section the rule belongs to
	 *
	 * @param string $section Section name
	 */
	public function setSection($section)
	{
		$this->section = $section;
	}

	/**
	 * Get the current section setting
	 *
	 * @param string $path INI "path" to setting [optional]
	 * @return string Section name
	 */
	public function getSection($path = null)
	{
		if ($path !== null) {
			$parts = explode('.', $path);
			return (count($parts) == 1) ? 'PHP' : $parts[0];
		} else {
			return $this->section;
		}
	}

	/**
	 * Get the severity level (ex. WARNING or ERROR)
	 *
	 * @return string Severity level
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * Get the current "version" value
	 *
	 * @return string Version value
	 */
	public function getVersion()
	{
		return $this->version ?: PHP_VERSION;
	}

	/**
	 * Set the current "version" value
	 *
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
	    return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
	    $this->value = $value;
	}

	/**
	 * Set the pass/fail status for the rule
	 *
	 * @param boolean $flag Pass/fail status
	 */
	public function setStatus($flag)
	{
		$this->status = $flag;
	}

	/**
	 * Get the current pass/fail status of the rule
	 *
	 * @return boolean Pass/fail status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Shorthand method to fail the test
	 */
	public function fail()
	{
		$this->setStatus(false);
	}

	/**
	 * Shorthand method to pass the test
	 */
	public function pass()
	{
		$this->setStatus(true);
	}

	/**
	 * Set the status for "not applicable" (null)
	 */
	public function na()
	{
		$this->setStatus(null);
	}

	/**
	 * Get the current rule's test definition
	 *
	 * @return array Testing config
	 */
	public function getTest()
	{
		return $this->test;
	}

    /**
     * Get the PHP.ini setting key from the test
     *
     * @throws \InvalidArgumentException
     * @return string Test key
     */
	public function getTestKey()
	{
		$test = $this->getTest();
		if (!isset($test->key)) {
			throw new \InvalidArgumentException('Test key not found');
		}
		return $test->key;
	}

	/**
	 * Set the test information for the rule
	 *
	 * @param mixed $test Either a test object or array
	 */
	public function setTest($test)
	{
		if (is_array($test)) {
			$test = (object)$test;
		}
		$this->test = $test;
	}

	/**
	 * Get the test context list
	 *
	 * @return mixed Either the array of context or null if not found
	 */
	public function getContext()
	{
		return (isset($this->test->context))
			? $this->test->context : null;
	}

	/**
	 * Set the description for the rule
	 *
	 * @param string $description Rule description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Get the current rule's description
	 *
	 * @return string Rule description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Output the values from the current rule as an array
	 *
	 * @return array
	 */
	public function values()
	{
		return array(
			'name' => $this->name,
			'description' => $this->description,
			'level' => $this->level,
			'status' => $this->status,
			'currentValue' => $this->value,
		);
	}

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

    /**
     * Evaluate the rule and its test
     *
     * @param array $ini Current php.ini configuration
     * @throws \InvalidArgumentException
     * @return null
     */
	public function evaluate(array $ini)
	{
		$test = $this->getTest();
		$evalClass = "\\Psecio\\Iniscan\\Operation\\Operation".ucwords(strtolower($test->operation));

		if (!class_exists($evalClass)) {
			throw new \InvalidArgumentException('Invalid operation "'.$test->operation.'"');
		}
		$value = (isset($test->value)) ? $test->value : null;
		$this->setValue($value);

		$evalInstance = new $evalClass($this->getSection());

		if (isset($test->version) && !$this->isVersion($test->version)) {
			$this->na();
		} else {
			($evalInstance->execute($test->key, $value, $ini) === false)
				? $this->fail() : $this->pass();
		}
	}

	/**
	 * Checks to see if the current version is above or the same as the one given
	 *
	 * @param  string $phpVersion PHP version string
	 * @return boolean Valid/invalid match
	 */
	public function isVersion($phpVersion)
	{
		$compare = version_compare($this->getVersion(), $phpVersion);
		return ($compare === 1 || $compare === 0) ? true : false;
	}

    /**
     * Check that the rule matches the wanted security level
     *
     * @param string $wantedLevel The minimum level to display
     * @return bool
     */
	public function respectThreshold($wantedLevel) {
		// If not threshold is given, always display the rule
		if (is_null($wantedLevel)) {
			return true;
		}

		$currentValue = $this->getLevelNumericalValue($this->level);
		$wantedValue = $this->getLevelNumericalValue($wantedLevel);

		return  $currentValue >= $wantedValue;
	}

	/**
	 * Return a numerical value for the level
	 *
	 * @param string $level The level to convert to a number
	 * @return int A numerical value representing the level
	 */
	public function getLevelNumericalValue($level) {
		$level = strtolower($level);
		if (isset($this->levelValues[$level])) {
			return $this->levelValues[$level];
		}

		return 0;
	}

	/**
	 * The levels and their numerical values
	 */
	protected $levelValues = array(
		'info' => 5,
		'warning' => 10,
		'error' => 20,
		'fatal' => 30,
	);
}
