<?php
/**
* Autoload class file when needed
*/
class Autoloader
{
    private $directory_name;

    public function __construct($directory_name) {
        $this->directory_name = $directory_name;
    }

    public function autoload($class_name) {
        $file_name = $class_name . '.php';
        $file = $this->directory_name.'/'.$file_name;
        if (file_exists($file) == false) {
            return false;
        }
        include ($file);
    }
}

foreach (Config::get('autoloader_paths') as $path) {
    $loader = new Autoloader($path);
    spl_autoload_register([$loader, 'autoload']);
}
