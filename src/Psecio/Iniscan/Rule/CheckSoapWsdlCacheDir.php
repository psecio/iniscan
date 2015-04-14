<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operations:
 *
 * Checks to see if the SOAP WSDL cache directory is inside open_basedir for
 * PHP before 5.3.22 and 5.4.x before 5.4.13
 * https://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2013-1635
 *
 * Checks to see if the SOAP WSDL cache directory is empty or set to /tmp
 * https://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2013-6501
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
		$wsdlCacheEnabled = $this->getCast()->castValue($this->findValue('soap.wsdl_cache_enabled', $ini));

		// This only matters if soap.wsdl_cache_enabled is enabled
		if ($wsdlCacheEnabled === 0)
		{
			return true;
		}

		$wsdlCacheDir = $this->findValue('soap.wsdl_cache_dir', $ini);
		if ($wsdlCacheDir !== 0 && $wsdlCacheDir)
		{
			$wsdlCacheDir = realpath($wsdlCacheDir);
		}

		// For CVE-2013-6501 we only care about PHP before 5.6.8
		if (!$wsdlCacheDir && version_compare($this->getVersion(), '5.6.7', '<='))
		{
			$this->setDescription('The SOAP WSDL cache directory is empty which means the default "/tmp/" is used which allows local users to conduct WSDL injection attacks (CVE-2013-6501)');
			$this->fail();
			return false;
		}

		if (strpos($wsdlCacheDir, '/tmp') !== false)
		{
			$this->setDescription('The SOAP WSDL cache directory is inside of "/tmp/" which allows local users to conduct WSDL injection attacks (CVE-2013-6501)');
			$this->fail();
			return false;
		}

		$openBasedir = $this->getCast()->castValue($this->findValue('open_basedir', $ini));
		if ($openBasedir !== 0 && $wsdlCacheDir)
		{
			$openBasedir = realpath($openBasedir);
		}

		// Make sure the folders are valid
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

		// For CVE-2013-1635 we only care about PHP before 5.3.22 and 5.4.x before 5.4.13 and if open_basedir is set
		if ($openBasedir === 0 || version_compare($this->getVersion(), '5.3.22', '>=') || version_compare($this->getVersion(), '5.4.13', '>='))
		{
			return true;
		}

		// Ensure that the WSDL cache directory is inside the base directory
		if (strpos($wsdlCacheDir, $openBasedir) !== 0)
		{
			$this->setDescription('soap.wsdl_cache_dir is not inside of open_basedir which allows the creation of cached SOAP WSDL files in an arbitrary directory (CVE-2013-1635)');
			$this->fail();
			return false;
		}

		return true;
	}
}
