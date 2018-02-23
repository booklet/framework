<?php
include_once 'tests/fixtures/paperclip/NewPaperClipTestingClass.php';

class NewPaperClipTraitTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testAttachmentPath()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1;

        Assert::expect($paper_clip_testing_class->previewPath())->to_equal(null);

        $paper_clip_testing_class->preview_file_name = 'file.pdf';

        Assert::expect($paper_clip_testing_class->previewPath())->to_equal(
          'system/files/new_paper_clip_testing_class/preview/000/000/001/original/file.pdf');
        Assert::expect($paper_clip_testing_class->previewPath('medium'))->to_equal(
          'system/files/new_paper_clip_testing_class/preview/000/000/001/medium/file.jpg');
    }

    public function testAttachmentUrl()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1;

        Assert::expect($paper_clip_testing_class->previewUrl())->to_equal(
          'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/original/missing.png');

        $paper_clip_testing_class->preview_file_name = 'file.pdf';

        Assert::expect($paper_clip_testing_class->previewUrl())->to_equal(
          'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/000/001/original/file.pdf');
        Assert::expect($paper_clip_testing_class->previewUrl('medium'))->to_equal(
          'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/000/001/medium/file.jpg');
    }

    public function testAttachmentReprocess()
    {
        // $paper_clip_testing_class->previewReprocess()
    }
}
