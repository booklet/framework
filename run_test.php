#!/usr/bin/php
<?php
require_once('vendor/autoload.php');

Config::set('password_salt', "18ac6e5a5e2e5567a1580f78eb33c3160bc58b0a522bad40465a918bd4ba9d5b465ffd0b1fbd");
Config::set('env', 'test');


// to remove
// API TEST
Config::set('api_url', 'http://api.booklet.dev/v1');


echo "\nRun all tests\n";
$time_start = microtime(true);
$tests = new Tester([null, 'tests_paths' => ['tests']]);
$tests->run();
echo "\nFinished in " . number_format((microtime(true) - $time_start), 2) . " seconds.\n\n";
