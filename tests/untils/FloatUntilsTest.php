<?php
class FloatUntilsTest extends TesterCase
{
    public function testFnCeilTo()
    {
        $ceil = Util::floatCeilTo(1.222, 1);
        Assert::expect($ceil)->to_equal(1.3);

        $ceil = Util::floatCeilTo(1.888, 1);
        Assert::expect($ceil)->to_equal(1.9);

        $ceil = Util::floatCeilTo(1.222222222, 3);
        Assert::expect($ceil)->to_equal(1.223);
    }

    public function testFnFloorTo()
    {
        $ceil = Util::floatFloorTo(1.222, 1);
        Assert::expect($ceil)->to_equal(1.2);

        $ceil = Util::floatFloorTo(1.888, 1);
        Assert::expect($ceil)->to_equal(1.8);

        $ceil = Util::floatFloorTo(1.222822222, 3);
        Assert::expect($ceil)->to_equal(1.222);
    }

    public function testGetWrapperFolderById()
    {
        $wrapper_folder = Util::getWrapperFolderById(0);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = Util::getWrapperFolderById(1);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = Util::getWrapperFolderById(999);
        Assert::expect($wrapper_folder)->to_equal('000');

        $wrapper_folder = Util::getWrapperFolderById(1000);
        Assert::expect($wrapper_folder)->to_equal('001');

        $wrapper_folder = Util::getWrapperFolderById(1999);
        Assert::expect($wrapper_folder)->to_equal('001');

        $wrapper_folder = Util::getWrapperFolderById(2000);
        Assert::expect($wrapper_folder)->to_equal('002');
    }
}
