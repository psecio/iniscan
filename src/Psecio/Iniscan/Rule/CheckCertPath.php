<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the default ssl configuration for PHP >= 5.6.0
 * is valid
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
			$defaultCertFile = $this->findValue('openssl.cafile', $ini);
			$defaultCertDir = $this->findValue('openssl.capath', $ini);
			
			// Check for default if no custom values are set
			if (function_exists('openssl_get_cert_locations')) {
				$config = openssl_get_cert_locations();
				$defaultCertFile = $defaultCertFile ? $defaultCertFile : $config['default_cert_file'];
				$defaultCertDir = $defaultCertDir ? $defaultCertDir : $config['default_cert_dir'];
			}
			
			$isCertFileSet = (empty($defaultCertFile)) ? false : true;
			$isCertFileValid = false;
			if ($isCertFileSet) {
				$isCertFileValid = is_readable($defaultCertFile);
			}
			
			$isCertDirSet = (empty($defaultCertDir)) ? false : true;
			$isCertDirValid = false;
			if ($isCertDirSet) {
				$isCertDirValid = is_dir($defaultCertDir) && is_executable($defaultCertDir);
			}
			
			if ($isCertFileValid || $isCertDirValid) {
				return true;
			}
			
			// File is set but not valid
			if (!$isCertFileValid) {
				$this->setDescription('Default certificate specified `' . $defaultCertFile . '` does not exists. Please set with openssl.cafile parameter.');
				$this->fail();
				return false;
			}
			
			// Director is set but not valid
			if (!$isCertDirValid) {
				$this->setDescription('Default certificate directory specified `' . $defaultCertDir . '` not not exists or is not accessible. Please set with openssl.capath parameter.');
				$this->fail();
				return false;
			}
			
			// File is not set
			if (!$isCertFileSet) {
				$this->setDescription('No default certificate specified. Please set with openssl.cafile parameter.');
				$this->fail();
				return false;
			}
			
			// Directory is not set
			// Unreachable
			if (!$isCertDirSet) {
				$this->setDescription('No default certificate directory specified. Please set with openssl.capath parameter.');
				$this->fail();
				return false;
			}
		}

		return true;
	}
}
