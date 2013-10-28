<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the session path
 * 	is world writeable
 */
class CheckSessionPath extends \Psecio\Iniscan\Rule
{
	public function evaluate($ini)
	{
		if (!isset($ini['Session']['session.save_path'])) {
			$this->setDescription('Path not set, default (/tmp) is world writeable');
			$this->fail();
			return false;
		}
		$savePath = $ini['Session']['session.save_path'];
		$perms = substr(sprintf('%o', fileperms($savePath)), - 3);
		if ($perms == 777) {
			$this->fail();
			$this->setDescription('Path '.$savePath.' is world writeable');
		} else {
			$this->pass();
		}
	}
}
