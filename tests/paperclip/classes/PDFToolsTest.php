<?php
class PDFToolsTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testSplitPdfMultiplePage()
    {
        $pdf_tools = new PDFTools('tests/fixtures/paperclip/tests_files/12-pages.pdf');
        $paths = $pdf_tools->splitPdfToPath('tests/fixtures/paperclip/tmp');

        for ($i = 1; $i < 13; ++$i) {
            $file_path = 'tests/fixtures/paperclip/tmp/12-pages_page' . sprintf('%02d', $i) . '.pdf';
            Assert::expect(file_exists($file_path))->to_equal(true);
            unlink($file_path);
        }

        Assert::expect(count($paths))->to_equal(12);
        Assert::expect($paths[0])->to_equal('tests/fixtures/paperclip/tmp/12-pages_page01.pdf');
        Assert::expect($paths[11])->to_equal('tests/fixtures/paperclip/tmp/12-pages_page12.pdf');
    }

    public function testSplitPdfOnePage()
    {
        $pdf_tools = new PDFTools('tests/fixtures/paperclip/tests_files/client-test-file-09.pdf');
        $paths = $pdf_tools->splitPdfToPath('tests/fixtures/paperclip/tmp');

        $file_path = 'tests/fixtures/paperclip/tmp/client-test-file-09.pdf';
        Assert::expect(file_exists($file_path))->to_equal(true);
        unlink($file_path);

        Assert::expect(count($paths))->to_equal(1);
        Assert::expect($paths[0])->to_equal('tests/fixtures/paperclip/tmp/client-test-file-09.pdf');
    }

    public function testSplitPdfMultiplePageWithCustomFileName()
    {
        $pdf_tools = new PDFTools('tests/fixtures/paperclip/tests_files/12-pages.pdf');
        $paths = $pdf_tools->splitPdfToPath('tests/fixtures/paperclip/tmp', 'prefix nazwy pliku ze spacjami.pdf');

        for ($i = 1; $i < 13; ++$i) {
            $file_path = 'tests/fixtures/paperclip/tmp/prefix-nazwy-pliku-ze-spacjami_page' . sprintf('%02d', $i) . '.pdf';
            Assert::expect(file_exists($file_path))->to_equal(true);
            unlink($file_path);
        }

        Assert::expect(count($paths))->to_equal(12);
        Assert::expect($paths[0])->to_equal('tests/fixtures/paperclip/tmp/prefix-nazwy-pliku-ze-spacjami_page01.pdf');
        Assert::expect($paths[11])->to_equal('tests/fixtures/paperclip/tmp/prefix-nazwy-pliku-ze-spacjami_page12.pdf');
    }

    public function testConvertToJpg()
    {
        $pdf_tools = new PDFTools('tests/fixtures/paperclip/tests_files/client-test-file-01.pdf');
        $jpg_path = 'tests/fixtures/paperclip/tmp/convert_to_jpg.jpg';
        $pdf_tools->convertToJpg($jpg_path);

        Assert::expect(file_exists($jpg_path))->to_equal(true);

        $image = new Imagick($jpg_path);
        $d = $image->getImageGeometry();

        Assert::expect($d['width'])->to_equal(1146);
        Assert::expect($d['height'])->to_equal(319);

        unlink($jpg_path);
    }
}
