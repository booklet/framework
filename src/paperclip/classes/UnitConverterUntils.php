<?php
class UnitConverterUntils
{
    const MM_IN_POINTS = 2.834645;
    const POINTS_PER_INTCH = 72;
    const POINTS_IN_PIXEL = 0.75;
    const ONE_INCH_IN_MM = 25.4;
    const DEFAULT_DPI = 300;

    public static function ptToMm($val, $params = [])
    {
        $precision = $params['precision'] ?? null;
        $mm = $val / self::MM_IN_POINTS;

        if (isset($precision)) {
            return round($mm, $precision);
        }

        return $mm;
    }

    public static function ptToPx($val, $params = [])
    {
        $dpi = $params['dpi'] ?? self::DEFAULT_DPI;
        $px = $val * $dpi / self::POINTS_PER_INTCH;

        return intval(round($px));
    }

    public static function pxToMm($val, $params = [])
    {
        $precision = $params['precision'] ?? null;
        $dpi = $params['dpi'] ?? self::DEFAULT_DPI;
        $mm = $val * self::ONE_INCH_IN_MM / $dpi;

        if (isset($precision)) {
            return round($mm, $precision);
        }

        return $mm;
    }

    public static function pxToPt($val, $params = [])
    {
        $precision = $params['precision'] ?? null;
        $dpi = $params['dpi'] ?? self::DEFAULT_DPI;
        $pt = $val * self::POINTS_PER_INTCH / $dpi;

        if (isset($precision)) {
            return round($pt, $precision);
        }

        return $pt;
    }

    public static function mmToPx($val, $params = [])
    {
        $dpi = $params['dpi'] ?? self::DEFAULT_DPI;
        $px = $val * $dpi / self::ONE_INCH_IN_MM;

        return intval(round($px));
    }

    public static function changeDpiPt($val, $params = [])
    {
        $precision = $params['precision'] ?? null;
        $dpi = $params['dpi'] ?? self::DEFAULT_DPI;
        $pt = $val * self::DEFAULT_DPI / $dpi;

        if (isset($precision)) {
            return round($pt, $precision);
        }

        return $pt;
    }
}
