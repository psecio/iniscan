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
            'PHP' => array(
                'disable_functions' => implode(',', $this->functions)
            )
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
            'PHP' => array(
                'disable_functions' => 'exec,passthru,system'
            )
        );

        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }
}