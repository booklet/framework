<?php
trait ArrayUntils
{
    /**
    * Check if array is associative or sequential
    * ['a', 'b', 'c'] // false
    * ["0" => 'a', "1" => 'b', "2" => 'c'] // false
    * ["1" => 'a', "0" => 'b', "2" => 'c'] // true
    * ["a" => 'a', "b" => 'b', "c" => 'c'] // true
    */
    public static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
