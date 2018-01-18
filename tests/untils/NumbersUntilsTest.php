<?php
class NumbersUntilsTest extends TesterCase
{
    public function testFormatCurrency()
    {
        $fc = NumbersUntils::formatCurrency(1234567.899999);
        Assert::expect($fc)->to_equal('1 234 567,90 zł');
    }
}
