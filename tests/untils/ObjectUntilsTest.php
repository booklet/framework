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

        $parameters = FWObjectUntils::mysqlParameters($obj);
        Assert::expect($parameters)->to_equal('`attrib1`, `attrib2`');

        $parameters = FWObjectUntils::mysqlParameters([]);
        Assert::expect($parameters)->to_equal(false);
    }

    public function testMysqlParametersUpdate()
    {
        $obj = $this->objectForTest();

        $parameters = FWObjectUntils::mysqlParametersUpdate($obj);
        Assert::expect($parameters)->to_equal('`attrib1`=?, `attrib2`=?');

        $parameters = FWObjectUntils::mysqlParametersUpdate([]);
        Assert::expect($parameters)->to_equal(false);
    }

    public function testParameters()
    {

    }

    public function testMysqlParametersValuesArray()
    {
        $obj = $this->objectForTest();

        $values = FWObjectUntils::mysqlParametersValuesArray($obj);
        Assert::expect($values)->to_equal(['value1', 'value2']);

        $values = FWObjectUntils::mysqlParametersValuesArray([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testMysqlParametersValues()
    {
        $obj = $this->objectForTest();

        $values = FWObjectUntils::mysqlParametersValues($obj);
        Assert::expect($values)->to_equal('value1, value2');

        $values = FWObjectUntils::mysqlParametersValues([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testMysqlParametersValuesPlaceholder()
    {
        $obj = $this->objectForTest();

        $values = FWObjectUntils::mysqlParametersValuesPlaceholder($obj);
        Assert::expect($values)->to_equal('?, ?');

        $values = FWObjectUntils::mysqlParametersValuesPlaceholder([]);
        Assert::expect($values)->to_equal(false);
    }

    public function testObjToArray()
    {
        $obj = $this->objectForTest();

        $values = Util::objToArray($obj);
        Assert::expect($values)->to_equal(['attrib1'=>'value1', 'attrib2'=>'value2']);

        $values = Util::objToArray(['attr1'=>'val1', 'attr2'=>'val2']);
        Assert::expect($values)->to_equal(['attr1'=>'val1', 'attr2'=>'val2']);

        $values = Util::objToArray('wrong_params');
        Assert::expect($values)->to_equal(false);
    }
}
