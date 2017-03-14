<?php
class VarUntilsTest extends TesterCase
{
    public function testIsVariableExistsAndNotEmpty()
    {
        $var = null;
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(false);

        $var = '';
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(false);

        $var = [];
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(false);

        $var = 'a';
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(true);

        // TODO decide whether it should return a true or false
        $var = 0;
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(false);

        $var = ['a' => 1];
        Assert::expect(VarUntils::isVariableExistsAndNotEmpty($var))->to_equal(true);
    }
}
