<?php
class ObjectUntils
{
    // object parameters to mysql string => "`name`, `name_search`, `created_at`, `updated_at`"
    public static function mysqlParameters($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '`' . $key . '`';
        }

        return implode(", ",$params);
    }

    // object parameters to mysql string => "`name`=?, `name_search=?`, `created_at=?`, `updated_at=?`"
    public static function mysqlParametersUpdate($obj) {

        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '`' . $key . '`=?';
        }

        return implode(", ", $params);
    }

    public static function parameters($obj)
    {

    }

    // get object values as array
    // Stdandard Object (
    //   [name] => 'Kowalski'
    //   [email] => 'k.kowalski.example.com'
    //   [telefon] => '888 888 88'
    // )
    // => ['Kowalski','k.kowalski.example.com','888 888 88']
    public static function mysqlParametersValuesArray($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = $value;
        }

        return $params;
    }

    // return atributes as coma separated string => "name, email, telefon, ..."
    public static function mysqlParametersValues($obj)
    {
        if (!is_object($obj)) { return false; }

        $arr = ObjectUntils::mysqlParametersValuesArray($obj);

        return implode(", ", $arr);
    }

    // retrun question marks coma sparated equal to atributes count => "?, ?, ?, ..."
    public static function mysqlParametersValuesPlaceholder($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '?';
        }
        
        return implode(", ", $params);
    }

    // convert object to array
    public static function toArray($obj)
    {
        if (is_array($obj)) {
            return $obj;
        }

        if (is_object($obj)) {
            return json_decode(json_encode($obj), true);
        } else {
            return false;
        }
    }
}
