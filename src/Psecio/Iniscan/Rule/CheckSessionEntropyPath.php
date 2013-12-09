<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the maximum
 * 	post size is too large
 */
class CheckSessionEntropyPath extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'session.entropy_file'));
	}

	public function evaluate(array $ini)
	{
		$entropyFile = $this->findValue('session.entropy_file', $ini);

		// If the version is less than 5.4.0
		if (version_compare(PHP_VERSION, '5.4.0', '<') === true) {
			if ($entropyFile === '') {
				$this->fail();
				return false;
			}
		}
		$this->pass();
		return true;
	}
}