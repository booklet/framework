<?php
class FloatUntilsTest extends TesterCase
{
    public function testFnCeilTo()
    {
        $ceil = FloatUntils::ceilTo(1.222, 1);
        Assert::expect($ceil)->to_equal(1.3);

        $ceil = FloatUntils::ceilTo(1.888, 1);
        Assert::expect($ceil)->to_equal(1.9);

        $ceil = FloatUntils::ceilTo(1.222222222, 3);
        Assert::expect($ceil)->to_equal(1.223);
    }

    public function testFnFloorTo()
    {
        $ceil = FloatUntils::floorTo(1.222, 1);
        Assert::expect($ceil)->to_equal(1.2);

        $ceil = FloatUntils::floorTo(1.888, 1);
        Assert::expect($ceil)->to_equal(1.8);

        $ceil = FloatUntils::floorTo(1.222822222, 3);
        Assert::expect($ceil)->to_equal(1.222);
    }

    public function testGetWrapperFolderById()
    {
        $wrapper_folder = FilesUntils::getWrapperFolderById(0);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = FilesUntils::getWrapperFolderById(1);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = FilesUntils::getWrapperFolderById(999);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = FilesUntils::getWrapperFolderById(1000);
        Assert::expect($wrapper_folder)->to_equal('001');

        $wrapper_folder = FilesUntils::getWrapperFolderById(1999);
        Assert::expect($wrapper_folder)->to_equal('001');

        $wrapper_folder = FilesUntils::getWrapperFolderById(2000);
        Assert::expect($wrapper_folder)->to_equal('002');
    }
}
