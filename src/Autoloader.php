<?php
class Autoloader
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function autoload($class_name)
    {
        $filename = $class_name;

        // Support to load classes form app/modules directory who used namespaces
        if ($this->isClassNamespaceFromModulesDriectory($class_name) and
            $this->isClassNamespaceEqualCurrentModuleDirectory($class_name)) {
            $filename = $this->getFileClassNameFormNamespaceClassName($class_name);
        }

        $file_path = $this->directory . '/' . $filename . '.php';

        if (file_exists($file_path) == false) {
            return false;
        }

        include $file_path;
    }

    private function isClassNamespaceFromModulesDriectory($class_name)
    {
        return strpos($class_name, '\\') !== false and strpos($this->directory, 'app/modules') !== false;
    }

    private function isClassNamespaceEqualCurrentModuleDirectory($class_name)
    {
        list($module_name, $file_class_name) = explode('\\', $class_name);
        $module_name = strtolower($module_name);

        return strpos($this->directory, "app/modules/$module_name") !== false;
    }

    private function getFileClassNameFormNamespaceClassName($class_name)
    {
        $arr = explode('\\', $class_name);

        return end($arr);
    }
}
