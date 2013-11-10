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
	 * Init the object with the given config and section
	 *
	 * @param array $config Configuration settings
	 * @param string $section Section name
	 */
	public function __construct($config, $section)
	{
		$this->setConfig($config);
		$this->setSection($section);
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
	 * @return string Section name
	 */
	public function getSection()
	{
		return $this->section;
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
	 * Set the pass/fail status for the rule
	 *
	 * @param boolean $flag Pass/fail status
	 */
	public function setStatus($flag)
	{
		if (!is_bool($flag)) {
			throw new \InvalidArgumentException('Value must be boolean!');
		}
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
			'status' => $this->status
		);
	}

	/**
	 * Evaluate the rule and its test
	 *
	 * @param array $ini Current php.ini configuration
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
		$evalInstance = new $evalClass($this->getSection());

		($evalInstance->execute($test->key, $value, $ini) == false)
			? $this->fail() : $this->pass();
	}
}