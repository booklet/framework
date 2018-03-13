<?php
class DateTimeUntils
{
    const SECONDS_IN_A_MINUTE = 60;
    const SECONDS_IN_A_HOUR = 3600;
    const SECONDS_IN_AN_HOUR = 3600;
    const SECONDS_IN_A_DAY = 86400;
    const SECONDS_IN_A_WEEK = 604800;
    const SECONDS_IN_A_MONTH = 2592000;
    const SECONDS_IN_A_YEAR = 31536000;

    public static function daysInMonth($year, $month)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public static function monthBeginningDate($year, $month)
    {
        $m = sprintf('%02d', $month);

        return "{$year}-{$m}-01 00:00:00";
    }

    public static function monthEndDate($year, $month)
    {
        $days_in_month = self::daysInMonth($year, $month);
        $m = sprintf('%02d', $month);

        return "{$year}-{$m}-{$days_in_month} 23:59:59";
    }

    // Days from 1800-12-28
    // clarionDate('2001-12-31');
    // clarionDate('-10 days');
    public static function clarionDate($date_time_string = 'now')
    {
        $d1 = new DateTime('1800-12-28');
        $d2 = new DateTime($date_time_string);
        $interval = $d1->diff($d2);

        return $interval->format('%a');
    }
}
