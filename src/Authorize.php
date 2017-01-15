<?php
class Authorize
{
    public $auth_class;
    public $auth_action;
    public $current_user;

    /**
    *
    * @param $method => 'UserController::index'
    * @param Array $params
    */
    function __construct($method, array $params = [])
    {
        // 'UserController::index' => 'UserPolicies'
        $this->auth_class = str_replace("Controller", "Policies", explode('::', $method)[0]);
        // 'UserController::index' => 'index'
        $this->auth_action = explode('::', $method)[1];

        $this->current_user = $params['user'] ?? CurrentUser::fetch();
    }

    /**
    * Load related police class
    * @param $obj
    * @return exeption or
    */
    public function auth($obj=null)
    {
        if (!($this->current_user instanceof User)) {
            echo Response::raiseError(401, ['Not Authorized Action.']);
            die();
        }

        $police = new $this->auth_class($this->current_user, $obj);
        $access = $police->{$this->auth_action}();

        if ($access !== true) {
            echo Response::raiseError(401, ['Not Authorized Action.']);
            die();
        }
    }
}
