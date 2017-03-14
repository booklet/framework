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

    public static function isVariableNotExistsOrEmpty($var)
    {
        return !self::isVariableExistsAndNotEmpty($var);
    }
}
