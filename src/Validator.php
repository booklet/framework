<?php
class Validator
{
    private $errors = [];

    function __construct($obj, $rules, Array $params = [])
    {
        $this->obj = $obj;
        $this->rules = $rules;

        // check unique not only in database but also in passed array
        $this->unique_attribs = $params['unique_attribs'] ?? [];
    }

    public function isValid()
    {
        foreach ($this->rules as $attribute => $rules) {
            foreach ($rules as $rule) {
                $parts = explode(':', $rule);
                $method = $parts[0];

                if (isset($parts[1])) {
                    $params = explode(',', $parts[1]);
                } else {
                    $params = null;
                }

                $this->{$method}($attribute, $params);
            }
        }

        // custom validators in model
        if (method_exists($this->obj, 'customValidation')) {
            $custom_validation_errors = $this->obj->customValidation();
            foreach ($custom_validation_errors as $key => $value) {
                $this->addError($key, $value);
            }
        }

        if (empty($this->errors)) {
            return true;
        }

        // add error to validion object
        $this->obj->errors = $this->errors;
        return false;
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
    * Check if attribute is not blank
    * @param $attr
    */
    private function required($attr)
    {
        if (isset($this->obj->$attr) && trim($this->obj->$attr) !== '') {
            // OK
        } else {
            $this->addError($attr,  'is required.');
        }
    }

    /**
    * Check if attribute is not blank
    * @param $attr
    */
    private function type($attr, $params)
    {
        // if empty field, not validation need
        if (is_null($this->obj->$attr)) { return false; }

        $type = $params[0];
        if ($type == 'integer') {
            if (!($this->obj->$attr == (string)(integer)$this->obj->$attr)) {
                $this->addError($attr, "is not $type type.");
            }
        } else if ($type == 'string' || $type == 'text') {
            if (!is_string($this->obj->$attr)) {
                $this->addError($attr, "is not $type type.");
            }
        } else if ($type == 'double' || $type == 'decimal') {
            if (!is_numeric($this->obj->$attr)) {
                $this->addError($attr, "is not $type type.");
            }
        // do we need '0', '1', false, true, 'false', 'true', 'yes', 'no'?
        } else if ($type == 'boolean') {
            if (!in_array($this->obj->$attr, array('0', '1', 0, 1, true, false), true)) {
                $this->addError($attr, "is not $type type.");
            }
        } else if ($type == 'datetime') {
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $this->obj->$attr, $matches);
            if (empty($matches)) {
                $this->addError($attr, "is not $type type.");
            }
        } else if ($type == 'date') {
            preg_match('/(\d{4})-(\d{2})-(\d{2})/', $this->obj->$attr, $matches);
            if (empty($matches)) {
                $this->addError($attr, "is not $type type.");
            }
        } else {
            $this->addError($attr, 'is unknown data type.');
        }
    }

    /**
    * Check lenght of attribute
    * @param $attr
    * @param Array $params
    */
    private function max_length($attr, $params)
    {
        $max = $params[0];

        // make sure header is correct
        // HTTP-header (Content-Type: text/html; charset=UTF-8)
        if (mb_strlen($this->obj->$attr, 'UTF-8') > $max) {
            $this->addError($attr, 'is too long (max ' . $max . ').');
        }
    }

    /**
    * Check minimal item vaue
    * @param $attr
    * @param Array $params
    */
    private function greater_than_or_equal_to($attr, $params)
    {
        $min = intval($params[0]);

        if ($this->obj->$attr < $min) {
            $this->addError($attr, 'is low value (min ' . (string) $min . ').');
        }
    }

    /**
    * Check if email has valid format
    * @param $attr
    */
    private function email($attr)
    {
        if (ValidateEmail::valid($this->obj->$attr)) {
            // OK
        } else {
            $this->addError($attr, 'email is not valid.');
        }
    }

    /**
    * Check if value is unique
    * @param $attr
    */
    private function unique($attr)
    {
        $class = get_class($this->obj);
        $items = $class::where($attr . ' = ?', [$attr=>$this->obj->$attr]);

        // chekc unique not only in databse but also in passed values
        if (!empty($this->unique_attribs[$attr])) {

            $unique_attrib_attributes = $this->unique_attribs[$attr];
            // Array (
            //     [address] => Array (
            //          [0] =>
            //          [1] => kontakt@com.pl
            //          [2] =>
            //          [3] => test@test.com
            //     )
            // )

            $counted_array_values = array_count_values($unique_attrib_attributes);
            // Array (
            //     [] => 2
            //     [kontakt@com.pl] => 1
            //     [test@test.com] => 1
            // )

            if ($counted_array_values[$this->obj->$attr] > 1) {
                $item = new stdClass();
                $item->id = 'blank';
                $item->$attr = $this->obj->$attr;
                $items[] = $item;
            }
        }

        if (!empty($items)) {
            if (count($items) == 1 && $items[0]->id == $this->obj->id) {
                // after save object any next validation return false,
                // but, this is valid object if ids is equal, so we not add error
            } else {
                $this->addError($attr, 'is not unique.');
            }
        }
    }

    /**
    * Check if attribute is allowed value
    * @param $attr
    * @param Array $allow_values
    */
    private function in($attr, $allow_values)
    {
        if (!in_array($this->obj->$attr, $allow_values)) {
            $this->addError($attr, 'is not allowed value.');
        }
    }

    /**
    * Special validator to use with HasSecurePassword
    * @param $attr
    */
    // TODO move this to HasSecurePassword module
    private function password()
    {
        // if password_digest contains
        // if value start with error
        if (substr($this->obj->password_digest , 0, 5) == 'error') {
            $errors = explode('|', $this->obj->password_digest);
            array_shift($errors); // remove error text
            foreach ($errors as $value) {
                $this->addError('password', $value);
            }
        }
    }

    private function zip_code($attr)
    {
        $is_valid = false;
        $zip_codes = ['[0-9]{2}-[0-9]{3}'];

        foreach ($zip_codes as $zip_code) {
            if (preg_match('/^'.$zip_code.'$/', $this->obj->$attr)) {

                $is_valid = true;
            }
        }

        if (!$is_valid) {
            $this->addError($attr, 'is not zip code.');
        }
    }

    private function addError($attr, $error)
    {
        if (!isset($this->errors[$attr])) {
            $this->errors[$attr] = [];
        }

        $this->errors[$attr][] = $error;
    }
}
