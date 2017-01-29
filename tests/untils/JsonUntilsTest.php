<?php
class JsonUntilsTest extends TesterCase
{
    public function testFnIsJSON()
    {
        $is_json = Util::isJSON('{"key":"val"}');
        Assert::expect($is_json)->to_equal(true);

        $is_json = Util::isJSON('[{"key":"val"}]');
        Assert::expect($is_json)->to_equal(true);

        $is_json = Util::isJSON('{"key":');
        Assert::expect($is_json)->to_equal(false);

        $is_json = Util::isJSON('var1=one&var2=two');
        Assert::expect($is_json)->to_equal(false);
    }
}
