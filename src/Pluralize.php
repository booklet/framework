<?php
class Pluralize
{
    function __construct()
    {

    }

    public function pluralizeClassName($class_name)
    {
        $pluralize_class_names_arr = Config::get('pluralize_class_names');

        if (array_key_exists($class_name, $pluralize_class_names_arr)) {
            return $pluralize_class_names_arr[$class_name];
        } else {
            throw new Exception("Missing pluralize class name for class: " . $class_name);
        }
    }

}
