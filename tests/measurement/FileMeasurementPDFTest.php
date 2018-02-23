<?php
class FileMeasurementPDFTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testPagesCount()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/30x50.pdf');

        Assert::expect($pdf_tools->pagesCount())->to_equal(3);

        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/one-page-file.pdf');

        Assert::expect($pdf_tools->pagesCount())->to_equal(1);

        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/client-test-file-01.pdf');

        Assert::expect($pdf_tools->pagesCount())->to_equal(2);
    }

    public function testWidthInPt()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->widthInPt())->to_equal(255.12);
        Assert::expect($pdf_tools->widthInPt(['page' => 2]))->to_equal(340.16);
        Assert::expect($pdf_tools->widthInPt(['page' => 3]))->to_equal(765.35);
    }

    public function testHeightInPt()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->heightInPt())->to_equal(198.42);
        Assert::expect($pdf_tools->heightInPt(['page' => 2]))->to_equal(538.58);
        Assert::expect($pdf_tools->heightInPt(['page' => 3]))->to_equal(623.62);
    }

    public function testSizesWithTrimbox()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/55x55_bez_spadu.pdf');
        Assert::expect($pdf_tools->heightInPt())->to_equal(155.91);

        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/59x59.pdf');
        Assert::expect($pdf_tools->heightInPt())->to_equal(167.24);

        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/55x55_spady.pdf');
        Assert::expect($pdf_tools->heightInPt())->to_equal(167.25);
    }

    public function testWidthInMM()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->widthInMm())->to_equal(90.0);
        Assert::expect($pdf_tools->widthInMm(['page' => 2]))->to_equal(120.0);
        Assert::expect($pdf_tools->widthInMm(['page' => 3]))->to_equal(270.0);
    }

    public function testHeightInMM()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->heightInMm())->to_equal(70.0);
        Assert::expect($pdf_tools->heightInMm(['page' => 2]))->to_equal(190.0);
        Assert::expect($pdf_tools->heightInMm(['page' => 3]))->to_equal(220.0);
    }

    public function testWidthInPx()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->widthInPx())->to_equal(1063);
        Assert::expect($pdf_tools->widthInPx(['page' => 2]))->to_equal(1417);
        Assert::expect($pdf_tools->widthInPx(['page' => 3]))->to_equal(3189);
    }

    public function testHeightInPx()
    {
        $pdf_tools = new FileMeasurementPDF('tests/fixtures/paperclip/tests_files/difrent-pages-sizes.pdf');

        Assert::expect($pdf_tools->heightInPx())->to_equal(827);
        Assert::expect($pdf_tools->heightInPx(['page' => 2]))->to_equal(2244);
        Assert::expect($pdf_tools->heightInPx(['page' => 3]))->to_equal(2598);
    }
}
