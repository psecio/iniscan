<?php

namespace Psecio\Iniscan\Rule;

class MaximumPostSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a max post size of 12M is too large
     *
     * @covers \Psecio\Iniscan\Rule\MaximumPostSize::evaluate
     */
    public function testPostSizeTooLarge()
    {
        $config = array();
        $section = 'PHP';
        $rule = new MaximumPostSize($config, $section);

        $ini = array(
            'post_max_size' => '12M'
        );

        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

    /**
     * Test that a max post size of 8M (the default) is the max
     *
     * @covers \Psecio\Iniscan\Rule\MaximumPostSize::evaluate
     */
    public function testPostSizeJustRight()
    {
        $config = array();
        $section = 'PHP';
        $rule = new MaximumPostSize($config, $section);

        $ini = array(
            'post_max_size' => '8M'
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }
}