<?php
class VarUntils
{
    public static function isVariableExistsAndNotEmpty($var)
    {
        if (isset($var) and !empty($var)) {
            return true;
        }

        return false;
    }
}
