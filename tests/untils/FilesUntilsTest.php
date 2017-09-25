<?php
class FilesUntilsTest extends TesterCase
{
    public function testObjectIdToPath()
    {
        $object = (object) ['id' => 0];

        Assert::expect(FilesUntils::objectIdToPath($object))->to_equal('000/000/000/');

        $object = (object) ['id' => 1234];

        Assert::expect(FilesUntils::objectIdToPath($object))->to_equal('000/001/234/');

        try {
            $object = (object) [];
            $test = FilesUntils::objectIdToPath($object);
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal('Object does not have id property.');
        }
    }

    public function testGetPathDirname()
    {
        $base_name = FilesUntils::getPathDirname('path/to/file.pdf');

        Assert::expect($base_name)->to_equal('path/to');
    }

    public function testGetFileBasename()
    {
        $base_name = FilesUntils::getFileBasename('path/to/file.pdf');

        Assert::expect($base_name)->to_equal('file.pdf');
    }

    public function testGetFileExtension()
    {
        $base_name = FilesUntils::getFileExtension('path/to/file.pdf');

        Assert::expect($base_name)->to_equal('pdf');
    }

    public function testGetFileName()
    {
        $file_name = FilesUntils::getFileName('path/to/file.pdf');

        Assert::expect($file_name)->to_equal('file');
    }
}
