<?php
abstract class Controller
{
    public $params;

    function __construct($params)
    {
        $this->params = $params;
    }

    // Authorize controller method
    public function auth($data, $user)
    {
        $authorizator = new Authorize($this->getControllerAndAction(), $user);
        $authorizator->auth($data);
    }

    // Set header status and return data to response (to show)
    public function response($data, $status = 200)
    {
        $data = $this->renderToJson($data, $this->getControllerAndAction());

        return Response::bulid($status, $data);
    }

    // Create array of response data, base on view file template
    public function renderToJson($data, $controller_and_action)
    {
        $json_builder = new JSONBuilder($data, $controller_and_action);
        $response_data = $json_builder->render();

        return json_encode($response_data);
    }

    // for 422 - Unprocessable Entity
    public function errorResponse($data, $status = 422)
    {
        $error = [];

        // handle nested attributes errors
        if (isset($data->errors)) {
          $error['errors'] = $data->errors;
        }

        return $this->customDataResponse($error, $status);
    }

    // For 422 - Unprocessable Entity
    // We return only errors info, but why not return object + errors
    public function errorResponseWithObject($data, $status = 422)
    {
        $data = $this->renderToJson($data, $this->getControllerAndAction());
        return Response::bulid($status, $data);
    }

    // Other custom responses with custom data and custom satatuses
    public function customDataResponse($data = null, $status = 200)
    {
        $response_data = $data == null ? '' : json_encode($data);

        return Response::bulid($status, $response_data);
    }

    // Render html
    public function render(Array $params = [], Array $options = [])
    {
        return (new View($this->params, $params, $options))->render();
    }

    private function getControllerAndAction()
    {
        return get_class($this) . '::' . $this->params['action'];
    }
}
