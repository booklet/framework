<?php
class CLIUntils
{
    public static function colorizeConsoleOutput($text, $status)
    {
        $statuses = [
            'SUCCESS' => "\033[0;32m",
            'FAILURE' => "\033[0;31m",
            'WARNING' => "\033[1;33m",
            'NOTE' => "\033[0;34m",
        ];

        if (array_key_exists($status, $statuses)) {
            return chr(27) . $statuses[$status] . $text . chr(27) . "\033[0m";
        }

        throw new Exception('Invalid status: ' . $status);
    }
}
