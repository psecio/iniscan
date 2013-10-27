<?php

namespace Psecio\Iniscan\Rule;

class DisableCliFunctions extends \Psecio\Iniscan\Rule
{
	private $functions = array(
		'exec', 'passthru', 'shell_exec', 'system', 
		'proc_open', 'popen', 'curl_exec', 'curl_multi_exec'
	);

	public function evaluate($ini)
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
		} else {
			$this->pass();
		}
	}
}