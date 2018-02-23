<?php
include_once 'tests/fixtures/paperclip/NewPaperClipTestingClass.php';

class NewPaperClipSaveTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testSaveFileFromArray()
    {
        copy('tests/fixtures/paperclip/tests_files/animal.jpg', 'tests/fixtures/paperclip/tmp/animal.jpg');

        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;

        $file = $this->fileParamsArray();

        $saver = new NewPaperClipSave([
            'model_object' => $paper_clip_testing_class,
            'attachment_name' => 'preview',
            'file' => $file,
        ]);
        $saver->saveOriginalFile();

        Assert::expect($paper_clip_testing_class->preview_file_name)->to_equal('animal.jpg');
        Assert::expect($paper_clip_testing_class->preview_file_size)->to_equal(1000);
        Assert::expect($paper_clip_testing_class->preview_content_type)->to_equal('image/jpeg');
        Assert::expect($paper_clip_testing_class->preview_updated_at)->toNotBeNull();
        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/001/234/original/animal.jpg'))->to_equal(true);

        FilesUntils::deleteDirectoryAndEverythingIn('system/files/new_paper_clip_testing_class');
    }

    public function testSaveFileFromString()
    {
        copy('tests/fixtures/paperclip/tests_files/animal.jpg', 'tests/fixtures/paperclip/tmp/animal.jpg');

        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;

        $saver = new NewPaperClipSave([
            'model_object' => $paper_clip_testing_class,
            'attachment_name' => 'preview',
            'file' => 'tests/fixtures/paperclip/tmp/animal.jpg',
        ]);
        $saver->saveOriginalFile();

        Assert::expect($paper_clip_testing_class->preview_file_name)->to_equal('animal.jpg');
        Assert::expect($paper_clip_testing_class->preview_file_size)->to_equal(47851);
        Assert::expect($paper_clip_testing_class->preview_content_type)->to_equal('image/jpeg');
        Assert::expect($paper_clip_testing_class->preview_updated_at)->toNotBeNull();
        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/001/234/original/animal.jpg'))->to_equal(true);

        FilesUntils::deleteDirectoryAndEverythingIn('system/files/new_paper_clip_testing_class');
    }

    public function fileParamsArray()
    {
        return [
            'name' => 'animal.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => 'tests/fixtures/paperclip/tmp/animal.jpg',
            'error' => 0,
            'size' => 1000,
        ];
    }

    public function uploadFileParamsArray()
    {
        return [
            'preview' => [
                'name' => [
                    'animal.jpg',
                    'not exists.jpg',
                ],
                'type' => [
                    'image/jpeg',
                    'image/jpeg',
                ],
                'tmp_name' => [
                    'tests/fixtures/paperclip/tmp/animal.jpg',
                    'tests/fixtures/paperclip/tmp/not-exists.jpg',
                ],
                'error' => [
                    0,
                    0,
                ],
                'size' => [
                    1000,
                    2000,
                ],
            ],
        ];
    }
}
