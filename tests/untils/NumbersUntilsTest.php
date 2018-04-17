<?php
class NumbersUntilsTest extends TesterCase
{
    public function testFormatCurrency()
    {
        $fc = NumbersUntils::formatCurrency(1234567.899999);
        Assert::expect($fc)->to_equal('1 234 567,90 zł');
    }

    public function testIsNumberBetween()
    {
        $is = NumbersUntils::isNumberBetween(99, 100, 200);
        Assert::expect($is)->to_equal(false);

        $is = NumbersUntils::isNumberBetween(100, 100, 200);
        Assert::expect($is)->to_equal(true);

        $is = NumbersUntils::isNumberBetween(150, 100, 200);
        Assert::expect($is)->to_equal(true);

        $is = NumbersUntils::isNumberBetween(200, 100, 200);
        Assert::expect($is)->to_equal(true);

        $is = NumbersUntils::isNumberBetween(201, 100, 200);
        Assert::expect($is)->to_equal(false);
    }
}
