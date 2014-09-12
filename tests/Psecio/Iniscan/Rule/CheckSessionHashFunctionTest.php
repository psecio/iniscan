<?php

namespace Psecio\Iniscan\Rule;

class CheckSessionHashFunctionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a hashing function is rejected if we specify one of the
     * broken hashing functions
     *
     * @covers \Psecio\Iniscan\Rule\CheckSessionHashFunction::evaluate
     */
    public function testCheckSessionHashFunctionFail()
    {
        $config = array();
        $section = 'Session';
        $rule = new CheckSessionHashFunction($config, $section);

        $ini = array('session.hash_function' => '0');
        $result = $rule->evaluate($ini);
        $this->assertFalse($result);

        $ini = array('session.hash_function' => '1');
        $result = $rule->evaluate($ini);
        $this->assertFalse($result);

        $ini = array('session.hash_function' => 'md5');
        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

    /**
     * Test that a hashing function is accepted if we specify one of the
     * robust hashing functions
     *
     * @covers \Psecio\Iniscan\Rule\CheckSessionHashFunction::evaluate
     */
    public function testCheckSessionHashFunction()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckSessionHashFunction($config, $section);

        // Get a list of available hashing algorithms on this machine
        $availableHashes = array_unique(hash_algos());

        // Filter out the unwanted hashing algorithms
        // http://en.wikipedia.org/wiki/Category:Broken_hash_functions
        $brokenHashes = array(
            'md2',
            'md4',
            'md5',
            'sha1',
            'gost',
            'snefru'
        );
        // Grab the first available hash on this machine
        $safeHashes = array_diff($availableHashes, $brokenHashes);

        if (empty($safeHashes))
        {
            // Can't really test then since this machine doesn't have
            // certain algorithms.
            return true;
        }

        $ini = array('session.hash_function' => current($safeHashes));
        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }

    /**
     * Test that the object is created correctly
     *
     * @covers \Psecio\Iniscan\Rule\CheckSessionHashFunction::__construct
     */
    public function testCheckSessionHashFunctionInit()
    {
        $config = array('bar' => 'foo');
        $section = 'PHP';
        $rule = new CheckSessionHashFunction($config, $section);

        $this->assertEquals($rule->bar, 'foo');
    }
}