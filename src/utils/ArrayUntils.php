<?php
trait ArrayUntils
{
    // check if array is associative or sequential
    // var_dump(isAssocArray(['a', 'b', 'c'])); // false
    // var_dump(isAssocArray(["0" => 'a', "1" => 'b', "2" => 'c'])); // false
    // var_dump(isAssocArray(["1" => 'a', "0" => 'b', "2" => 'c'])); // true
    // var_dump(isAssocArray(["a" => 'a', "b" => 'b', "c" => 'c'])); // true
    public static function isAssocArray($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
