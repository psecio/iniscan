<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if a session entropy file is provided
 *
 * http://php.net/manual/en/session.configuration.php#ini.session.entropy-file
 * As of PHP 5.4.0 session.entropy_file defaults to /dev/urandom or /dev/arandom if it is available.
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
		// If the version is less than 5.4.0
		if (version_compare($this->getVersion(), '5.4.0', '<') === true) {
			if (empty($ini['Session']['session.entropy_file'])) {
				$this->fail();
				return false;
			}
		}
		$this->pass();
		return true;
	}
}