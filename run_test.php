#!/usr/bin/php
<?php
include_once 'src/Config.php';
Config::set('autoloader_paths', ['src', 'tests/support']);
include_once 'src/Autoloader.php';

Config::set('pluralize_class_names', ['Session' => 'Sessions']);
Config::set('password_salt', "18ac6e5a5e2e5567a1580f78eb33c3160bc58b0a522bad40465a918bd4ba9d5b465ffd0b1fbd");

require_once('vendor/autoload.php');

echo "\nRun all tests\n";
$time_start = microtime(true);
$tests = new Tester(['tests_paths' => ['tests']]);
$tests->run();
echo "\nFinished in ". number_format((microtime(true) - $time_start), 2)." seconds.\n\n";
