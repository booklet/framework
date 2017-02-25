<?php
class ObjectUntilsTest extends TesterCase
{
    public function objectForTest()
    {
        $obj = new stdClass;
        $obj->attrib1 = 'value1';
        $obj->attrib2 = 'value2';
        return $obj;
    }

    public function testMysqlParameters()
    {
        $obj = $this->objectForTest();

        $parameters = ObjectUntils::mysqlParameters($obj);
        Assert::expect($parameters)->to_equal('`attrib1`, `attrib2`');

        $parameters = ObjectUntils::mysqlParameters([]);
        Assert::expect($parameters)->to_equal(false);
    }

    public function testMysqlParametersUpdate()
    {
        $obj = $this->objectForTest();

        $parameters = ObjectUntils::mysqlParametersUpdate($obj);
        Assert::expect($parameters)->to_equal('`attrib1`=?, `attrib2`=?');

        $parameters = ObjectUntils::mysqlParametersUpdate([]);
        Assert::expect($parameters)->to_equal(false);
    }

    public function testParameters()
    {

    }

    public function testMysqlParametersValuesArray()
    {
        $obj = $this->objectForTest();

        $values = ObjectUntils::mysqlParametersValuesArray($obj);
        Assert::expect($values)->to_equal(['value1', 'value2']);

        $values = ObjectUntils::mysqlParametersValuesArray([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testMysqlParametersValues()
    {
        $obj = $this->objectForTest();

        $values = ObjectUntils::mysqlParametersValues($obj);
        Assert::expect($values)->to_equal('value1, value2');

        $values = ObjectUntils::mysqlParametersValues([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testMysqlParametersValuesPlaceholder()
    {
        $obj = $this->objectForTest();

        $values = ObjectUntils::mysqlParametersValuesPlaceholder($obj);
        Assert::expect($values)->to_equal('?, ?');

        $values = ObjectUntils::mysqlParametersValuesPlaceholder([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testObjToArray()
    {
        $obj = $this->objectForTest();

        $values = ObjectUntils::objToArray($obj);
        Assert::expect($values)->to_equal(['attrib1'=>'value1', 'attrib2'=>'value2']);

        $values = ObjectUntils::objToArray(['attr1'=>'val1', 'attr2'=>'val2']);
        Assert::expect($values)->to_equal(['attr1'=>'val1', 'attr2'=>'val2']);

        $values = ObjectUntils::objToArray('wrong_params');
        Assert::expect($values)->to_equal(false);
    }
}
