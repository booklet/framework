<?php
class FilesUntilsTest extends TesterCase
{
    public function testIdToPath()
    {
        Assert::expect(FilesUntils::idToPath(0))->to_equal('000/000/000/');
        Assert::expect(FilesUntils::idToPath(1234))->to_equal('000/001/234/');
    }

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

    public function testHumanFilesize()
    {
        $file_size = FilesUntils::humanFilesize(0);
        Assert::expect($file_size)->to_equal('0 B');

        $file_size = FilesUntils::humanFilesize(681);
        Assert::expect($file_size)->to_equal('681 B');

        $file_size = FilesUntils::humanFilesize(1024);
        Assert::expect($file_size)->to_equal('1 KB');

        $file_size = FilesUntils::humanFilesize(2097152);
        Assert::expect($file_size)->to_equal('2 MB');

        $file_size = FilesUntils::humanFilesize(2197152, 0);
        Assert::expect($file_size)->to_equal('2 MB');

        $file_size = FilesUntils::humanFilesize(4718592000);
        Assert::expect($file_size)->to_equal('4.39 GB');
    }
}
