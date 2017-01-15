<?php
class JsonUntilsTest extends TesterCase
{
    public function testFnIsJSON()
    {
        $is_json = JsonUntils::isJSON('{"key":"val"}');
        Assert::expect($is_json)->to_equal(true);

        $is_json = JsonUntils::isJSON('[{"key":"val"}]');
        Assert::expect($is_json)->to_equal(true);

        $is_json = JsonUntils::isJSON('{"key":');
        Assert::expect($is_json)->to_equal(false);

        $is_json = JsonUntils::isJSON('var1=one&var2=two');
        Assert::expect($is_json)->to_equal(false);
    }
}
