<?php

namespace Psecio\Iniscan;

class Scan
{
	/**
	 * Path to the php.ini file
	 * @var string
	 */
	private $path;

	/**
	 * Init the object with the given ini path
	 *
	 * @param string $path PHP.ini path to evaluate [optional]
	 */
	public function __construct($path = null)
	{
		if ($path !== null) {
			$this->setPath($path);
		}
	}

	/**
	 * Set the ini path to evaluate
	 *
	 * @param string $path Path to php.ini
	 */
	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new \InvalidArgumentException('Path '.$path.' invalid');
		}
		$this->path = realpath($path);
	}

	/**
	 * Get the current path value
	 *
	 * @return string Path location
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Get the current rules to evaluate
	 *
	 * @return array Set of rules
	 */
	public function getRules()
	{
		$rules = json_decode(file_get_contents(__DIR__.'/rules.json'));

		if ($rules === null) {
			throw new \Exception('Cannot parse rule configuration');
		}
		if (!isset($rules->settings[0]->rules)) {
			throw new \Exception('Rule configuration not found');
		}
		return $rules->settings[0]->rules;
	}

	/**
	 * Parse the configuration (php.ini) file
	 *
	 * @param string $path Path to the string to parse
	 * @return array Ini settings
	 */
	public function parseConfig($path = null)
	{
		$ini = parse_ini_file( (!is_null($path) ? $path : $this->path), true);
		return $ini;
	}

	/**
	 * Execute the scan
	 *
	 * @return array Set of post-evaluation rules (with pass/fail status)
	 */
	public function execute()
	{
		$path = $this->getPath();
		$ini = $this->parseConfig($path);
		$rules = $this->getRules();

		$ruleList = array();
		foreach ($rules as $index => $ruleSet) {
			foreach ($ruleSet as $type => $rule) {
				if (is_string($rule->test)) {
					$ruleClass = "\\Psecio\\Iniscan\\Rule\\".$rule->test;
					$rule = new $ruleClass($rule, $index);
				} else {
					// make a rule
					$rule = new \Psecio\Iniscan\Rule($rule, $index);
				}

				// execute its test
				$rule->evaluate($ini);
				$ruleList[] = $rule;
			}
		}
		return $ruleList;
	}
}
