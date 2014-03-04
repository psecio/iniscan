<?php

namespace Psecio\Iniscan\Rule;

class CheckCertPathTest extends \PHPUnit_Framework_TestCase
{
    public function providerCert()
    {
        return array(
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => ''),
                false
            ),
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => '/not/a/valid/file'),
                false
            ),
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => __FILE__),
                true,
            ),
            array(
                '5.5.0',
                array('openssl.cafile' => ''),
                true,
            ),
            array(
                '5.5.0',
                array('openssl.cafile' => '/not/a/valid/file'),
                false
            ),
            array(
                '5.5.0',
                array('openssl.cafile' => __FILE__),
                true,
            ),
            array(
                '5.6.0alpha3',
                array('openssl.capath' => ''),
                false
            ),
            array(
                '5.6.0alpha3',
                array('openssl.capath' => '/not/a/valid/dir'),
                false
            ),
            array(
                '5.6.0alpha3',
                array('openssl.capath' => __DIR__),
                true,
            ),
            array(
                '5.5.0',
                array('openssl.capath' => ''),
                true,
            ),
            array(
                '5.5.0',
                array('openssl.capath' => '/not/a/valid/dir'),
                false
            ),
            array(
                '5.5.0',
                array('openssl.capath' => __DIR__),
                true,
            ),
            // No capath and cafile
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => '', 'openssl.capath' => ''),
                false
            ),
            // Invalid capath and cafile
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => '/not/a/valid/file', 'openssl.capath' => '/not/a/valid/dir'),
                false
            ),
            // Invalid cafile, valid capath
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => '/not/a/valid/file', 'openssl.capath' => __DIR__),
                true,
            ),
            // Valid cafile, invalid capath
            array(
                '5.6.0alpha3',
                array('openssl.cafile' => __FILE__, 'openssl.capath' => '/not/a/valid/dir'),
                true,
            ),
        );
    }
        
    /**
     * Test permutations of openssl.cafile
     *
     * @covers \Psecio\Iniscan\Rule\CheckCertPathTest::evaluate
     * @dataProvider providerCert
     */
    public function testCheckCert($version, $test, $result)
    {
        $config = array();
        $section = 'OpenSSL';
        $rule = new CheckCertPath($config, $section);
        $rule->setVersion($version);
        
        $result = $rule->evaluate($test);
        $this->assertEquals($result, $result);
    }
}