#!/usr/bin/php
<?php
include_once 'src/Config.php';
Config::set('autoloader_paths', ['src', 'src/models', 'src/orm', 'src/utils', 'tests/fixtures/support']);
include_once 'src/Autoloader.php';

foreach (Config::get('autoloader_paths') as $path) {
    $loader = new Autoloader($path);
    spl_autoload_register([$loader, 'autoload']);
}

Config::set('password_salt', "18ac6e5a5e2e5567a1580f78eb33c3160bc58b0a522bad40465a918bd4ba9d5b465ffd0b1fbd");

require_once('vendor/autoload.php');

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


MyDB::connect(Config::get('db_test'));




echo "\nRun all tests\n";
$time_start = microtime(true);
$tests = new Tester(['db_connection' => MyDB::db(), 'tests_paths' => ['tests']]);
$tests->run();
echo "\nFinished in ". number_format((microtime(true) - $time_start), 2)." seconds.\n\n";
