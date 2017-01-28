<?php
abstract class Model
{
    use BasicORM;

    function __construct(Array $attributes = [])
    {
        // first setup default values
        foreach ($this->fields() as $key => $value) {
            $this->$key = $value['default'];
        }

        // assign object attributes/parameters when create
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
    * Throw exception if property not exist, else set property
    * @param $name
    * @return $value
    */
    public function __set($name, $value)
    {
        // allow attributes
        // errors -> store errors validation informations
        // oryginal_record -> clone object attributes when get object from database
        // _destroy ->
        $specialPropertis = ['errors', 'oryginal_record', '_destroy'];

        // custom model allowed attributes
        if (method_exists($this, 'specialPropertis')) {
            $specialPropertis = array_merge($specialPropertis, $this->specialPropertis());
        }

        // allow nested attributes params
        if (method_exists($this, 'acceptsNestedAtributesFor')) {
            foreach ($this->acceptsNestedAtributesFor() as $attr) {
                $specialPropertis[] = $attr . '_attributes';
            }
        }

        // allow habtm ids array
        if (method_exists($this, 'relations')) {
            foreach ($this->relations() as $relation_name => $relation_params) {
                if ($relation_params['relation'] == 'has_and_belongs_to_many'){
                    $specialPropertis[] = $relation_name . '_ids';
                }
            }
        }

        if ((property_exists(get_class($this), $name) === false) && !in_array($name, $specialPropertis)) {
            throw new Exception(get_class($this) . " does not have '" . $name . "' property.");
        } else {
            $this->$name = $value;
        }
    }

    /**
    * Object is valid
    * @return true/false
    */
    public function isValid(Array $params = [])
    {
        // callback function beforeValidate()
        if (method_exists($this, 'beforeValidate')) { $this->beforeValidate(); }

        $validation = new Validator($this, $this->validationRules(), $params);
        $validation->isValid();

        if ($this->validNestedObjects()) {
            // object OK
        }  else {
            // save errors
        }

        if (empty($this->errors)) {
            return true;
        }

        // przezucic bledy do wyzszego obiektu?
        return false;
    }

    /**
    * Extact validation rules form fields array
    * @return Array $rules
    */
    public function validationRules()
    {
        $rules = [];
        $fields = $this->fields();
        foreach ($fields as $key => $value) {
            // field type validator
            $validations_rules = [];
            $type_validator = 'type:'.$value['type'];
            array_push($validations_rules, $type_validator);

            // custom validators
            if (isset($value['validations'])) {
                $validations_rules = array_merge($validations_rules, $value['validations']);
            }
            $rules[$key] = $validations_rules;
        }
        return $rules;
    }

    /**
    * Chcek if object is new object/record
    * @return true/false
    */
    public function isNewRecord()
    {
        return $this->id == null ? true : false;
    }

    // catch relations methods
    public function __call($name, $args)
    {
        // dynamic generate Push method => $client->categoriesPush($category1);
        if (Util::isStringInclude($name, 'Push')) {
            $relation = new Relations($this, $name, $args);
            return $relation->habtmPushObjects();

        // dynamic generate Delete method => $client->categoriesDelete($category1);
      } elseif (Util::isStringInclude($name, 'Delete')) {
            $relation = new Relations($this, $name, $args);
            return $relation->habtmDeleteObjects();

        // dynamic generate relations methods => $client->categories();
        } elseif (Relations::isRelationMethod($this, $name)) {
            $relation = new Relations($this, $name, $args);
            return $relation->getRelationsObjects();

        // if not Push, Delete or relation
        } else {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
        }
    }

    public function pluralizeClassName()
    {
        // custom pluralize class name setup in model
        if (method_exists($this, 'customPluralizeClassName')) {
            return $this->customPluralizeClassName();
        }

        // pluralize by add s on the end
        $pluralize_class_name = get_class($this) . 's';

        return $pluralize_class_name;
    }

    private function validNestedObjects()
    {
        // check if object has declared nested attributes
        if (!method_exists($this, 'acceptsNestedAtributesFor')) {
            return;
        }

        $nested_objects_params = $this->getNestedAttributesPrams();

        // loop current object nested atributes
        foreach ($nested_objects_params as $nested_object_param) {

            foreach ($this->{$nested_object_param['wrapper_name']} as $index => $item) {

                // if object has id, then update/delete
                if (isset($item['id'])) {
                    // check if ID contains in parent object children
                    // for security reason, if user manipulate ids in form
                    $children_objects = $this->{$nested_object_param['attribute_name']}();
                    $children_ids = array_map(function($o) { return $o->id; }, $children_objects);
                    if (!empty($children_objects) && !in_array($item['id'], $children_ids)) {
                        $this->errors[$nested_object_param['attribute_name'] . '[' . $index . '].' . 'id'] = ['Item not belongs to this parent.'];
                        continue;
                    }

                    // update or destroy
                    if (isset($item['_destroy']) and $item['_destroy'] == 1) {
                        // if to destroy, do not valid
                    } else {
                        // find element to update
                        $nested_obj = $nested_object_param['objects_class_name']::find($item['id']);

                        // update object with new params
                        foreach ($item as $key => $value) {
                            $nested_obj->$key = $item[$key];
                        }

                        if (!$nested_obj->isValid()) {
                            $this->saveErrorsInParentObject($nested_object_param['attribute_name'], $index, $nested_obj);
                        }
                    }
                } else {
                    $params = $item;
                    $params = $this->addFakeParentId($params);

                    $nested_obj = new $nested_object_param['objects_class_name']($params);

                    $unique_attribs_values = $this->getUniqueAttributesValues($nested_obj, $nested_object_param['wrapper_name']);

                    if (!$nested_obj->isValid(['unique_attribs' => $unique_attribs_values])) {
                        $this->saveErrorsInParentObject($nested_object_param['attribute_name'], $index, $nested_obj);
                    }
                }
            }

            if (!empty($this->errors)) {
                return false;
            }
        }
    }

    public function getNestedAttributesPrams()
    {
        $nested_attributes = [];
        // loop current object accepts nested atributes
        foreach ($this->acceptsNestedAtributesFor() as $attribute_name) {
            $nested_attribute_object_name = $attribute_name . '_attributes';
            if (isset($this->$nested_attribute_object_name)) {
                $data = [];
                $data['attribute_name'] = $attribute_name;
                $data['wrapper_name'] = $nested_attribute_object_name;
                $data['objects_class_name'] = $this->relations()[$attribute_name]['class'];
                $nested_attributes[] = $data;
            }
        }
        return $nested_attributes;
    }

    private function saveErrorsInParentObject($attribute_name, $index, $nested_object)
    {
        $nested_obj_underscore_class_name = Util::camelCaseStringToUnderscore(get_class($nested_object));
        foreach ($nested_object->errors as $key => $value) {
            $this->errors[$attribute_name . '[' . $index . '].' . $key] = $value;
        }
    }

    private function getUniqueAttributesValues($nested_object, $nested_object_wrapper_name)
    {
        $unique_attribs = [];
        // get fields that required unique validation
        foreach ($nested_object->validationRules() as $attribute => $rules) {
            foreach ($rules as $rule) {
                if ($rule == 'unique') {
                    $unique_attribs[] = $attribute;
                }
            }
        }

        // get values from that fields
        $unique_attribs_values = [];
        foreach ($unique_attribs as $unique_attrib) {
            $unique_items = [];

            foreach ($this->$nested_object_wrapper_name as $nested_object_arr) {
                $unique_items[] = $nested_object_arr[$unique_attrib];
            }

            if (!empty($unique_items)) {
                $unique_attribs_values[$unique_attrib] = $unique_items;
            }
        }

        return $unique_attribs_values;
    }

    // add fake parent id to pass parent required id validation
    private function addFakeParentId($params)
    {
        $underscore_class_name = Util::camelCaseStringToUnderscore(get_class($this));
        $parent_key_name = $underscore_class_name . '_id';
        $params[$parent_key_name] = 0;

        return $params;
    }
}
