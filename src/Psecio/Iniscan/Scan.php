<?php

namespace Psecio\Iniscan;

class Scan
{
	private $path;
	private $rules = array(
		'session'
	);

	public function __construct($path)
	{
		$this->setPath($path);
	}

	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new \InvalidArgumentException('Path '.$path.' invalid');
		}
		$this->path = realpath($path);
	}
	public function getPath()
	{
		return $this->path;
	}

	public function getRules()
	{
		return $this->rules;
	}

	public function execute()
	{
		$path = $this->getPath();
		$ini = parse_ini_file($path, true);

		// pull in the rule configuration
		$rules = json_decode(file_get_contents(__DIR__.'/rules.json'));

		$ruleList = array();
		foreach ($rules->rules as $index => $ruleSet) {
			foreach ($ruleSet as $type => $rule) {
				// make a rule
				$rule = new \Psecio\Iniscan\Rule($rule);

				// execute its test
				$rule->evaluate($ini);
				$ruleList[] = $rule;
			}
		}
		return $ruleList;
	}
}