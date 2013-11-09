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

    public function testGetRuleContext()
    {
        $context = array('prod');
        $test = array(
            'key' => 'foo',
            'context' => $context
        );
        $rule = new Rule(array(), 'testsection');
        $rule->setTest($test);

        $this->assertEquals($rule->getContext(), $context);
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

    /**
     * Test that an exception is thrown when a non-boolean
     *     status is set
     * 
     * @expectedException \InvalidArgumentException
     * @covers \Psecio\Iniscan\Rule::setStatus
     */
    public function testSetNonBooleanStatus()
    {
        $rule = new Rule(array(), 'testing123');
        $rule->setStatus('badvalue');
    }

    /**
     * Test the getter/setter for level
     * 
     * @covers \Psecio\Iniscan\Rule::getLevel
     */
    public function testGetSetLevel()
    {
        $config = array(
            'level' => 'ERROR'
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->getLevel(), 'ERROR');
    }

    /**
     * Test the getter/setter for status
     * 
     * @covers \Psecio\Iniscan\Rule::getStatus
     */
    public function testGetSetStatus()
    {
        $config = array(
            'status' => true
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->getStatus(), true);
    }

    /**
     * Test the getter for the rule's test key
     * 
     * @covers \Psecio\Iniscan\Rule::getTestKey
     */
    public function testGetTestKey()
    {
        $config = array(
            'test' => (object)array(
                'key' => 'foobar'
            )
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->getTestKey(), 'foobar');
    }

    /**
     * Test that an exception is thrown when no test key is defined
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testGetTestKeyNoKey()
    {
        $config = array('test' => (object)array());
        $rule = new Rule($config, 'testing123');
        $rule->getTestKey();
    }

    /**
     * Test the result of the values method to
     *     make an array from the object
     * 
     * @covers \Psecio\Iniscan\Rule::values
     */
    public function testGetRuleValues()
    {
        $config = array(
            'name' => 'test1',
            'description' => 'test description',
            'level' => 'ERROR',
            'status' => true
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->values(), $config);
    }
}