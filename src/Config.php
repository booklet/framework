<?php
abstract class Config
{
    public static $items = [];

    public static function get($key = null)
    {
        return static::$items[$key] ?? null;
    }

    public static function set($key, $val)
    {
        static::$items[$key] = $val;
    }
}
