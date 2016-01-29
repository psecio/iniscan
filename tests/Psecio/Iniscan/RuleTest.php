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

        $this->assertEquals('foo', $rule->getSection('foo.bar'));
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
     * @covers \Psecio\Iniscan\Rule::setStatus
     */
    public function testGetSetStatus()
    {
        $config = array(
            'status' => true
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->getStatus(), true);

        $rule->setStatus(false);
        $this->assertEquals($rule->getStatus(), false);
    }

    /**
     * Test the setting of the status to N/A (null)
     *
     * @covers \Psecio\Iniscan\Rule::na
     */
    public function testSetStatusNa()
    {
        $rule = new Rule(array(), 'testing123');

        $rule->na();
        $this->assertEquals($rule->getStatus(), null);
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
            'status' => true,
            'currentValue' => null
        );
        $rule = new Rule($config, 'testing123');
        $this->assertEquals($rule->values(), $config);
    }

    /**
     * Test the getter/setter for the name property
     *
     * @covers \Psecio\Iniscan\Rule::getName
     * @covers \Psecio\Iniscan\Rule::setName
     */
    public function testGetSetName()
    {
        $name = 'test name';
        $rule = new Rule(array(), 'testing123');
        $rule->setName($name);

        $this->assertEquals($rule->getName(), $name);
    }

    /**
     * Test the evaluation of a passing rule
     *
     * @covers \Psecio\Iniscan\Rule::evaluate
     */
    public function testEvaluationValid()
    {
        $test = array(
            'key' => 'foo',
            'operation' => 'equals',
            'value' => '1'
        );
        $ini = array(
            'foo' => '1'
        );
        $rule = new Rule(array(), 'PHP');
        $rule->setTest($test);

        $result = $rule->evaluate($ini);
        $this->assertTrue($rule->getStatus());
    }

    /**
     * Test the evluation of a failing rule
     *
     * @covers \Psecio\Iniscan\Rule::evaluate
     */
    public function testEvaluationInvalid()
    {
        $test = array(
            'key' => 'foo',
            'operation' => 'equals',
            'value' => '1'
        );
        $ini = array(
            'PHP' => array(
                'foo' => 'test'
            )
        );
        $rule = new Rule(array(), 'PHP');
        $rule->setTest($test);

        $result = $rule->evaluate($ini);
        $this->assertFalse($rule->getStatus());
    }

    /**
     * Test that the evaluation is null when the test is
     *     not applicable
     *
     * @covers \Psecio\Iniscan\Rule::evaluate
     */
    public function testEvaluationNa()
    {
        $test = array(
            'key' => 'foo',
            'operation' => 'equals',
            'value' => '1',
            'version' => '5.6.12'
        );
        $ini = array(
            'PHP' => array(
                'foo' => 'test'
            )
        );
        $rule = new Rule(array(), 'PHP');
        $rule->setTest($test);

        $result = $rule->evaluate($ini);
        $this->assertNull($rule->getStatus());
    }

    /**
     * Test the evaluation with a bad operation
     *
     * @expectedException \InvalidArgumentException
     * @covers \Psecio\Iniscan\Rule::evaluate
     */
    public function testEvaluationBadOperation()
    {
        $rule = new Rule(array(), 'PHP');
        $rule->setTest(array(
            'key' => 'foo',
            'operation' => 'badop',
            'value' => '1'
        ));
        $ini = array();
        $result = $rule->evaluate($ini);
    }

    /**
     * Data for the threshold tests
     */
    public function thresholdDataProvider() {
        return array(
            array('WARNING', null, true),
            array('WARNING', 'ERROR', false),
            array('ERROR', 'ERROR', true),
            array('FATAL', 'ERROR', true),
        );
    }

    /**
     * Test that the rules is above or on the wanted threshold
     *
     * @covers \Psecio\Iniscan\Rule::respectThreshold
     * @dataProvider thresholdDataProvider
     * @param string $level The rule level
     * @param string $threshold The wanted threshold
     * @param bool $expectedResult The expected function result
     */
    public function testRespectThreshold($level, $threshold, $expectedResult) {
        $config = array(
            'level' => $level
        );
        $rule = new Rule($config, 'testing123');

        $this->assertSame($expectedResult, $rule->respectThreshold($threshold));
    }

    /**
     * Test that the level numeric values are correct
     *
     * @covers \Psecio\Iniscan\Rule::getLevelNumericalValue
     */
    public function testGetValidNumericalValue()
    {
        $rule = new Rule(array(), 'testing123');
        $this->assertEquals($rule->getLevelNumericalValue('warning'), 10);
        $this->assertEquals($rule->getLevelNumericalValue('error'), 20);
        $this->assertEquals($rule->getLevelNumericalValue('fatal'), 30);
    }

    /**
     * Test that an invalid level returns a zero
     *
     * @covers \Psecio\Iniscan\Rule::getLevelNumericalValue
     */
    public function testGetInvalidNumericalValue()
    {
        $rule = new Rule(array(), 'testing123');
        $this->assertEquals($rule->getLevelNumericalValue('bad-level'), 0);
    }

    /**
     * Test that the "find" works as expected
     *
     * @covers \Psecio\Iniscan\Rule::findValue
     */
    public function testFindValueValid()
    {
        $rule = new Rule(array(), 'testing');
        $path = 'testing.foo.bar';
        $ini = array(
            'testing.foo.bar' => 'test'
        );
        $value = $rule->findValue($path, $ini);

        // In this case, the config is made up, so it returns false
        // and sets the value to the array
        $this->assertTrue($value === 'test');
        $this->assertTrue(isset($ini['testing.foo.bar']));
    }

    public function testFindValueDefault()
    {
        $savePath = ini_get('session.save_path');

        $rule = new Rule(array(), 'testing');
        $path = 'session.save_path';
        $ini = array();
        $value = $rule->findValue($path, $ini);
        $this->assertEquals($value, $savePath);

    }

    /**
     * Test the version evaluation
     *
     * @covers \Psecio\Iniscan\Rule::isVersion
     */
    public function testAboveVersion()
    {
        // a very old PHP release...please tell me you're not using it
        $phpVersion = '3.0';
        $rule = new Rule(array(), 'testing');

        // Ensure that the running version is fine
        $this->assertTrue(
            $rule->isVersion($phpVersion)
        );

        // Assume we're using 5.6 for now
        $rule->setVersion('5.6');
        // 5.6 > 3.0, so we get true
        $this->assertTrue(
            $rule->isVersion($phpVersion)
        );

        // Newer flavourful versions
        $phpVersion = '7.0';
        // 5.6 < 7.0, so we get false
        $this->assertNotTrue(
            $rule->isVersion($phpVersion)
        );
    }

    /**
     * Test the version getter/setter
     *
     * @covers \Psecio\Iniscan\Rule::getVersion
     * @covers \Psecio\Iniscan\Rule::setVersion
     */
    public function testGetSetVersion()
    {
        $version = '1.0';
        $rule = new Rule(array(), 'testing');
        $rule->setVersion($version);
        $this->assertEquals($rule->getVersion(), $version);

        $rule->setVersion('7.0');
        $this->assertNotEquals($rule->getVersion(), $version);
    }

    /**
     * Test the translation of casting values of "Off"
     *
     * @covers \Psecio\Iniscan\Cast::castValue
     */
    public function testCastValuesOff()
    {
        $rule = new Rule(array(), 'testing');

        $this->assertEquals($rule->getCast()->castValue('Off'), 0);
        $this->assertEquals($rule->getCast()->castValue(''), 0);
        $this->assertEquals($rule->getCast()->castValue(0), 0);
        $this->assertEquals($rule->getCast()->castValue('0'), 0);
    }

    /**
     * Test the translation of casting values of "On"
     *
     * @covers \Psecio\Iniscan\Cast::castValue
     */
    public function testCastValuesOn()
    {
        $rule = new Rule(array(), 'testing');

        $this->assertEquals($rule->getCast()->castValue('On'), 1);
        $this->assertEquals($rule->getCast()->castValue('1'), 1);
        $this->assertEquals($rule->getCast()->castValue(1), 1);
    }

    /**
     * Test the translation of casting values neither "On" or "Off"
     *
     * @covers \Psecio\Iniscan\Cast::castValue
     */
    public function testCastValueOther()
    {
        $rule = new Rule(array(), 'testing');

        $this->assertEquals($rule->getCast()->castValue('foo'), 'foo');
    }

    /**
     * Test the casting of powers from a string
     *
     * @covers \Psecio\Iniscan\Cast::castPowers
     */
    public function testCastPowers()
    {
        $rule = new Rule(array(), 'testing');

        $this->assertEquals($rule->getCast()->castPowers('1K'), 1024);
        $this->assertEquals($rule->getCast()->castPowers('1M'), 1024 * 1024);
        $this->assertEquals($rule->getCast()->castPowers('1G'), 1024 * 1024 * 1024);
    }

    /**
     * Test the getter/setter for the Cast instance
     *
     * @covers \Psecio\Iniscan\Rule::getCast
     * @covers \Psecio\Iniscan\Rule::setCast
     */
    public function testGetSetCast()
    {
        $cast = new \Psecio\Iniscan\Cast();
        $rule = new Rule(array(), 'testing');
        $rule->setCast($cast);

        $this->assertInstanceOf('\Psecio\Iniscan\Cast', $rule->getCast());
    }
}
