<?php

namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the system (cli) functions
 * 	are disabled.
 */
class DisableCliFunctions extends \Psecio\Iniscan\Rule
{
	/**
	 * Functions to disable
	 * @var array
	 */
	private $functions = array(
		'exec', 'passthru', 'shell_exec', 'system',
		'proc_open', 'popen', 'curl_exec', 'curl_multi_exec'
	);

	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'disable_functions'));
	}

	/**
	 * Convert the current check into a string
	 * 
	 * @return string INI formatted setting string
	 */
	public function __toString()
	{
		return 'disable_functions = '.implode(', ', $this->functions);
	}

	/**
	 * Evaluate the operation
	 *
	 * @param array $ini
	 * @return boolean Pass/fail result
	 */
	public function evaluate(array $ini)
	{
		if (isset($ini['PHP']['disable_functions'])) {

			$functions = explode(',', $ini['PHP']['disable_functions']);
			foreach ($functions as $function) {
				$search = array_search($function, $this->functions);
				if ($search !== false) {
					unset($this->functions[$search]);
				}
			}
		}
		if (!empty($this->functions)) {
			$this->setDescription('Methods still enabled - '.implode(', ', $this->functions));
			$this->fail();
			return false;
		} else {
			$this->pass();
			return true;
		}
	}
}