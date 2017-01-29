#!/usr/bin/php
<?php
require_once('vendor/autoload.php');

Config::set('password_salt', "18ac6e5a5e2e5567a1580f78eb33c3160bc58b0a522bad40465a918bd4ba9d5b465ffd0b1fbd");

Config::set('env', 'test');

// API TEST
Config::set('api_url', 'http://api.booklet.dev/v1');

// TIME FOR DATABASE
Config::set('mysqltime', "Y-m-d H:i:s");

$test = array();
$test['host'] = '127.0.0.1';
$test['user'] = 'framework';
$test['password'] = 'framework';
$test['name'] = 'framework';
Config::set('db_test', $test);

// to remove
MyDB::connect(Config::get('db_test'));

echo "\nRun all tests\n";
$time_start = microtime(true);
$tests = new Tester(['db_connection' => MyDB::db(), 'tests_paths' => ['tests']]);
$tests->run();
echo "\nFinished in ". number_format((microtime(true) - $time_start), 2)." seconds.\n\n";
