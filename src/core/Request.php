<?php
class Request
{
    /**
    * Get all params
    * @param Array $params
    * @return Array of parameters
    */
    public static function params($route_params)
    {
        $put = Request::getPutData();
        $json = Request::getJsonData();

        return array_merge($route_params, $_POST, $put, $json, $_GET);
    }

    public static function getPutData()
    {
        // get put data
        $arr_put = [];
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $put_data = file_get_contents('php://input');
            if (Util::isJSON($put_data)) {
                $arr_put = Util::objToArray(json_decode($put_data));
            } else {
                parse_str($put_data, $arr_put);
            }
        }

        return $arr_put;
    }

    // Jesli wyslemy do aplikacji zapytanie POST z nagłowkiem  "Content-Type" rownym "application/json"
    // to dane json nie sa dostepne w tablicy post, trzeba je dekodowac.
    public static function getJsonData()
    {
        $json_data = file_get_contents('php://input');
        if (Util::isJSON($json_data)) {
            $json = json_decode($json_data, true);
        } else {
            $json = [];
        }

        return $json;
    }
}
