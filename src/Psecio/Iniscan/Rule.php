<?php

namespace Psecio\Iniscan;

class Rule
{
	private $level;
	private $description;
	private $name;
	private $test;
	private $status = true;
	private $section;

	public function __construct($config, $section)
	{
		$this->setConfig($config);
		$this->setSection($section);
	}

	public function setConfig($config)
	{
		if (is_object($config)) {
			$config = get_object_vars($config);
		}
		foreach ($config as $index => $value) {
			$this->$index = $value;
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function setSection($section)
	{
		$this->section = $section;
	}
	public function getSection()
	{
		return $this->section;
	}

	public function getLevel()
	{
		return $this->level;
	}

	public function setStatus($flag)
	{
		if (!is_bool($flag)) {
			throw new \InvalidArgumentException('Value must be boolean!');
		}
		$this->status = $flag;
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function fail()
	{
		$this->setStatus(false);
	}
	public function pass()
	{
		$this->setStatus(true);
	}

	public function getTest()
	{
		return $this->test;
	}

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