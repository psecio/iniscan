<?php

namespace Psecio\Iniscan\Rule;

class CheckUploadTmpDirTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that when upload_tmp_dir isn't inside open_basedir, we evaluate false
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
/*    public function testUploadTmpDirFail()
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
     * Test that when upload_tmp_dir is inside open_basedir, we evaluate true
     *
     * @covers \Psecio\Iniscan\Rule\CheckUploadTmpDir::evaluate
     */
/*    public function testUploadTmpDirSysDefault()
    {
        $config = array();
        $section = 'PHP';
        $rule = new CheckUploadTmpDir($config, $section);

        $ini = array(
            'open_basedir' => sys_get_temp_dir()
        );

        $result = $rule->evaluate($ini);
        $this->assertTrue($result);
    }*/
}