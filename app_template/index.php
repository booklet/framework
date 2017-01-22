<?php
try {
    // Apliaction setup
    include_once 'config/autoload.php';
    include_once 'config/application.php';

    // Routing
    include_once 'config/routing.php';

    if (!$match) {
        echo Response::raiseError(404, ['Resource not found.']);
        die();
    }

    // Connect to mysql database
    // TODO
    //$db_setup = 'db_'.Config::get('env');
    //MyDB::connect(Config::get($db_setup));

    // Run Controller#action
    if ($match) {
        // $match from 'app/core/router.php'
        // $match['target'] => UserController#index

        // merge all params $match $get $post $put
        $params = Request::params($match['params']);

        list($controller_name, $action_name) = explode('#', $match['target']);
        $controller = new $controller_name($params);
        $body_response = $controller->$action_name();

        echo $body_response;
    }
} catch (Throwable $t) {
    if ($t->getCode() != 0) {
        $error_code = $t->getCode();
    } else {
        $error_code = 500;
    }

    echo Response::raiseError($error_code, [$t->getMessage()]);
}
