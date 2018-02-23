<?php
include_once 'tests/fixtures/paperclip/NewPaperClipTestingClass.php';

class NewPaperClipPathTraitTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testAttachmentDirectory()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;

        $paper_clip = new NewPaperClip($paper_clip_testing_class);
        $get_file = $paper_clip->attachmentDirectory('preview', 'medium');

        Assert::expect($get_file)->to_equal('system/files/new_paper_clip_testing_class/preview/000/001/234/medium');

        $get_file = $paper_clip->attachmentDirectory('preview');

        Assert::expect($get_file)->to_equal('system/files/new_paper_clip_testing_class/preview/000/001/234/original');
    }

    public function testAttachmentPath()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;

        $paper_clip = new NewPaperClip($paper_clip_testing_class);
        $get_file = $paper_clip->attachmentPath('preview', 'medium');

        Assert::expect($get_file)->to_equal(null);

        $paper_clip_testing_class->preview_file_name = 'test.pdf';

        $paper_clip = new NewPaperClip($paper_clip_testing_class);
        $get_file = $paper_clip->attachmentPath('preview', 'medium');

        Assert::expect($get_file)->to_equal('system/files/new_paper_clip_testing_class/preview/000/001/234/medium/test.jpg');
    }

    public function testAttachmentUrl()
    {
        Config::set('paperclip_host', 'http://api.booklet.dev');

        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;
        $paper_clip_testing_class->preview_file_name = 'test.pdf';

        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        Assert::expect($paper_clip->attachmentUrl('preview'))->to_equal('http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/001/234/original/test.pdf');
        Assert::expect($paper_clip->attachmentUrl('preview', 'medium'))->to_equal('http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/001/234/medium/test.jpg');
    }

    public function testAttachmentName()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;
        $paper_clip_testing_class->preview_file_name = 'test.pdf';

        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        Assert::expect($paper_clip->attachmentName('preview'))->to_equal('test.pdf');
        Assert::expect($paper_clip->attachmentName('preview', 'medium'))->to_equal('test.jpg');
    }

    public function testAttachmentPaths()
    {
        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;
        $paper_clip_testing_class->preview_file_name = 'test.pdf';

        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        $expect_results = [
            'original' => 'system/files/new_paper_clip_testing_class/preview/000/001/234/original/test.pdf',
            'medium' => 'system/files/new_paper_clip_testing_class/preview/000/001/234/medium/test.jpg',
            'thumbnail' => 'system/files/new_paper_clip_testing_class/preview/000/001/234/thumbnail/test.jpg',
        ];

        Assert::expect($paper_clip->attachmentPaths('preview'))->to_equal($expect_results);
    }

    public function testAttachmentUrls()
    {
        Config::set('paperclip_host', 'http://api.booklet.dev');

        $paper_clip_testing_class = new NewPaperClipTestingClass();
        $paper_clip_testing_class->id = 1234;
        $paper_clip_testing_class->preview_file_name = 'test.pdf';

        $paper_clip = new NewPaperClip($paper_clip_testing_class);

        $expect_results = [
            'original' => 'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/001/234/original/test.pdf',
            'medium' => 'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/001/234/medium/test.jpg',
            'thumbnail' => 'http://api.booklet.dev/system/files/new_paper_clip_testing_class/preview/000/001/234/thumbnail/test.jpg',
        ];

        Assert::expect($paper_clip->attachmentUrls('preview'))->to_equal($expect_results);
    }
}
