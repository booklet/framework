<?php
class MeasurementFileForPrintingTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    private function checkSizes($path, $sizes)
    {
        $file_measurement = new MeasurementFileForPrinting($path);

        Assert::expect($file_measurement->getWidthInPx())->to_equal($sizes['width_px']);
        Assert::expect($file_measurement->getHeightInPx())->to_equal($sizes['height_px']);
        Assert::expect($file_measurement->getWidthInPt())->to_equal($sizes['width_pt']);
        Assert::expect($file_measurement->getHeightInPt())->to_equal($sizes['height_pt']);
        Assert::expect($file_measurement->getWidthInMm())->to_equal($sizes['width_mm']);
        Assert::expect($file_measurement->getHeightInMm())->to_equal($sizes['height_mm']);
    }

    public function testMeasurementJpg()
    {
        $sizes = [
            'width_px' => 640,
            'height_px' => 480,
            'width_pt' => 153.6,
            'height_pt' => 115.2,
            'width_mm' => 54.19,
            'height_mm' => 40.64,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/animal.jpg', $sizes);
    }

    public function testMeasurementPdf()
    {
        $sizes = [
            'width_px' => 1146,
            'height_px' => 319,
            'width_pt' => 274.96,
            'height_pt' => 76.54,
            'width_mm' => 97.0,
            'height_mm' => 27.0,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-01.pdf', $sizes);
    }

    public function testMeasurementAi()
    {
        $sizes = [
            'width_px' => 756,
            'height_px' => 520,
            'width_pt' => 181.42,
            'height_pt' => 124.72,
            'width_mm' => 64.0,
            'height_mm' => 44.0,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-06a.ai', $sizes);
    }

    public function testMeasurementEps()
    {
        $sizes = [
            'width_px' => 3333,
            'height_px' => 3333,
            'width_pt' => 800.0,
            'height_pt' => 800.0,
            'width_mm' => 282.22,
            'height_mm' => 282.22,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-11.eps', $sizes);

        $sizes = [
            'width_px' => 304,
            'height_px' => 346,
            'width_pt' => 73.0,
            'height_pt' => 83.0,
            'width_mm' => 25.75,
            'height_mm' => 29.28,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-07.eps', $sizes);
    }

    // public function testMeasurementSvg()
    // {
    //     $sizes = [
    //         'width_px' => 250,
    //         'height_px' => 250,
    //         'width_pt' => 60.0,
    //         'height_pt' => 60.0,
    //         'width_mm' => 21.17,
    //         'height_mm' => 21.17,
    //     ];
    //
    //     $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-08.svg', $sizes);
    //
    //     $sizes = [
    //         'width_px' => 146,
    //         'height_px' => 108,
    //         'width_pt' => 35.0,
    //         'height_pt' => 26.0,
    //         'width_mm' => 12.35,
    //         'height_mm' => 9.17,
    //     ];
    //
    //     $this->checkSizes('tests/fixtures/paperclip/tests_files/cloud-upload.svg', $sizes);
    // }

    public function testMeasurementGif()
    {
        $sizes = [
            'width_px' => 721,
            'height_px' => 392,
            'width_pt' => 173.04,
            'height_pt' => 94.08,
            'width_mm' => 61.04,
            'height_mm' => 33.19,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-04.gif', $sizes);
    }

    public function testMeasurementPng()
    {
        $sizes = [
            'width_px' => 2223,
            'height_px' => 1678,
            'width_pt' => 533.52,
            'height_pt' => 402.72,
            'width_mm' => 188.21,
            'height_mm' => 142.07,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-05.png', $sizes);
    }

    public function testMeasurementTiff()
    {
        $sizes = [
            'width_px' => 758,
            'height_px' => 757,
            'width_pt' => 181.92,
            'height_pt' => 181.68,
            'width_mm' => 64.18,
            'height_mm' => 64.09,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-03.tif', $sizes);
    }

    public function testMeasurementBmp()
    {
        $sizes = [
            'width_px' => 800,
            'height_px' => 600,
            'width_pt' => 192.0,
            'height_pt' => 144.0,
            'width_mm' => 67.73,
            'height_mm' => 50.8,
        ];

        $this->checkSizes('tests/fixtures/paperclip/tests_files/client-test-file-12.BMP', $sizes);
    }
}
