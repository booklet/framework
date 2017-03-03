<?php
class JSONBuilder
{
    public $data;
    public $controller_and_action_or_path;

    /**
    * Ta klasa uzywa pliku widoku do budowy obiektu odpowiedzi
    */
    public function __construct($data, $controller_and_action_or_path)
    {
        $this->data = $data;
        $this->controller_and_action_or_path = $controller_and_action_or_path;
    }

    public function render()
    {
        $view_file_path = $this->getFilePath();

        if (file_exists($view_file_path)) {
            $data = $this->data; // $data variable use in view
            include $view_file_path; // Grab $response variable
        } else {
            throw new RuntimeException('Missing view file.');
        }

        return $response;
    }

    private function getFilePath()
    {
        // Check if template is a path or ClassController::action
        if (strpos($this->controller_and_action_or_path, '::') !== false) {
            // SessionsController::index => $class = 'session', $method = 'index'
            list($controller, $method) = explode('::', $this->controller_and_action_or_path);
            $class = str_replace('Controller', '', $controller);
            $class_pluralize_name = Inflector::pluralize($class);
            $class = StringUntils::camelCaseToUnderscore($class_pluralize_name);

            return 'app/views/' . $class . '/' . $method . '.php';
        }

        return $this->controller_and_action_or_path;
    }
}
