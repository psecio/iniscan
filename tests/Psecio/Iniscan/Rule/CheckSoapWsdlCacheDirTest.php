<?php

namespace Psecio\Iniscan\Rule;

class CheckSoapWsdlCacheDirTest extends \PHPUnit_Framework_TestCase
{
    public function soapConfigurationProvider() {
        return array(
            array(1, '5.3.21', array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '0', 'soap.wsdl_cache_dir' => '')),
            array(0, '5.3.21', array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/foo')),
            array(1, '5.3.21', array('open_basedir' => '/usr', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/usr')),
            array(0, '5.3.21', array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/tmp')),
            array(0, '5.3.21', array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '')),
            array(0, '5.6.7',  array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/tmp')),
            array(0, '5.6.7',  array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/tmp')),
            array(0, '5.6.7',  array('open_basedir' => '/tmp', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '')),
            array(1, '5.6.7',  array('open_basedir' => '/usr', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/usr')),
            array(0, '5.6.8',  array('open_basedir' => '/usr', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '/foo')),
            array(1, '5.6.8',  array('open_basedir' => '/foo', 'soap.wsdl_cache_enabled' => '1', 'soap.wsdl_cache_dir' => '')),
        );
    }

    /**
     * Test various SOAP WSDL Cache configurations
     *
     * @dataProvider soapConfigurationProvider
     * @covers \Psecio\Iniscan\Rule\CheckSoapWsdlCacheDir::evaluate
     */
    public function testSoapWsdlCache($expected, $version, $ini)
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckSoapWsdlCacheDir($config, $section);

        $rule->setVersion($version);
        $result = $rule->evaluate($ini);
        $this->assertEquals($expected, $result);
    }


    /**
     * Validate that the test config (key) is set correctly on
     *     object init
     */
    public function testValidateTestConfig()
    {
        $rule = new CheckSoapWsdlCacheDir(array(), 'PHP');
        $test = $rule->getTest();

        $this->assertEquals($test->key, 'soap.wsdl_cache_dir');
    }
}