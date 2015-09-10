<?php

namespace Psecio\Iniscan\Rule;

class CheckUploadTmpDirTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that when upload_tmp_dir isn't inside open_basedir, we evaluate false
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
    public function testUploadTmpDirNotInOpenBaseDir()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => '/tmp',
            'upload_tmp_dir' => '/upload'
        );

        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

    /**
     * Test that when upload_tmp_dir is inside open_basedir, we evaluate true
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
    public function testUploadTmpDirSuccess()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => '/tmp',
            'upload_tmp_dir' => '/tmp/'
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }

    /**
     * Test that when open_basedir is set to system tmp dir and upload_tmp_dir is not set, we evaluate true
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
    public function testUploadTmpDirSysDefault()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => sys_get_temp_dir()
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }

    /**
     * Test that when upload_tmp_dir is inside one of open_basedir directories, we evaluate true
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
    public function testUploadTmpDirInOneOfOpenBasedir()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => '/tmp' . PATH_SEPARATOR . '/var',
            'upload_tmp_dir' => '/tmp'
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }

    /**
     * Test that when upload_tmp_dir is not inside one of open_basedir directories, we evaluate false
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
    public function testUploadTmpDirNotInOneOfOpenBasedir()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => '/tmp' . PATH_SEPARATOR . '/var',
            'upload_tmp_dir' => '/etc'
        );

        $result = $rule->evaluate($ini);
        $this->assertFalse($result);
    }

}