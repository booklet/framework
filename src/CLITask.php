<?php
class CLITask
{
    public $action;
    public $action_param;

    public function __construct($args)
    {
        $this->action = $args[1] ?? null;
        $this->action_param = $args[2] ?? null;
        // run tests if no action (parmas)
        if (!$this->action) { $this->testRunAll(); }
    }

    public function dbMigrate()
    {
        echo "\nRun database migrations (" . Config::get('env') . ")\n";
        $runs_migrations_paths = $this->runMigrations();

        foreach ($runs_migrations_paths as $migration_path) {
            echo CLIUntils::colorize("$migration_path\n\n", 'SUCCESS');
        }

        if (empty($runs_migrations_paths)) {
            echo CLIUntils::colorize("All migrations are made\n\n", 'SUCCESS');
        }
    }

    public function dbRollback()
    {
        echo "\nRun database rollback (" . Config::get('env') . ")\n";
        $rollback_status = $this->runRollback();
        echo CLIUntils::colorize($rollback_status['message'] . "\n\n", $rollback_status['status']);
    }

    public function dbSeed()
    {
        MyDB::connect(Config::get('db_development'));
        MyDB::clearDatabaseExceptSchema();
        require_once 'db/seed.php';
        echo CLIUntils::colorize("\nSeeds database (" . Config::get('env') . ") successfully\n\n", 'SUCCESS');
    }

    public function dbPrepare()
    {
        echo "\nClear and run migrations for tests database\n";
        Config::set('env', 'test');
        $this->dropAllTablesAndRecreate();
        $this->runMigrations();
        echo CLIUntils::colorize("Test database migration successfully\n\n", 'SUCCESS');
    }

    public function testRunAll()
    {
        Config::set('env', 'test');
        $db_setup = 'db_' . Config::get('env');
        MyDB::connect(Config::get($db_setup));

        echo "\nRun all tests\n";
        $time_start = microtime(true);
        $tests = new Tester(['db_connection' => MyDB::db(), 'tests_paths' => ['tests']]);
        $tests->run();

        echo "\nFinished in " . number_format((microtime(true) - $time_start), 2) . " seconds.\n\n";
    }

    public function testRunSingle()
    {
        Config::set('env', 'test');
        $db_setup = 'db_' . Config::get('env');
        MyDB::connect(Config::get($db_setup));

        echo "\nRun single test\n";
        $time_start = microtime(true);
        $tests = new Tester(['db_connection' => MyDB::db(), 'tests_paths' => ['tests'], 'single_test_to_run' => $this->action_param]);
        $tests->run();
        echo "\nFinished in " . number_format((microtime(true) - $time_start), 2) . " seconds.\n\n";
    }

    // private

    /**
    * run all not migrated migrations
    */
    private function runMigrations()
    {
        $db_setup = 'db_' . Config::get('env');
        MyDB::connect(Config::get($db_setup));

        MigrationTools::createSchemaMigrationsTable();

        $runs_migrations_arr = [];
        foreach (glob("db/migrate/*.php") as $file) {
            $version = MigrationTools::getVersionFromFilename($file);
            $is_migrated = MigrationTools::isMigratedMigration($version);

            // if not migrated, execute migration
            if ($is_migrated == false) {
                include_once $file;

                $migration_class_name = MigrationTools::getClassNameFromFilename($file);
                $query = (new $migration_class_name)->up();

                $result = mysqli_query(MyDB::db(), $query);
                if ($result == false) {
                    die(CLIUntils::colorize("\nMigrate error: $file\n\n", 'FAILURE'));
                }

                MigrationTools::incrementSchemaVersionIfSuccess($result, $version);
                $runs_migrations_arr[] = $file;
            }
        }

        return $runs_migrations_arr;
    }

    /**
    *
    */
    private function runRollback()
    {
        $db_setup = 'db_' . Config::get('env');
        MyDB::connect(Config::get($db_setup));

        // get last migration
        $version = MigrationTools::getLastMigrationVersion();

        // get file
        $migration_filepath_arr = glob("db/migrate/$version*.php");

        // jesli nie znaloeziono pliku z ta wesja to wywal blad
        if (empty($migration_filepath_arr)) {
            return ['message' => 'Not found migration rollback file.', 'status' => 'FAILURE'];
        } else {
            $migration_filepath = $migration_filepath_arr[0];
        }

        include_once $migration_filepath;
        $migration_class_name = MigrationTools::getClassNameFromFilename($migration_filepath);
        // get rollbck sql query
        $query = (new $migration_class_name)->down();
        $result = mysqli_query(MyDB::db(), $query);

        if ($result == false) {
            return ['message' => "Migrate rollback error: $migration_filepath", 'status' => 'FAILURE'];
        } else {
            MigrationTools::removeVersion($version);
            return ['message' => $migration_filepath, 'status' => 'SUCCESS'];
        }
    }

    private function dropAllTablesAndRecreate()
    {
        // only i test env!!!
        Config::set('env', 'test');
        $db_setup = 'db_' . Config::get('env');
        MyDB::connect(Config::get($db_setup));
        MyDB::db()->query('SET foreign_key_checks = 0');
        if ($result = MyDB::db()->query("SHOW TABLES")) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                MyDB::db()->query('DROP TABLE IF EXISTS ' . $row[0]);
            }
        }
        MyDB::db()->query('SET foreign_key_checks = 1');
        MyDB::db()->close();
    }
}
