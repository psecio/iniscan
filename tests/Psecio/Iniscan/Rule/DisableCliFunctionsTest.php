<?php

namespace Psecio\Iniscan\Rule;

class DisableCliFunctionsTest extends \PHPUnit_Framework_TestCase
{
    private $functions = array(
        'exec', 'passthru', 'shell_exec', 'system',
        'proc_open', 'popen', 'curl_exec', 'curl_multi_exec'
    );

    /**
     * Test that a true (pass) is returned when all functions are disabled
     *
     * @covers \Psecio\Iniscan\Rule\DisableCliFunctions::evaluate
     */
    public function testAllFunctionsDisabled()
    {
        $config = array();
        $section = 'PHP';
        $rule = new DisableCliFunctions($config, $section);

        $ini = array(
            'disable_functions' => implode(',', $this->functions)
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }

    /**
     * Test that a false (fail) is returned when all functions are not disabled
     *
     * @covers \Psecio\Iniscan\Rule\DisableCliFunctions::evaluate
     */
    public function testSomeFunctionsDisabled()
    {
        $config = array();
        $section = 'PHP';
        $rule = new DisableCliFunctions($config, $section);

        $ini = array(
            'disable_functions' => 'exec,passthru,system'
        );

        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

    /**
     * Test that the remaining non-disabled method list is correct
     *
     * @covers \Psecio\Iniscan\Rule\DisableCliFunctions::evaluate
     * @covers \Psecio\Iniscan\Rule\DisableCliFunctions::__toString
     */
    public function testStringOutputDisabled()
    {
        $functionList = array('exec','passthru','system');
        $output = 'disable_functions = shell_exec, proc_open, popen, curl_exec, curl_multi_exec';

        $rule = new DisableCliFunctions(array(), 'PHP');
        $ini = array(
            'disable_functions' => implode(',', $functionList)
        );

        $result = $rule->evaluate($ini);
        $this->assertEquals((string)$rule, $output);

    }
}