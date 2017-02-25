<?php
class FloatUntils
{
    /**
    * 1.222 => 1.3
    * 1.222822222 => 1.223
    */
    public static function ceilTo($number, $precision = 2)
    {
        return ceil($number * pow(10, $precision)) / pow(10, $precision);
    }

    /**
    * 1.222 => 1.2
    * 1.222822222 => 1.222
    */
    public static function floorTo($number, $precision = 2)
    {
        return floor($number * pow(10, $precision)) / pow(10, $precision);
    }
}
