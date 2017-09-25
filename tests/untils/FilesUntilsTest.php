<?php
class FilesUntilsTest extends TesterCase
{
    public function testGetFileExtension()
    {
        $base_name = FilesUntils::getFileExtension('path/to/file.pdf');

        Assert::expect($base_name)->to_equal('pdf');
    }

    public function testGetFileBasename()
    {
        $base_name = FilesUntils::getFileBasename('path/to/file.pdf');

        Assert::expect($base_name)->to_equal('file.pdf');
    }

    public function testGetFileName()
    {
        $file_name = FilesUntils::getFileName('path/to/file.pdf');

        Assert::expect($file_name)->to_equal('file');
    }
}
