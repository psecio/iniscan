<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the session path
 * 	is world writeable
 */
class CheckSessionPath extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'session.save_path'));
	}

	/**
	 * Perform the evluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini)
	{
		// See which type we're working with
		switch(strtolower($ini['Session']['session.save_handler'])) {
			case 'file':
				return $this->evaluateFile($ini);
				break;
			case 'memcache':
				return $this->evaluateMemcache($ini);
				break;
		}
		return false;
	}

	/**
	 * Evaluate the session handling for file-based systems
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	protected function evaluateFile(array $ini)
	{
		if (!isset($ini['Session']['session.save_path'])) {
			$this->setDescription('Path not set, default (/tmp) is world writeable');
			$this->fail();
			return false;
		}
		$savePath = $ini['Session']['session.save_path'];
		$perms = substr(sprintf('%o', fileperms($savePath)), - 3);
		if ($perms == 777) {
			$this->fail();
			$this->setDescription('Path '.$savePath.' is world writeable');
			return false;
		} else {
			$this->pass();
			return true;
		}
	}

	/**
	 * Check the memcache session storage settings
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation;
	 */
	protected function evaluateMemcache(array $ini)
	{
		return true;
	}
}
