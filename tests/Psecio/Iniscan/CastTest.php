<?php

namespace Psecio\Iniscan;

class CastTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Cast object instance
	 * @var \Psecio\Iniscan\Cast
	 */
	private $cast;

	public function setUp()
	{
		$this->cast = new Cast();
	}
	public function tearDown()
	{
		unset($this->cast);
	}

	/**
	 * Test the casting of "Off" to zero
	 */
	public function testCastOff()
	{
		$this->assertEquals(
			$this->cast->castValue('Off'),
			0
		);
	}

	/**
	 * Test that an empty string casts to zero
	 */
	public function testCastEmpty()
	{
		$this->assertEquals(
			$this->cast->castValue(''),
			0
		);
	}

	/**
	 * Test that zero is cast to zero (duh)
	 */
	public function testCastZero()
	{
		$this->assertEquals(
			$this->cast->castValue(0),
			0
		);
	}

	/**
	 * Test that a string of "0" is cast to zero
	 */
	public function testCastStringZero()
	{
		$this->assertEquals(
			$this->cast->castValue('0'),
			0
		);
	}

	/**
	 * Test the casting of boolean false to zero
	 */
	public function testCastFalse()
	{
		$this->assertEquals(
			$this->cast->castValue(false),
			0
		);
	}

	/**
	 * Test that the string "On" is cast to one
	 */
	public function testCastOn()
	{
		$this->assertEquals(
			$this->cast->castValue('On'),
			1
		);
	}

	/**
	 * Test the string "1" is cast to one
	 */
	public function testCastStringOne()
	{
		$this->assertEquals(
			$this->cast->castValue('1'),
			1
		);
	}

	/**
	 * Test that the integer 1 is cast to one
	 */
	public function testCastIntegerOne()
	{
		$this->assertEquals(
			$this->cast->castValue(1),
			1
		);
	}

	/**
	 * Test that non-On/Off values pass on through
	 */
	public function testCastOther()
	{
		$other = 'thisisatest';

		$this->assertEquals(
			$this->cast->castValue($other),
			$other
		);
	}
}