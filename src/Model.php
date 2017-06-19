<?php
abstract class Model
{
    use BasicORM;
    use BasicORM2;

    /**
     * Contains model values as column_name => value
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Flag whether or not this model's attributes have been modified since
     * it will either be null or an array of column_names that have been modified
     *
     * @var array
     */
    private $__dirty = null;

    function __construct(array $attributes = [])
    {
        // Setup default model values
        foreach ($this->fields() as $name => $value) {
            // Use assignAttribute to not call any callbacks
            $this->assignAttribute($name, $value['default'] ?? null);
        }

        // Assign object attributes when create
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        // Initialize object relations if passed
    }

    /**
    * Throw exception if property not exist, else set property
    *
    * class User extends Model {
    *   # define custom setter methods. Note you must
    *   # prepend set_ to your method name:
    *   function set_password($plaintext) {
    *     $this->encrypted_password = md5($plaintext);
    *   }
    * }
    *
    * $user = new User();
    * $user->password = 'plaintext';  # will call $user->set_password('plaintext')
    *
    * If you define a custom setter with the same name as an attribute then you
    * will need to use assignAttribute() to assign the value to the attribute.
    * This is necessary due to the way __set() works.
    *
    * class User extends Model {
    *   # INCORRECT way to do it
    *   # function set_name($name) {
    *   #   $this->name = strtoupper($name);
    *   # }
    *
    *   function set_name($name) {
    *     $this->assignAttribute('name',strtoupper($name));
    *   }
    * }
    */
    public function __set($name, $value)
    {
        $allowed_propertis = $this->allowedPropertis();
        if (!in_array($name, $allowed_propertis)) {
            throw new Exception(get_called_class() . " does not have '" . $name . "' property.");
        }

        // Set method from model if exists
        if (method_exists($this, "set_$name")) {
            $name = "set_$name";
            return $this->$name($value);
        }

        return $this->assignAttribute($name, $value);
    }

    /**
    * Magic method which delegates to readAttribute().
    *
    * You can also define customer getter methods for the model.
    *
    * EXAMPLE:
    * class User extends ActiveRecord\Model {
    *   # define custom getter methods. Note you must
    *   # prepend get_ to your method name:
    *   function get_middle_initial() {
    *     return $this->middle_name{0};
    *   }
    * }
    *
    * $user = new User();
    * echo $user->middle_name;  # will call $user->get_middle_name()
    * </code>
    *
    * If you define a custom getter with the same name as an attribute then you
    * will need to use readAttribute() to get the attribute's value.
    * This is necessary due to the way __get() works.
    *
    * For example, assume 'name' is a field on the table and we're defining a
    * custom getter for 'name':
    *
    * class User extends ActiveRecord\Model {
    *   # INCORRECT way to do it
    *   # function get_name() {
    *   #   return strtoupper($this->name);
    *   # }
    *
    *   function get_name() {
    *     return strtoupper($this->readAttribute('name'));
    *   }
    * }
    *
    * $user = new User();
    * $user->name = 'bob';
    * echo $user->name; # => BOB
    */
    public function &__get($name)
    {
        // Check for getter
        if (method_exists($this, "get_$name")) {
            $name = "get_$name";
            $value = $this->$name();
            return $value;
        }

        return $this->readAttribute($name);
    }

    /**
     * TODO After reorganize model nested attributes remove this function
     */
    public function __unset($name)
    {
        unset($this->attributes[$name]);
    }

    /**
     *
     */
    public function &readAttribute($name)
    {
        // Check for attribute
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        # // check relationships if no attribute
        # if (array_key_exists($name,$this->__relationships))
        #   return $this->__relationships[$name];

        throw new Exception(get_called_class() . " does not have '" . $name . "' property.");
    }

    /**
     * Determines if an attribute exists for this Model
     * isset($myObj->item) call this function
     */
    public function __isset($attribute_name)
    {
        return array_key_exists($attribute_name, $this->attributes);
    }

    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Assign a value to an attribute.
    */
    public function assignAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        $this->flagDirty($name);
        return $value;
    }

    /**
     * Flags an attribute as dirty.
     *
     * @param string $name Attribute name
     */
    public function flagDirty($name)
    {
        if (!$this->__dirty) {
            $this->__dirty = array();
        }

        $this->__dirty[$name] = true;
    }

    /**
     * Returns hash of attributes that have been modified since loading the model.
     *
     * @return mixed null if no dirty attributes otherwise returns array of dirty attributes.
     */
    public function dirtyAttributes()
    {
        if (!$this->__dirty) {
            return null;
        }
        $dirty = array_intersect_key($this->attributes, $this->__dirty);

        return !empty($dirty) ? $dirty : null;
    }

    /**
     * Check if a particular attribute has been modified since loading the model.
     * @param string $attribute  Name of the attribute
     * @return boolean TRUE if it has been modified.
     */
    public function attributeIsDirty($attribute)
    {
        return $this->__dirty && isset($this->__dirty[$attribute]) && array_key_exists($attribute, $this->attributes);
    }

    /**
    * Object is valid
    * @return true/false
    */
    public function isValid(array $params = [])
    {
        // callback function beforeValidate()
        if (method_exists($this, 'beforeValidate')) {
            $this->beforeValidate();
        }

        // TODO
        // in uniqes validator rule validator nedd db connection to check database
        // that not good, so we need to pass this database records as validator params
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
            $type_validator = 'type:' . $value['type'];
            array_push($validations_rules, $type_validator);

            // custom validators
            // required, lenght, uniques, etc
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

    // Catch relations methods
    public function __call($name, $args)
    {
        // dynamic generate Push method => $client->categoriesPush($category1);
        if (StringUntils::isInclude($name, 'Push')) {
            $relation = new Relations($this, $name, $args);
            return $relation->habtmPushObjects();

        // dynamic generate Delete method => $client->categoriesDelete($category1);
        } elseif (StringUntils::isInclude($name, 'Delete')) {
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
        $class_name = get_called_class();
        $pluralize_class_name = Inflector::pluralize($class_name);

        return $pluralize_class_name;
    }

    // valid objects passed by items_attribute field
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
        $nested_obj_underscore_class_name = StringUntils::camelCaseToUnderscore(get_class($nested_object));
        foreach ($nested_object->errors as $key => $value) {
            if (!isset($this->errors)) {
                $this->errors = [];
            }
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
        $underscore_class_name = StringUntils::camelCaseToUnderscore(get_called_class());
        $parent_key_name = $underscore_class_name . '_id';
        $params[$parent_key_name] = 0;

        return $params;
    }

    // Return all allowed model propertis
    public function allowedPropertis()
    {
        // allow attributes
        // errors -> store errors validation informations
        // oryginal_record -> clone object attributes when get object from database
        // _destroy -> use to delete object
        $allowed_propertis = ['errors', 'oryginal_record', '_destroy'];

        // declarative attributes in model
        foreach ($this->fields() as $key => $value) {
            $allowed_propertis[] = $key;
        }

        // custom model allowed attributes
        if (method_exists($this, 'specialPropertis')) {
            $allowed_propertis = array_merge($allowed_propertis, $this->specialPropertis());
        }

        // allow nested attributes params
        if (method_exists($this, 'acceptsNestedAtributesFor')) {
            foreach ($this->acceptsNestedAtributesFor() as $attr) {
                $allowed_propertis[] = $attr . '_attributes';
            }
        }

        // allow habtm ids array
        if (method_exists($this, 'relations')) {
            foreach ($this->relations() as $relation_name => $relation_params) {
                if ($relation_params['relation'] == 'has_and_belongs_to_many'){
                    $allowed_propertis[] = $relation_name . '_ids';
                }
            }
        }

        return $allowed_propertis;
    }
}
