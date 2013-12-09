<?php

namespace Psecio\Iniscan\Rule;

class CheckSoapWsdlCacheDirTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a SOAP WSDL cache dir is invalid
     *
     * @covers \Psecio\Iniscan\Rule\CheckSoapWsdlCacheDir::evaluate
     */
    public function testSoapWsdlCacheDirFail()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckSoapWsdlCacheDir($config, $section);

        $ini = array(
            'PHP' => array(
                'open_basedir' => '/tmp',
            ),
            'soap' => array(
                'soap.wsdl_cache_enabled' => '1',
                'soap.wsdl_cache_dir' => '/foo'
            )
        );

        $rule->setVersion('5.3.21');
        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

    /**
     * Test that a SOAP WSDL cache dir is set inside the open_basedir path
     *
     * @covers \Psecio\Iniscan\Rule\CheckSoapWsdlCacheDir::evaluate
     */
    public function testSoapWsdlCacheDir()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckSoapWsdlCacheDir($config, $section);

        $ini = array(
            'PHP' => array(
                'open_basedir' => '/tmp',
            ),
            'soap' => array(
                'soap.wsdl_cache_enabled' => '1',
                'soap.wsdl_cache_dir' => '/tmp'
            )
        );

        $rule->setVersion('5.3.21');
        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }
}