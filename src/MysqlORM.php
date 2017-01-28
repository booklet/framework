<?php
class MysqlORM
{
    private $db_connection;
    private $model_obj;
    private $model_class_name;
    private $table_name;

    function __construct($db_connection, $model_obj)
    {
        $this->db_connection = $db_connection;
        $this->model_obj = $model_obj;
        $this->model_class_name = get_class($model_obj);
        $this->table_name = $this->tableName($model_obj);
    }

    /**
    * Buildit query statement and return objects
    */
    public function where($query, Array $fileds = [], Array $params = [])
    {
        $extra_params = $this->extraParams($params);

        // buildit query
        $query_string = "SELECT `" . $this->table_name."`.* FROM `" . $this->table_name . "`";
        if ($query != '') {
            $query_string .= " WHERE " . $query;
        }
        $query_string .= $extra_params;

        $query_statement = $this->db_connection->prepare($query_string);

        if (!$query_statement) {
            // handle error
            die("Złe zapytanie 1");
        }

        $query_statement = $this->bindQueryParams($query_statement, $fileds);
        $objects = $this->runQueryGetResultsObjects($query_statement);

        return $objects;
    }

    /**
    * Save/update model in database
    */
    public function save(Array $params = [])
    {
        // exit if not valid model
        if (!$this->model_obj->isValid()) { return false; }

        $db_obj = $this->createDbObject();
        $db_obj = $this->setTimestamps($db_obj);

        // buildit query
        if ($this->model_obj->isNewRecord()) {
            $query_statement = $this->builditNewRecordQuery($db_obj);
        } else {
            $query_statement = $this->builditUpdateRecordQuery($db_obj);
        }

        // callback before save
        if (method_exists($this->model_obj, 'beforeSave')) { $this->model_obj->beforeSave(); }

        $query_statement = $this->bindQueryParams($query_statement, $db_obj);

        if (!$query_statement->execute()) {
            return false;
        } else {
            // when create new record, update object id and time stamp
            if ($this->model_obj->isNewRecord()) {
                $this->model_obj->id = $query_statement->insert_id;
                $this->model_obj->created_at = $db_obj->created_at;
            }
            $this->model_obj->updated_at = $db_obj->updated_at;

            // check if nested attributes
            $this->saveNestedObjects();

            // save habtm ids
            $this->saveHABTMIdsObjects();

            // callback after save
            if (method_exists($this->model_obj, 'afterSave')) { $this->model_obj->afterSave(); }

            return true;
        }
    }

    /**
    * Generate params to sql query
    */
    public function extraParams(Array $params)
    {
        $sql = '';

        // ORDER ['order'=>'created_at DESC']
        if (isset($params['order'])) {
            $sql .= ' ORDER BY ' . $params['order'];
        }

        // LIMIT ['limit'=>10, 'page'=>2]
        if (isset($params['limit'])) {
            $page = (int)($params['page'] ?? 1);
            $limit = $params['limit'];
            $startpoint = ($page * $limit) - $limit;
            $sql .= ' LIMIT ' . $startpoint . ', ' . $limit;
        }

        return $sql;
    }

    /**
    *
    */
    private function runQueryGetResultsObjects($query_statement) {
        $query_statement->execute();
        $result = $query_statement->get_result();
        $objects = $this->createObjects($result);
        $query_statement->free_result();

        return $objects;
    }

    /**
    *
    */
    private function createObjects($result)
    {
        $objects = [];

        while ($row = $result->fetch_assoc()) { // only return one row each time it is called
            $obj = new $this->model_class_name;

            foreach ($row as $key => $value) {
                $obj->$key = $value;
            }

            // store in oryginal_record attribute oryginal object attributes
            // to detect what attributes change and buildit update query
            // only for this who change
            $oryg = clone $obj;
            $obj->oryginal_record = [];

            foreach ($oryg as $key => $value) {
                $obj->oryginal_record[$key] = $value;
            }

            unset($obj->oryginal_record['id']);
            unset($obj->oryginal_record['created_at']);
            unset($obj->oryginal_record['updated_at']);

            $objects[] = $obj;
        }

        return $objects;
    }

    /**
    * Create and execute delete query
    */
    public function destroy()
    {
      if (!isset($this->model_obj->id)) {
          throw new Exception("The object was not saved in the database, so you can not delete it.");
      }

      $query = MyDB::db()->prepare("DELETE FROM `" . $this->table_name . "` WHERE `id` = ?");
      $query->bind_param("i", $this->model_obj->id);

      return $query->execute() ? true : false;
    }

    /**
    * Get model database table name
    */
    private function tableName($model_obj)
    {
        $class_pluralize_name = $model_obj->pluralizeClassName();

        return Util::camelCaseStringToUnderscore($class_pluralize_name);
    }

    /**
    * Create and eval bind statement command
    */
    private function bindQueryParams($query, $db_obj)
    {
        // buldit bind params
        $type = [];
        $data = [];
        foreach ($db_obj as $param => $value) {
            // i  corresponding variable has type integer
            // d  corresponding variable has type double
            // s  corresponding variable has type string
            // b  corresponding variable is a blob and will be sent in packets
            $t = 's';
            if ($this->model_obj->fields()[$param] == 'integer') { $t = 'i'; }
            if ($this->model_obj->fields()[$param] == 'float') { $t = 'd'; }
            if ($this->model_obj->fields()[$param] == 'double') { $t = 'd'; }
            if ($this->model_obj->fields()[$param] == 'blob') { $t = 'b'; }
            if ($this->model_obj->fields()[$param] == 'text') { $t = 's'; }
            if ($this->model_obj->fields()[$param] == 'datetime') { $t = 's'; }
            $type[] = $t;

            if (is_array($db_obj)) {
              $data[] = '$db_obj[\'' . $param.   '\']';
            } else {
              $data[] = '$db_obj->' . $param;
            }
        }

        if (empty($data)) { return $query; }

        // if update, add id
        if (!$this->model_obj->isNewRecord()) {
            $type[] = 'i';
            $data[] = '$this->model_obj->id';
        }

        $sql_stmt = '$query->bind_param(\'' . implode('',$type) . '\', ' . implode(', ',$data) . ');'; // put bind_param line together
        eval($sql_stmt); // execute bind_param

        return $query;
    }

    /**
    * Create sql query for new record
    */
    private function builditNewRecordQuery($db_obj)
    {
        // obj => "`name`, `name_search`, `created_at`, `updated_at`"
        $parameters = ObjectUntils::mysqlParameters($db_obj);
        // obj => "?, ?, ?, ..."
        $parameters_values_placeholder = ObjectUntils::mysqlParametersValuesPlaceholder($db_obj);
        $query = MyDB::db()->prepare("INSERT INTO `" . $this->table_name . "` (" . $parameters . ") VALUES (" . $parameters_values_placeholder . ")");

        if (!$query) {
            // handle error
            die("Złe zapytanie 2");
        } else {
            return $query;
        }
    }

    /**
    * Create sql query for update record
    */
    private function builditUpdateRecordQuery($db_obj)
    {
        // "UPDATE MyGuests SET lastname='Doe' WHERE id=2"
        $parameters = ObjectUntils::mysqlParametersUpdate($db_obj);
        $query = MyDB::db()->prepare("UPDATE `" . $this->table_name . "` SET " . $parameters . " WHERE `id`=?");

        if (!$query) {
            // handle error
            die("Złe zapytanie 3");
        } else {
            return $query;
        }
    }

    /**
    * Set created_at timestamp when create record
    * Set updated_at any time when update record
    */
    private function setTimestamps($obj)
    {
        if ($this->model_obj->isNewRecord() and !isset($obj->created_at)) {
            $obj->created_at = date(Config::get('mysqltime'));
        }
        $obj->updated_at = date(Config::get('mysqltime'));

        return $obj;
    }

    /**
    * Create new object used to buldit query
    * Filter object attributes, leave only present and database exist params
    */
    public function createDbObject()
    {
        $db_obj = new stdClass();
        if ($this->model_obj->isNewRecord()) {
            foreach ($this->model_obj->fields() as $attr => $type) {
                if ($this->model_obj->$attr !== null) {
                    $db_obj->$attr = $this->model_obj->$attr;
                }
            }
        } else {
            // if object save and update, not get from databse, object dont have oryginal_record attrib
            if (isset($this->model_obj->oryginal_record)) {
                // Update only fields that changes
                foreach ($this->model_obj->oryginal_record as $attr => $val) {
                    if ($val != $this->model_obj->$attr)
                        $db_obj->{$attr} = $this->model_obj->$attr;
                }
            }
        }

        return $db_obj;
    }

    private function saveNestedObjects()
    {
        // check if object has declared nested attributes
        if (!method_exists($this->model_obj, 'acceptsNestedAtributesFor')) {
            return;
        }

        $nested_objects_params = $this->model_obj->getNestedAttributesPrams();

        // loop current object nested atributes
        foreach ($nested_objects_params as $nested_object_param) {

            foreach ($this->model_obj->{$nested_object_param['wrapper_name']} as $index => $item) {

                // if object has id, then update/delete
                if (isset($item['id'])) {

                    // find element to update
                    $nested_obj = $nested_object_param['objects_class_name']::find($item['id']);

                    // update or destroy
                    if (isset($item['_destroy']) and $item['_destroy'] == 1) {
                        $nested_obj->destroy();
                    } else {
                        $new_params = $item;
                        // dont update this params:
                        unset($new_params['id']);
                        unset($new_params['created_at']);
                        unset($new_params['updated_at']);

                        if (!$nested_obj->update($new_params)) {
                            $this->model_obj->saveErrorsInParentObject($nested_object_param['attribute_name'], $index, $nested_obj);
                        }
                    }
                } else {
                    $params = $item;
                    $underscore_class_name = Util::camelCaseStringToUnderscore($this->model_class_name);
                    $parent_key_name = $underscore_class_name . '_id';
                    $params[$parent_key_name] = $this->model_obj->id;

                    $nested_obj = new $nested_object_param['objects_class_name']($params);

                    if (!$nested_obj->save()) {
                        $this->saveErrorsInParentObject($nested_object_param['attribute_name'], $index, $nested_obj);
                    }
                }
            }

            if (!empty($this->model_obj->errors)) {
                return false;
            }
        }
    }

    private function saveHABTMIdsObjects()
    {
        if (!method_exists($this->model_obj, 'relations')) { return; }

        // get habtm relations from current object and ids array
        $habtm_relations = [];

        foreach ($this->model_obj->relations() as $relation_key => $relation_params) {
            if ($relation_params['relation'] == 'has_and_belongs_to_many' and isset($this->model_obj->{$relation_key . '_ids'})) {
                $habtm_relations[$relation_key] = $relation_params;
            }
        }

        foreach ($habtm_relations as $relation_key => $relation_params) {
            // get current habtm objects ids
            $current_ids = array_map(function ($o) { return $o->id; }, $this->model_obj->$relation_key());

            // what if pass empty array
            $passed_ids = $this->model_obj->{$relation_key . '_ids'};

            // convert empty array passed as string '[]' to array object
            // http_build_query used in test request remove empty arrays
            if ($passed_ids == '[]') { $passed_ids = []; }

            // remove empty items (last field is always empty for deleting purpose)
            $passed_ids = array_filter($passed_ids);

            // add items
            $ids_to_add = array_diff($passed_ids, $current_ids);
            foreach ($ids_to_add as $id) {
                $push_method_name = $relation_key . 'Push';

                $item = $relation_params['class']::find($id);
                $this->model_obj->$push_method_name($item);
            }

            // remove items
            $ids_to_remove = array_diff($current_ids, $passed_ids);
            foreach ($ids_to_remove as $id) {
                $delete_method_name = $relation_key . 'Delete';

                $item = $relation_params['class']::find($id);
                $this->model_obj->$delete_method_name($item);
            }
        }
    }
}
