<?php
class DateTimeUntils
{
    public static function daysInMonth($year, $month)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public static function monthBeginningDate($year, $month)
    {
        $m = sprintf("%02d", $month);

        return "{$year}-{$m}-01 00:00:00";
    }

    public static function monthEndDate($year, $month)
    {
        $days_in_month = self::daysInMonth($year, $month);
        $m = sprintf("%02d", $month);

        return "{$year}-{$m}-{$days_in_month} 23:59:59";
    }
}
