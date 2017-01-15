<?php
abstract class Controller
{
    public $params;

    function __construct($params) {
        $this->params = $params;
    }

    // authorize controller method
    public function auth($data) {
        $authorizator = new Authorize($this->getControllerAndAction());
        $authorizator->auth($data);
    }

    // create array of response data, base on view file template
    public function renderToJson($data, $controller_and_action) {
        $response_body = new JSONBuilder($data, $controller_and_action);

        return $response_body->data;
    }

    // set header status and return data to response (to show)
    public function response($data, $status = 200) {
        $data = $this->renderToJson($data, $this->getControllerAndAction());

        return Response::bulid($status, $data);
    }

    // for 422 - Unprocessable Entity
    public function errorResponse($data, $status = 422) {
        $error = [];

        // handle nested attributes errors
        if (isset($data->errors)) {
          $error['errors'] = $data->errors;
        }

        return $this->customDataResponse($error, $status);
    }

    // for 422 - Unprocessable Entity
    // we return only errors info, but why not return object + errors
    public function errorResponseWithObject($data, $status = 422) {
        $data = $this->renderToJson($data, $this->getControllerAndAction());
        return Response::bulid($status, $data);
    }


    // other custom responses with custom data and custom satatuses
    public function customDataResponse($data = null, $status = 200) {
        $response_data = $data == null ? '' : json_encode($data);

        return Response::bulid($status, $response_data);
    }

    // WARMING
    // use with caution
    private function getControllerAndAction() {
        return get_class($this) . '::' . debug_backtrace()[2]['function'];
    }
}
