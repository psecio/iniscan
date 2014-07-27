<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the bug compatability for PHP between 4.3.0 and 5.4.0
 * are specifically disabled (default is enabled, so they must have an entry set to 0)
 *
 * http://www.php.net/manual/en/session.configuration.php#ini.session.bug-compat-42
 */
class CheckSessionHashFunction extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'session.hash_function'));
	}

	/**
	 * Perform the evaluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini)
	{
		$hashFunction = $this->findValue('session.hash_function', $ini);

		// Get a list of available hashing algorithms on this machine
		$availableHashes = array_unique(hash_algos());

		// Filter out the unwanted hashing algorithms
		// http://en.wikipedia.org/wiki/Category:Broken_hash_functions
		$brokenHashes = array(
			'md2',
			'md4',
			'md5',
			'sha1',
			'gost',
			'snefru'
		);

		$safeHashes = array_diff($availableHashes, $brokenHashes);

		if (empty($safeHashes)) {
			$this->setDescription('No strong hashing algorithms available.');
			$this->fail();
			return false;
		}

		if (!$hashFunction || $this->getCast()->castValue($hashFunction) === 1 || !in_array($hashFunction, $safeHashes)) {
			$this->setDescription('Weak hashing algorithms in use. Rather use one of these: ' . implode(', ', $safeHashes));
			$this->fail();
			return false;
		}

        $this->pass();
		return true;
	}
}
