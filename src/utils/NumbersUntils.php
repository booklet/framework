<?php
class NumbersUntils
{
    // 1 => 001
    public static function addLeadingZeros($num, $lenght)
    {
        return str_pad($num, $lenght, 0, STR_PAD_LEFT);
    }

    public static function formatCurrency($value, string $currency = 'PLN')
    {
        $fmt = new NumberFormatter('pl_PL', NumberFormatter::CURRENCY);

        return $fmt->formatCurrency($value, $currency);
    }

    public static function isNumberBetween($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }
}
