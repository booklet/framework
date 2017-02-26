<?php
trait BasicORM2
{

    private static $builder = null;
    private static $instance = null;


    /**
    * Get all records from database
    * @return array of objects
    */
    public static function orm2_all(Array $params = [])
    {
        self::initializeBuilder();

        // set all query
        self::$builder->select();

#        // na podstawie modelu wygeneruj zapytanie sql
#        // add scopes from model
#
#        $query->table('users')->
#
#        all  inicializuje $_instance
#        find inicializuje $_instance
#        findBy inicializuje $_instance
#        where inicializuje $_instance co z multiple where
#        first  moze inicializowac ale takze byc wywolanze na obiekcie
#        last
#        paginate  limity i ofsety
#        orderBy
#        pluck
#        chunk !!
#        count
#        offset(10)
#        limit(5)

#        insert
#        update
#        delete

#        get
#
#        $affectedRows = User::where('votes', '>', 100)->delete();
#        $user->touch(); // update time stamp
#
#        $orm = new MysqlORM(MyDB::db(), self::getModelInstance());
#        $results = $orm->where('', [], $params);

#        return $results;

        return self::$instance;
    }

    public static function orm2_find($id)
    {
        self::orm2_findBy('id', $id);

        return self::$instance;
    }

    public static function orm2_findBy($field, $value)
    {
        self::initializeBuilder();

        self::orm2_where($field . ' = ?', [$value]);
        self::$builder->limit(1);

        return self::$instance;
    }

    public static function orm2_where($query, Array $params = [])
    {
        self::initializeBuilder();

        self::$builder->select();
        self::$builder->where($query, $params);

        return self::$instance;
    }

    public static function orderBy($column, $direction)
    {
        self::$builder->orderBy($column, $direction);

        return self::$instance;
    }

    public static function get()
    {
        return self::$builder->get();
    }

    public static function toSql()
    {
        return self::$builder->toSql();
    }



    private static function initializeBuilder()
    {
        if (self::$builder == null) {
            self::$builder = new ORMQueryBuilder(self::getModelInstance());
        }
        if (self::$instance == null) {
            self::$instance = self::$instance = self::getModelInstance();
        }
    }
}
