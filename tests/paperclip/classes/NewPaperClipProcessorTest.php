<?php
include_once 'tests/fixtures/paperclip/NewPaperClipTestingClass.php';

class NewPaperClipProcessorTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testProcessFile()
    {
        copy('tests/fixtures/paperclip/tests_files/client-test-file-01.pdf', 'tests/fixtures/paperclip/tmp/client-test-file-01.pdf');

        $processor = new NewPaperClipProcessor('tests/fixtures/paperclip/tmp/client-test-file-01.pdf');
        $processor->processFileAndSave('1000x1000#', 'tests/fixtures/paperclip/tmp/client-test-file-01-300x300.jpg');

        Assert::expect(file_exists('tests/fixtures/paperclip/tmp/client-test-file-01-300x300.jpg'))->to_equal(true);
    }
}
