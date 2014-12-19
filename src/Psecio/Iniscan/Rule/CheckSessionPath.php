<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the session path
 * 	is world writable
 */
class CheckSessionPath extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'session.save_path'));
	}

	/**
	 * Perform the evaluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini)
	{
		// See which type we're working with
		$handler = $this->findValue('session.save_handler', $ini);
		switch(strtolower($handler)) {
			case 'file':
			case 'files':
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
		$savePath = $this->findValue('session.save_path', $ini);

		if ($savePath === '/tmp') {
			$this->setDescription('Custom path not set, default (/tmp) is world writeable');
			$this->fail();
			return false;
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$this->na();
			$this->setDescription('Cannot check Windows permissions. Please verify them manually');
			return true;
		}

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
