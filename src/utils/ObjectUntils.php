<?php
class ObjectUntils
{
    /**
    * Object parameters to mysql string
    * @return string "`name`, `name_search`, `created_at`, `updated_at`"
    */
    public static function mysqlParameters($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '`' . $key . '`';
        }

        return implode(', ' ,$params);
    }

    /**
    * Object parameters to mysql string
    * @return string "`name`=?, `name_search=?`, `created_at=?`, `updated_at=?`"
    */
    public static function mysqlParametersUpdate($obj) {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '`' . $key . '`=?';
        }

        return implode(', ', $params);
    }

    public static function parameters($obj)
    {

    }

    /**
    * Get object values as array
    * @param object ([name] => 'Kowalski' [email] => 'k.kowalski.example.com' [telefon] => '888 888 88')
    * @return array ['Kowalski', 'k.kowalski.example.com', '888 888 88']
    */
    public static function mysqlParametersValuesArray($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = $value;
        }

        return $params;
    }

    /**
    * Return atributes as coma separated
    * @param object ([name] => 'Kowalski' [email] => 'k.kowalski.example.com' [telefon] => '888 888 88')
    * @return array "name, email, telefon"
    */
    public static function mysqlParametersValues($obj)
    {
        if (!is_object($obj)) { return false; }

        $arr = ObjectUntils::mysqlParametersValuesArray($obj);

        return implode(", ", $arr);
    }

    /**
    * Retrun question marks coma sparated
    * @param object ([name] => 'Kowalski' [email] => 'k.kowalski.example.com' [telefon] => '888 888 88')
    * @return array "?, ?, ?"
    */
    public static function mysqlParametersValuesPlaceholder($obj)
    {
        if (!is_object($obj)) { return false; }

        $params = [];
        foreach ($obj as $key => $value) {
            $params[] = '?';
        }

        return implode(', ', $params);
    }

    /**
    * Convert object to array
    */
    public static function objToArray($obj)
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
