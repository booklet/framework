<?php
class CLIUntils
{
    public static function colorize($text, $status) {
        $out = "";
        switch($status) {
            case "SUCCESS":
                $out = "\033[0;32m";
                break;
            case "FAILURE":
                $out = "\033[0;31m";
                break;
            case "WARNING":
                $out = "\033[1;33m";
                break;
            case "NOTE":
                $out = "\033[0;34m";
                break;
            default:
                throw new Exception("Invalid status: " . $status);
        }

        return chr(27) . "$out" . "$text" . chr(27) . "\033[0m";
    }
}
