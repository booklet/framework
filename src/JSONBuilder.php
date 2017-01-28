<?php
class JSONBuilder
{
    public $data;
    public $view;
    /**
    * Ta klasa uzywa pliku widoku do budowy obiektu odpowiedzi
    * @param Array $data
    * @return String $template
    */
    public function __construct($data, $template)
    {
        // check if template is a path or ClassController::action
        if (strpos($template, '::') !== false) {
            // SessionsController::index => $class = 'session', $method = 'index'
            list($controller, $method) = explode('::', $template);
            $class = str_replace("Controller", "", $controller);

            $class_pluralize_name = Inflector::pluralize($class);

            $class = Util::camelCaseStringToUnderscore($class_pluralize_name);
            // $class = strtolower($class);

            // zbuduj scieze do pliku wygladu
            $this->view = "app/views/" . $class . "/" . $method . ".php";
        } else {
            $this->view = $template;
        }

        // check if file exist
        if (file_exists($this->view)) {
            include $this->view; // grab $response variable
        } else {
            throw new RuntimeException('Missing view file.');
        }

        $this->data = json_encode($response);
    }
}
