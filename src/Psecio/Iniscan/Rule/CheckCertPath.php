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
		if (function_exists('openssl_get_cert_locations')) {
			$config = openssl_get_cert_locations();
			
			$isCertFileSet = (empty($config['default_cert_file'])) ? false : true;
			$isCertFileValid = false;
			if ($isCertFileSet) {
				$isCertFileValid = is_readable($config['default_cert_file']);
			}
			
			$isCertDirSet = (empty($config['default_cert_dir'])) ? false : true;
			$isCertDirValid = false;
			if ($isCertDirSet) {
				$isCertDirValid = is_dir($config['default_cert_dir']) && is_executable($config['default_cert_dir']);
			}
			
			if ($isCertFileValid || $isCertDirValid) {
				return true;
			}
			
			// File is set but not valid
			if (!$isCertFileValid) {
				$this->setDescription('Default certificate specified `' . $config['default_cert_file'] . '` does not exists. Please set with openssl.cafile parameter.');
				$this->fail();
				return false;
			}
			
			// Director is set but not valid
			if (!$isCertDirValid) {
				$this->setDescription('Default certificate directory specified `' . $config['default_cert_dir'] . '` not not exists or is not accessible. Please set with openssl.capath parameter.');
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
