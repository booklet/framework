<?php
class Authorize
{
    public $auth_class;
    public $auth_action;
    public $current_user;

    /**
    * @param $method => 'UserController::index'
    * @param Array $params
    */
    function __construct($method, $user, Array $params = [])
    {
        // 'UserController::index' => 'UserPolicies'
        $this->auth_class = str_replace('Controller', 'Policies', explode('::', $method)[0]);
        // 'UserController::index' => 'index'
        $this->auth_action = explode('::', $method)[1];

        $this->current_user = $user;
    }

    /**
    * Load related police class
    * @param $obj
    * @return exeption if not access
    */
    public function auth($obj = null)
    {
        $allowed_classes = ['User', 'Contact'];
        if (!in_array(get_class($this->current_user), $allowed_classes)) {
            throw new Exception('Authorized action fail, unexpected object class.', 401);
        }

        $police = new $this->auth_class($this->current_user, $obj);
        $access = $police->{$this->auth_action}();

        if ($access !== true) {
            throw new Exception('Not Authorized Action.', 401);
        }
    }
}
