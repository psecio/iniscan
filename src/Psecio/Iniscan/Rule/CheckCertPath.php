<?php

namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the custom ssl configuration for PHP >= 5.6.0
 * is valid. If not valid, just warn since the system will fallback to a sane default
 */
class CheckCertPath extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'openssl.cafile'));
	}

	/**
	 * Perform the evaluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini)
	{
		if (version_compare($this->getVersion(), '5.6', '>='))
		{
			$customCertFile = $this->findValue('openssl.cafile', $ini);
			$customCertDir = $this->findValue('openssl.capath', $ini);
			
			// Is a custom certificate file set?
			$isCertFileSet = (empty($customCertFile)) ? false : true;
			$isCertFileValid = true;
			if ($isCertFileSet) {
				$isCertFileValid = is_readable($customCertFile);
			}

			// Is a custom cert directory set
			$isCertDirSet = (empty($customCertDir)) ? false : true;
			$isCertDirValid = true;
			if ($isCertDirSet) {
				$isCertDirValid = is_dir($customCertDir) && is_executable($customCertDir);
			}

			// File is set but not valid
			if (!$isCertFileValid) {
				$this->setDescription('Custom certificate specified `' . $customCertFile . '` does not exists. Please correct your openssl.cafile parameter or remove it to use a sane default.');
				$this->fail();
				return false;
			}
			
			// Directory is set but not valid
			if (!$isCertDirValid) {
				$this->setDescription('Custom certificate directory specified `' . $customCertDir . '` not not exists or is not accessible. Please correct your openssl.capath parameter or remove it to use a sane default.');
				$this->fail();
				return false;
			}

			$this->setDescription('Sane defaults will be used.');
			$this->pass();
			return true;
		}

		$this->setDescription('Upgrade to PHP 5.6+ for better peer verification or ensure that all external calls perform peer verification manually. Read http://bit.ly/1rMWFCx for more information.');
		$this->fail();
		return false;
	}
}
