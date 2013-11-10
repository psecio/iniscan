<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the maximum
 * 	post size is too large
 */
class MaximumPostSize extends \Psecio\Iniscan\Rule
{
	private $maxPost = 8;

	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'post_max_size'));
	}

	public function evaluate(array $ini)
	{
		$postSize = $ini['PHP']['post_max_size'];

		// find the number(s)
		preg_match('/[0-9]+/', $postSize, $match);
		if (isset($match[0]) && $match[0] > $this->maxPost) {
			$this->setDescription('Unless necessary, a maximum post size of '.$postSize.' is too large');
			$this->fail();
			return false;
		} else {
			$this->pass();
			return true;
		}
	}
}