<?php
class MysqlORMExtraParamsTest extends TesterCase
{
    public function testFnExtraParams()
    {
        $sql1 = MysqlORMExtraParams::extraParams(['limit' => 1, 'order'=>'id DESC']);
        Assert::expect($sql1)->to_equal(' ORDER BY id DESC LIMIT 0, 1');

        $sql2 = MysqlORMExtraParams::extraParams(['limit' => 1]);
        Assert::expect($sql2)->to_equal(' LIMIT 0, 1');

        $sql3 = MysqlORMExtraParams::extraParams(['order'=>'name DESC']);
        Assert::expect($sql3)->to_equal(' ORDER BY name DESC');
    }
}
