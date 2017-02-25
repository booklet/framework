<?php
trait BasicORM
{
    /**
    * Get all records from database
    * @return array of objects
    */
    public static function all(Array $params = [])
    {
        // na podstawie modelu wygeneruj zapytanie sql

        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where('', [], $params);

        return $results;
    }

    /**
    * Get record from database base on id
    * @param Integer $id
    * @return object
    */
    public static function find($id, Array $params = [])
    {
        $params['limit'] = 1;

        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where('id = ?', ['id' => $id], $params);

        if (empty($results)) {
            throw new Exception('Couldn\'t find '.get_called_class().' with id='.$id, 404);
        } else {
            return $results[0]; // return single object, not array of one object
        }
    }

    /**
    * Get record by field
    * @param String $attribute
    * @param String $value
    * @return Array of objects
    */
    public static function findBy($attribute, $value, Array $params = [])
    {
        $params['limit'] = 1;

        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where($attribute.' = ?', [$attribute => $value], $params);

        if (empty($results)) {
            return null;
        } else {
            return $results[0]; // return single object, not array of one object
        }
    }

    /**
    * Get record by field
    * @param String $query
    * @param Array $fileds
    * @return Array of objects
    */
    public static function where($query, Array $fileds = [], Array $params = [])
    {
        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where($query, $fileds, $params);

        return $results;
    }

    /**
    * Get first record
    * @param Array $params
    * @return object
    */
    public static function first(Array $params = [])
    {
        $params['limit'] = 1;

        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where('', [], $params);

        if (empty($results)) {
            return null;
        } else {
            return $results[0]; // return single object, not array of one object
        }
    }

    /**
    * Get last record
    * @param Array $params
    * @return object
    */
    public static function last(Array $params = [])
    {
        $params['limit'] = 1;
        $params['order'] = 'id DESC';

        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
        $results = $orm->where('', [], $params);

        if (empty($results)) {
            return null;
        } else {
            return $results[0]; // return single object, not array of one object
        }
    }

    /**
    * Qustom sql query
    * @param Array $params
    * @return object
    */
    public static function sql($query, Array $fileds, Array $params = [])
    {
        $extra_params = self::extra_params($params);
        self::$class = get_called_class();
        $class_pluralize_name = (new self::$class)->pluralizeClassName();
        self::$table = StringUntils::camelCaseToUnderscore($class_pluralize_name);

        self::$query = MyDB::db()->prepare($sql);
        self::bindParams($fileds);
        $objects = self::run_query_get_results_objects();

        return $objects;
    }

    /**
    * Save model in database
    */
    public function save(Array $params = [])
    {
        $orm = new MysqlORM(MyDB::db(), $this);

        return $orm->save($params);
    }

    /**
    * Update database record
    */
    public function update(Array $attributes)
    {
        // actualize object with new params
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        return $this->save();
    }

    /**
    * Destroy database record
    */
    public function destroy()
    {
        $orm = new MysqlORM(MyDB::db(), $this);

        return $orm->destroy();
    }

    /**
    * Create
    */
    public static function create(Array $attributes)
    {
        $object = self::getModelInstance();

        foreach ($attributes as $key => $value) {
            $object->$key = $value;
        }

        return $object->save();
    }

    /**
    * Get current model instance
    */
    private static function getModelInstance()
    {
        $model_class = get_called_class();

        return new $model_class;
    }
}
