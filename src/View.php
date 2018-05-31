<?php
class View
{
    public $layout;
    public $variables;
    public $path;
    public $params;

    public function __construct($params, array $variables = [], array $options = [])
    {
        $this->params = $params;
        $this->layout = $options['layout'] ?? 'app';
        $this->variables = $variables;

        if (isset($options['path'])) {
            // example: 'app/views/session/login.php'
            $this->path = $options['path'];
        } else {
            if (!empty($params['controller'])) {
                // Namespace support
                $parts = explode('\\', $params['controller']);
                $folder = str_replace('Controller', '', end($parts));
            } else {
                $folder = str_replace('Mailer', '', $params['mailer']);
            }

            $folder = StringUntils::camelCaseToUnderscore($folder);
            $file = StringUntils::camelCaseToUnderscore($params['action']) . '.php';

            // Check first module view folder, then default view folder
            if (!empty($params['controller'])) {
                $module_name = $this->getModuleNameBaseOnControllerName($params['controller']);
            } else {
                $module_name = $this->getModuleNameBaseOnControllerName($params['mailer']);
            }

            $module_file_path = 'app/modules/' . $module_name . '/views/' . $folder . '/' . $file;
            $mailer_module_file_path = 'app/modules/' . $module_name . '/views/mailers/' . $folder . '/' . $file;

            if (file_exists($module_file_path)) {
                $this->path = $module_file_path;
            } elseif (file_exists($mailer_module_file_path)) {
                $this->path = $mailer_module_file_path;
            } else {
                $this->path = 'app/views/' . $folder . '/' . $file;
            }
        }

        if (isset($options['helpers'])) {
            foreach ($options['helpers'] as $helper_name) {
                $helper_class_name = $helper_name . 'Helper';
                $this->{$helper_name} = new $helper_class_name();
            }
        }

        if (isset($options['router'])) {
            $this->router = $options['router'];
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
            // TEMP add support to new layouts directory
            if (file_exists('app/framework/views/layout/' . $this->layout . '.php')) {
                include 'app/framework/views/layout/' . $this->layout . '.php';
            } else {
                include 'app/views/layout/' . $this->layout . '.php';
            }
        }
        $rendered_view = ob_get_clean();

        return $rendered_view;
    }

    private function getModuleNameBaseOnControllerName($controller)
    {
        $reflector = new ReflectionClass($controller);
        $class_file_name = $reflector->getFileName();
        $path_to_class_file_name = dirname($class_file_name);

        // "/Users/admin/Sites/api.booklet.pl/app/modules/client/controllers" => "client"
        $path_elements = explode('/', $path_to_class_file_name);
        $module_name = $path_elements[count($path_elements) - 2];

        return $module_name;
    }
}
