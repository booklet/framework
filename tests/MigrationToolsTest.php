<?php
class MigrationToolsTest extends TesterCase
{
    public function testGetVersionFromFilename()
    {
        $mt = new MigrationTools();
        $version = $mt->getVersionFromFilename('db/migrate/201607061958_CreateUsersTable.php');

        Assert::expect($version)->to_equal('201607061958');
    }

    public function testGetLastMigrationVersion()
    {
        $mt = new MigrationTools();
        $version = $mt->getLastMigrationVersion();

        Assert::expect($version)->to_equal('201601010003');
    }

    public function testGetClassNameFromFilename()
    {
        $mt = new MigrationTools();
        $class_name = $mt->getClassNameFromFilename('db/migrate/201607061958_CreateUsersTable.php');

        Assert::expect($class_name)->to_equal('CreateUsersTable');
    }

    public function testisMigratedMigration()
    {
        $mt = new MigrationTools();
        $is_migrated = $mt->isMigratedMigration('201601010000');

        Assert::expect($is_migrated)->to_equal(true);

        $is_migrated = $mt->isMigratedMigration('202001010000');

        Assert::expect($is_migrated)->to_equal(false);
    }
}
