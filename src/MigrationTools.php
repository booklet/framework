<?php
class MigrationTools
{
    // db/migrate/201607061958_CreateUsersTable.php => 201607061958
    public static function getVersionFromFilename($file_name)
    {
        preg_match("/\d{12}/", $file_name, $output_array);
        return $output_array[0];
    }

    public static function getLastMigrationVersion()
    {
        $query = "SELECT `schema_migrations`.`version` FROM `schema_migrations` ORDER BY `version` DESC LIMIT 1";
        $result = mysqli_query(MyDB::db(), $query);
        $version = mysqli_fetch_assoc($result)['version'];

        if (strlen($version) == 12) {
            return $version;
        } else {
            die("Can't get last migration version.");
        }
    }

    // db/migrate/201607061958_CreateUsersTable.php => CreateUsersTable
    public static function getClassNameFromFilename($file_name)
    {
        $file_name = pathinfo($file_name)['filename'];

        return explode('_', $file_name)[1];
    }

    // before first migration create schema_migrations table if not exist
    public static function createSchemaMigrationsTable()
    {
        $query = "SELECT version FROM schema_migrations";
        $result = mysqli_query(MyDB::db(), $query);

        if ($result == null) {
            $query = "CREATE TABLE `schema_migrations` (
                `version` varchar(191) NOT NULL,
                UNIQUE KEY `unique_schema_migrations` (`version`)
            )";
            $result = mysqli_query(MyDB::db(), $query);
        }
    }

    // check if that migration has been migrated
    public static function isMigratedMigration($version)
    {
        $query = "SELECT * FROM schema_migrations WHERE version='" . $version . "'";
        $result = mysqli_query(MyDB::db(), $query);

        return $result->num_rows == 1 ? true : false;
    }

    // insert new migration version to schema_migrations table
    public static function incrementSchemaVersionIfSuccess($result, $version) {
        if (!empty($result)) {
            $query = "INSERT INTO schema_migrations (version) VALUES ($version)";
            mysqli_query(MyDB::db(), $query);
        }
    }

    public static function isAllMigrationsMade()
    {
        // get last migration version
        $migrations_paths = glob("db/migrate/*.php");
        $last_migration_path = array_pop($migrations_paths);
        $last_migration_to_migrate_version = MigrationTools::getVersionFromFilename($last_migration_path);

        // get last migration version from database
        $query = "SELECT version FROM schema_migrations ORDER BY version DESC LIMIT 1";
        $result = mysqli_query(MyDB::db(), $query);
        $last_database_migration_version = mysqli_fetch_assoc($result)['version'];

        if ($last_migration_to_migrate_version == $last_database_migration_version) {
            return true;
        }

        return false;
    }

    // remove passed version
    public static function removeVersion($version)
    {
      $query = "DELETE FROM schema_migrations WHERE version='" . $version . "'";
      $result = mysqli_query(MyDB::db(), $query);
    }
}
