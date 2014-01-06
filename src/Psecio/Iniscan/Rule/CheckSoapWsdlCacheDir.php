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
	public function evaluate(array $ini)
	{
		$openBasedir = $this->castValue($this->findValue('open_basedir', $ini));

		if ($openBasedir === 0) {
			return true;
		} else {
			$openBasedir = realpath($openBasedir);
		}

		$wsdlCacheDir = realpath($this->findValue('soap.wsdl_cache_dir', $ini));
		$wsdlCacheEnabled = $this->castValue($this->findValue('soap.wsdl_cache_enabled', $ini));

		// We only care about PHP before 5.3.22 and 5.4.x before 5.4.13
		if (version_compare($this->getVersion(), '5.3.22', '>=') || version_compare($this->getVersion(), '5.4.13', '>='))
		{
			return true;
		}

		// This only matters is an open_basedir is set and soap.wsdl_cache_enabled is enabled
		if (empty($openBasedir) || $wsdlCacheEnabled === 0)
		{
			return true;
		}

		// Make sure the folders are still valid
		if ($openBasedir === false)
		{
			$this->setDescription('The open_basedir did not resolve to a valid directory');
			$this->fail();
			return false;
		}
		if ($wsdlCacheDir === false)
		{
			$this->setDescription('The SOAP WSDL cache directory did not resolve to a valid directory');
			$this->fail();
			return false;
		}

		// Ensure that the WSDL cache directory is inside the base directory
		if (strpos($wsdlCacheDir, $openBasedir) !== 0)
		{
			$this->setDescription('soap.wsdl_cache_dir is not inside of open_basedir which allows the creation of cached SOAP WSDL files in an arbitrary directory');
			$this->fail();
			return false;
		}

		return true;
	}
}
