<?php
trait ParentChildGrandsonMysqlTables
{
    public function createParentChildGrandsonMysqlTables()
    {
        // load class models
        require_once 'tests/fixtures/validator/TesterParentModel.php';
        require_once 'tests/fixtures/validator/TesterChildModel.php';
        require_once 'tests/fixtures/validator/TesterGrandsonModel.php';

        // to test unique validation we need a database table
        $db_setup = 'db_'.Config::get('env');
        MyDB::connect(Config::get($db_setup));

        // we ignore mysl errors i table exist or not (clear)
        // PARENT
        // clear table
        $query = "TRUNCATE `tester_parent_models`";
        mysqli_query(MyDB::db(), $query);

        // create parent table
        $query = "CREATE TABLE `tester_parent_models` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(191) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        )";
        mysqli_query(MyDB::db(), $query);

        // CHILDS
        // clear table
        $query = "TRUNCATE `tester_child_models`";
        mysqli_query(MyDB::db(), $query);

        // create childs table
        $query = "CREATE TABLE `tester_child_models` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tester_parent_model_id` int(11) NOT NULL,
            `address` varchar(191) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        )";
        mysqli_query(MyDB::db(), $query);

        // GRANDSON
        // clear table
        $query = "TRUNCATE `tester_grandson_models`";
        mysqli_query(MyDB::db(), $query);

        // create grandson table
        $query = "CREATE TABLE `tester_grandson_models` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tester_child_model_id` int(11) NOT NULL,
            `description` varchar(191) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        )";
        mysqli_query(MyDB::db(), $query);
    }

    public function dropDownParentChildGrandsonMysqlTables()
    {
        $query = "DROP TABLE `tester_grandson_models`";
        mysqli_query(MyDB::db(), $query);

        $query = "DROP TABLE `tester_child_models`";
        mysqli_query(MyDB::db(), $query);

        $query = "DROP TABLE `tester_parent_models`";
        mysqli_query(MyDB::db(), $query);
    }
}
