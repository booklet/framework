<?php
class MysqlORMExtraParamsTest extends TesterCase
{
    public function testFnExtraParams()
    {
        $sql1 = MysqlORMExtraParams::extraParams(['limit' => 1, 'order' => 'id DESC']);
        Assert::expect($sql1)->to_equal(' ORDER BY id DESC LIMIT 0, 1');

        $sql2 = MysqlORMExtraParams::extraParams(['limit' => 1]);
        Assert::expect($sql2)->to_equal(' LIMIT 0, 1');

        $sql3 = MysqlORMExtraParams::extraParams(['order' => 'name DESC']);
        Assert::expect($sql3)->to_equal(' ORDER BY name DESC');

        $sql4 = MysqlORMExtraParams::extraParams(['paginate' => 2, 'per_page' => 50]);
        Assert::expect($sql4)->to_equal(' LIMIT 50, 50');

        $sql5 = MysqlORMExtraParams::extraParams(['paginate' => 5, 'per_page' => 25]);
        Assert::expect($sql5)->to_equal(' LIMIT 100, 25');
    }
}
