<?php
class Response
{
    // set header and return body content
    public static function bulid($status = 200, $body, Array $headers = [])
    {
        if (Config::get('env') != 'test') {
            header('Content-Type: application/json');
        }
        http_response_code($status);

        return $body;
    }

    public static function raiseError($status, Array $errors)
    {
        if (Config::get('env') != 'test') {
            header('Content-Type: application/json');
        }
        http_response_code($status);

        $e = [];
        $e['errors'] = [];
        foreach ($errors as $error) {
            $e['errors'][] = ['message'=>$error];
        }

        return json_encode($e);
    }
}
