<?php

namespace Psecio\Iniscan;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the configuration is set correctly on init
     *
     * @covers \Psecio\Iniscan\Rule::__construct
     */
    public function testConfigOnInit()
    {
        $name = 'test rule';
        $config = array('name' => $name);

        $rule = new Rule($config, 'testsection');
        $this->assertEquals($rule->getName(), $name);
    }

    /**
     * Test that the rule fails correctly
     *
     * @covers \Psecio\Iniscan\Rule::fail
     */
    public function testFailStatus()
    {
        $rule = new Rule(array(), 'testsection');
        $rule->fail();

        $this->assertFalse($rule->getStatus());
    }

    /**
     * Test that the rule passes correctly
     *
     * @covers \Psecio\Iniscan\Rule::pass
     */
    public function testPassStatus()
    {
        $rule = new Rule(array(), 'testsection');
        $rule->pass();

        $this->assertTrue($rule->getStatus());
    }

    /**
     * Test that the "test" is set correctly
     *
     * @covers \Psecio\Iniscan\Rule::setTest
     * @covers \Psecio\Iniscan\Rule::getTest
     */
    public function testGetSetTest()
    {
        $test = array(
            'key' => 'foo'
        );
        $rule = new Rule(array(), 'testsection');
        $rule->setTest($test);

        $this->assertEquals($rule->getTest(), (object)$test);
    }

    /**
     * Test that the description is set correctly
     *
     * @covers \Psecio\Iniscan\Rule::setDescription
     * @covers \Psecio\Iniscan\Rule::getDescription
     */
    public function testGetSetDescription()
    {
        $desc = 'this is the rule description';
        $rule = new Rule(array(), 'testsection');

        $rule->setDescription($desc);
        $this->assertEquals($rule->getDescription(), $desc);
    }

    /**
     * Test that the section is set correctly
     *
     * @covers \Psecio\Iniscan\Rule::getSection
     * @covers \Psecio\Iniscan\Rule::setSection
     */
    public function testGetSection()
    {
        $rule = new Rule(array(), 'testing123');
        $this->assertEquals('testing123', $rule->getSection());

        $rule->setSection('foobar');
        $this->assertEquals('foobar', $rule->getSection());
    }

    /**
     * Testing the setting of the config with an array
     * 
     * @covers \Psecio\Iniscan\Rule::setConfig
     */
    public function testSetConfigArray()
    {
        $config = array(
            'name' => 'testing'
        );
        $rule = new Rule(array(), 'testing123');
        $rule->setConfig($config);
        $this->assertEquals($rule->getName(), 'testing');
    }

    /**
     * Testing the setting of the configuration with an object
     * 
     * @covers \Psecio\Iniscan\Rule::setConfig
     */
    public function testSetConfigObject()
    {
        $obj = new \stdClass();
        $obj->name = 'testing';

        $rule = new Rule(array(), 'testing123');
        $rule->setConfig($obj);
        $this->assertEquals($rule->getName(), 'testing');
    }
}