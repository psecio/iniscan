<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operations:
 *
 * Checks to see if the upload_tmp_dir is inside open_basedir (if it has been specified)
 *
 */
class CheckUploadTmpDir extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'upload_tmp_dir'));
	}

	/**
	 * Perform the evluation of the rule
	 *
	 * @param array $ini Configuration settings (from php.ini)
	 * @return boolean Pass/fail of evaluation
	 */
	public function evaluate(array $ini)
	{
		$openBasedir = $this->getCast()->castValue($this->findValue('open_basedir', $ini));

		// This only matters if an open_basedir is set
		if ($openBasedir === 0) {
			return true;
		} else {
			$openBasedir = realpath($openBasedir);
		}

		$uploadTmpDir = $this->getCast()->castValue($this->findValue('upload_tmp_dir', $ini));

		// If we have no upload_tmp_dir, get the system default
		if ($uploadTmpDir === 0) {
			$uploadTmpDir = realpath(sys_get_temp_dir());
		} else {
			$uploadTmpDir = realpath($uploadTmpDir);
		}

		// Make sure the folders are still valid
		if ($openBasedir === false) {
			$this->setDescription('The open_basedir did not resolve to a valid directory');
			$this->fail();
			return false;
		}
		if ($uploadTmpDir === false) {
			$this->setDescription('The upload_tmp_dir did not resolve to a valid directory');
			$this->fail();
			return false;
		}

		// Ensure that the upload_tmp_dir is inside the base directory
		if (strpos($uploadTmpDir, $openBasedir) !== 0) {
			$this->setDescription('upload_tmp_dir is not inside of open_basedir which will prevent files from being uploaded');
			$this->fail();
			return false;
		}

		return true;
	}
}
