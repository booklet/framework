<?php
class ArrayUntils
{
    // check if array is associative or sequential
    // var_dump(isAssoc(['a', 'b', 'c'])); // false
    // var_dump(isAssoc(["0" => 'a', "1" => 'b', "2" => 'c'])); // false
    // var_dump(isAssoc(["1" => 'a', "0" => 'b', "2" => 'c'])); // true
    // var_dump(isAssoc(["a" => 'a', "b" => 'b', "c" => 'c'])); // true
    public static function isAssoc($arr) {
       return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
