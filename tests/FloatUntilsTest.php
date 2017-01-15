<?php
class FloatUntilsTest extends TesterCase
{
    public function testFnCeilTo()
    {
        $ceil = FloatUntils::ceil_to(1.222, 1);
        Assert::expect($ceil)->to_equal(1.3);

        $ceil = FloatUntils::ceil_to(1.888, 1);
        Assert::expect($ceil)->to_equal(1.9);

        $ceil = FloatUntils::ceil_to(1.222222222, 3);
        Assert::expect($ceil)->to_equal(1.223);
    }

    public function testFnFloorTo()
    {
        $ceil = FloatUntils::floorTo(1.222, 1);
        Assert::expect($ceil)->to_equal(1.2);

        $ceil = FloatUntils::floorTo(1.888, 1);
        Assert::expect($ceil)->to_equal(1.8);

        $ceil = FloatUntils::floorTo(1.222822222, 3);
        Assert::expect($ceil)->to_equal(1.222);
    }
}
