<?php
include_once 'tests/fixtures/paperclip/NewPaperClipTestingClass.php';

class NewPaperClipTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testAttachmentStyles()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        $expect_results = [
            'medium' => '300x300>',
            'thumbnail' => '100x100#',
        ];

        Assert::expect($paper_clip->attachmentStyles('preview'))->to_equal($expect_results);
    }

    public function testSaveFile()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        $paper_clip->saveFile('preview', 'tests/fixtures/paperclip/tests_files/animal.jpg');

        Assert::expect($paper_clip_testing_class->preview_file_name)->to_equal('animal.jpg');
        Assert::expect($paper_clip_testing_class->preview_file_size)->to_equal(47851);
        Assert::expect($paper_clip_testing_class->preview_content_type)->to_equal('image/jpeg');
        Assert::expect($paper_clip_testing_class->preview_updated_at)->toNotBeNull();

        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/000/000/original/animal.jpg'))->to_equal(true);
        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/000/000/medium/animal.jpg'))->to_equal(true);
        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/000/000/thumbnail/animal.jpg'))->to_equal(true);

        FilesUntils::deleteDirectoryAndEverythingIn('system/files/new_paper_clip_testing_class');
    }

    public function testReprocess()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        $paper_clip->saveFile('preview', 'tests/fixtures/paperclip/tests_files/animal.jpg');

        Assert::expect($paper_clip_testing_class->preview_file_name)->to_equal('animal.jpg');
        Assert::expect($paper_clip_testing_class->preview_file_size)->to_equal(47851);
        Assert::expect($paper_clip_testing_class->preview_content_type)->to_equal('image/jpeg');
        Assert::expect($paper_clip_testing_class->preview_updated_at)->toNotBeNull();
        Assert::expect(file_exists('system/files/new_paper_clip_testing_class/preview/000/000/000/medium/animal.jpg'))->to_equal(true);

        $image = new Imagick('system/files/new_paper_clip_testing_class/preview/000/000/000/medium/animal.jpg');
        Assert::expect($image->getImageGeometry())->to_equal(['width' => 300, 'height' => 225]);
        $image = new Imagick('system/files/new_paper_clip_testing_class/preview/000/000/000/thumbnail/animal.jpg');
        Assert::expect($image->getImageGeometry())->to_equal(['width' => 100, 'height' => 100]);

        $paper_clip_testing_class->fake_styles = [
            'preview' => [
                'styles' => [
                    'medium' => '200x200>',
                    'thumbnail' => '50x50#',
                ],
            ],
        ];
        $paper_clip = new NewPaperClip($paper_clip_testing_class);
        $paper_clip->reprocess('preview');

        $image = new Imagick('system/files/new_paper_clip_testing_class/preview/000/000/000/medium/animal.jpg');
        Assert::expect($image->getImageGeometry())->to_equal(['width' => 200, 'height' => 150]);
        $image = new Imagick('system/files/new_paper_clip_testing_class/preview/000/000/000/thumbnail/animal.jpg');
        Assert::expect($image->getImageGeometry())->to_equal(['width' => 50, 'height' => 50]);

        FilesUntils::deleteDirectoryAndEverythingIn('system/files/new_paper_clip_testing_class');
    }

    public function testDestroy()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip = new NewPaperClip($paper_clip_testing_class);
        $paper_clip->saveFile('preview', 'tests/fixtures/paperclip/tests_files/animal.jpg');
        $paper_clip->reprocess('preview');

        Assert::expect($paper_clip_testing_class->preview_file_name)->to_equal('animal.jpg');

        $paper_clip->destroy('preview');

        Assert::expect($paper_clip_testing_class->preview_file_name)->toBeNull();
    }

    public function testGetAttachmentNameFromFunctionName()
    {
        Assert::expect(NewPaperClip::getAttachmentNameFromFunctionName('previewPath'))->to_equal('preview');
    }
}
