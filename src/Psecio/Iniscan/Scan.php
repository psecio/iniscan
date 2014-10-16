<?php

namespace Psecio\Iniscan;

class Scan
{
	/**
	 * Path to the php.ini file
	 * @var string
	 */
	private $path;

	/**
	 * Set of context environments to run in (ex. "prod" or "dev")
	 * @var array
	 */
	private $context = array();

	/**
	 * Set of ini keys marked as deprecated
	 * @var array
	 */
	private $marked = array();

    /**
	 * The threshold to use for the rules. Only use the rules that are on
	 * or above this threshold.
	 * @var string
	 */
	private $threshold;

	/**
	 * Current INI configuration settings
	 * @var array
	 */
	private $config = array();

	/**
	 * The version of PHP being tested for
	 * @var string
	 */
	private $version;

	/**
	 * Init the object with the given ini path
	 *
	 * @param string $path PHP.ini path to evaluate [optional]
	 * @param array $context Set of context environments to run in (ex. "prod" or "dev") [optional]
	 * @param string $threshold Show only things at or above this theshold
	 * @param string $version Which version of PHP to scan against
	 */
	public function __construct($path = null, array $context = array(), $threshold = null, $version = null)
	{
		if ($path !== null) {
			$this->setPath($path);
		}
		$this->setContext($context);
		$this->setThreshold($threshold);
		$this->setVersion($version);
	}

    /**
     * Set the ini path to evaluate
     *
     * @param string $path Path to php.ini
     * @throws \InvalidArgumentException
     */
	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new \InvalidArgumentException('Path '.$path.' invalid');
		}
		$this->path = realpath($path);
	}

	/**
	 * Get the current path value
	 *
	 * @return string Path location
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set the current context list
	 *
	 * @param array $context Context list
	 */
	public function setContext(array $context)
	{
		$this->context = $context;
	}

	/**
	 * Get the current context list
	 *
	 * @return array Context list
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * Set the threshold for rules that should be displayed
	 *
	 * @param string $threshold The threshold to use
	 */
	public function setThreshold($threshold) {
		$this->threshold = $threshold;
	}

	/**
	 * Returns the current threshold
	 *
	 * @return string The Threshold for rules
	 */
	public function getThreshold() {
		return $this->threshold;
	}

	/**
	 * Get the current "version" value
	 *
	 * @return string Version value
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Set the current "version" value
	 *
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * Get the settings from the rules.json related to
	 * 	the given index
	 *
	 * @return object|boolean False if configuration not found
	 */
	public function getSettings()
	{
		$rules = json_decode(file_get_contents(__DIR__.'/rules.json'));
		if ($rules === null) {
			throw new \Exception('Cannot parse rule configuration');
		}
		return $rules;
	}

	/**
	 * Get the current rules to evaluate
	 *
	 * @return array Set of rules
	 */
	public function getRules()
	{
		$settings = $this->getSettings();
		if ($settings === false || !isset($settings->rules)) {
			throw new \Exception('Rule configuration not found');
		}
		return $settings->rules;
	}

    /**
     * Get the current set of deprecated settings from the config
     *
     * @throws \Exception
     * @return array Set of deprecated settings
     */
	public function getDeprecated()
	{
		$settings = $this->getSettings();
		if ($settings === false || !isset($settings->deprecated)) {
			throw new \Exception('Deprecated configuration not found');
		}
		return $settings->deprecated;
	}

	/**
	 * Mark a found key as a deprecated item
	 *
	 * @param string $key PHP.ini key
	 */
	public function markKey($key)
	{
		$this->marked[] = $key;
	}

	/**
	 * Get the current set of keys marked as deprecated
	 *
	 * @return array
	 */
	public function getMarked()
	{
		return $this->marked;
	}

	/**
	 * Set the current configuration (INI values)
	 *
	 * @param array $config Set of INI configuration values
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Get current INI configuration
	 *
	 * @return array Set of INI configuration values
	 */
	public function getConfig()
	{
		return $this->config;
	}

    /**
     * See if a setting is listing as deprecated in the PHP version given
     *
     * @param string $key PHP.ini settings key
     * @param string $phpVersion Current PHP version [optional]
     * @return boolean Key is deprecated/not deprecated
     */
	public function isDeprecated($key, $phpVersion = PHP_VERSION)
	{
		$deprecated = $this->getDeprecated();
		$ini = $this->getConfig();

		// loop through the versions and see if our key is in there
		if (property_exists($deprecated, $key))
		{
			$compare = version_compare($phpVersion, $deprecated->$key);
			if ($compare >= 0 && isset($ini[$key])) {
				$this->markKey($key);
				return true;
			}
		}
		return false;
	}

	/**
	 * Parse the configuration (php.ini) file
	 *
	 * @param string $path Path to the string to parse
	 * @return array Ini settings
	 */
	public function parseConfig($path = null)
	{
		$ini = parse_ini_file( (!is_null($path) ? $path : $this->path));

		// pull in settings from other scanned INI files
		$scannedIniList = php_ini_scanned_files();
		if ($scannedIniList !== false) {
			foreach(explode(',', $scannedIniList) as $scannedFile) {
				$scannedIni = parse_ini_file(trim($scannedFile));
				$ini = array_merge($ini, $scannedIni);
			}
		}

		$this->setConfig($ini);
		return $ini;
	}

	/**
	 * Execute the scan
	 *
	 * @return array Set of post-evaluation rules (with pass/fail status)
	 */
	public function execute()
	{
		$path = $this->getPath();
		$ini = $this->parseConfig($path);
		$rules = $this->getRules();
		$version = $this->getVersion();

		$ruleList = array();
		foreach ($rules as $section => $ruleSet) {
			foreach ($ruleSet as $type => $rule) {
				if (is_string($rule->test)) {
					$ruleClass = "\\Psecio\\Iniscan\\Rule\\".$rule->test;
					$rule = new $ruleClass($rule, $section);
				} else {
					// make a rule
					$rule = new \Psecio\Iniscan\Rule($rule, $section);
				}
				$rule->setVersion($version);

				$key = $rule->getTestKey();
				if ($this->isDeprecated($key) === true) {
					continue;
				}

				if (!$rule->respectThreshold($this->threshold)) {
					continue;
				}

				// if we have contexts, check the rule
				$ruleContext = $rule->getContext();
				$scanContext = $this->getContext();

				if ($ruleContext !== null) {
					$int = array_intersect($ruleContext, $scanContext);
					if (empty($int) && !empty($scanContext)) {
						continue;
					}
				}

				// execute its test
				$rule->evaluate($ini);
				$ruleList[] = $rule;
			}
		}
		return $ruleList;
	}
}
