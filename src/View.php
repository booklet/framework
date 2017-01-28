<?php
class View
{
    public $layout;
    public $variables;
    public $path;
    public $params;

    public function __construct($params, Array $variables = [], Array $options = [])
    {
        $this->params = $params;
        $this->layout = $variables['layout'] ?? 'app';
        $this->variables = $variables;

        if (isset($variables['path'])) {
            // 'app/views/session/login.php'
            $this->path = $variables['path'];
        } else {
            $folder = Util::camelCaseStringToUnderscore(str_replace("Controller", "", $params['controller']));
            $file = strtolower($params['action']).'.php';
            $this->path = 'app/views/' . $folder . '/' . $file;
        }

        if (isset($options['helpers'])) {
            foreach ($options['helpers'] as $helper_name) {
                $helper_class_name = $helper_name . 'Helper';
                $this->{$helper_name} = new $helper_class_name;
            }
        }
    }

    public function render()
    {
        extract($this->variables); // change ['var1' => var1] to $var1
        $path = $this->path; // variable for layout yeld

        // add params variable to available in views
        $params = $this->params;

        ob_start();
        if ($this->layout === false) {
            include $path;
        } else {
            include 'app/views/layout/' . $this->layout . '.php';
        }
        $rendered_view = ob_get_clean();

        return $rendered_view;
    }
}
