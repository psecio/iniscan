<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operations:
 *
 * Checks to see if an open_basedir has been specified
 *
 * Checks to see if the SOAP WSDL cache directory is inside open_basedir for
 * PHP before 5.3.22 and 5.4.x before 5.4.13
 *
 * https://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2013-1635
 */
class CheckSoapWsdlCacheDir extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'soap.wsdl_cache_dir'));
	}

	/**
	 * Perform the evluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini, $phpVersion = PHP_VERSION)
	{
		// We only care about PHP before 5.3.22 and 5.4.x before 5.4.13
		if (version_compare($this->getVersion(), '5.3.22', '>=') || version_compare($this->getVersion(), '5.4.13', '>='))
		{
			return true;
		}

		// This only matters is an open_basedir is set and soap.wsdl_cache_enabled is enabled
		if (empty($ini['PHP']['open_basedir']) || empty($ini['soap']['soap.wsdl_cache_enabled']))
		{
			return true;
		}

		// Resolve paths to deal with symbolic links
		$open_basedir = realpath($ini['PHP']['open_basedir']);
		$wsdl_cache_dir = realpath($ini['soap']['soap.wsdl_cache_dir']);

		// Make sure the folders are still valid
		if (!$open_basedir)
		{
			$this->setDescription('The open_basedir did not resolve to a valid directory');
			$this->fail();
			return false;
		}
		if (!$wsdl_cache_dir)
		{
			$this->setDescription('The SOAP WSDL cache directory did not resolve to a valid directory');
			$this->fail();
			return false;
		}

		// Ensure that the WSDL cache directory is inside the base directory
		if (strpos($wsdl_cache_dir, $open_basedir) !== 0)
		{
			$this->setDescription('soap.wsdl_cache_dir is not inside of open_basedir which allows the creation of cached SOAP WSDL files in an arbitrary directory');
			$this->fail();
			return false;
		}

		return true;
	}
}
