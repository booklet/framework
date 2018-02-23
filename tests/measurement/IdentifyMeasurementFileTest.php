<?php
class IdentifyMeasurementFileTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testGestSize()
    {
        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/30x50.pdf');

        Assert::expect($measurement->getWidth())->to_equal(96.0);
        Assert::expect($measurement->getHeight())->to_equal(153.0);

        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/client-test-file-07.eps');

        Assert::expect($measurement->getWidth())->to_equal(73.0);
        Assert::expect($measurement->getHeight())->to_equal(83.0);

        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/cloud-upload.svg');

        Assert::expect($measurement->getWidth())->to_equal(32.0);
        Assert::expect($measurement->getHeight())->to_equal(32.0);

        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/client-test-file-06a.ai');

        Assert::expect($measurement->getWidth())->to_equal(181.0);
        Assert::expect($measurement->getHeight())->to_equal(125.0);

        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/animal.jpg');

        Assert::expect($measurement->getWidth())->to_equal(640.0);
        Assert::expect($measurement->getHeight())->to_equal(480.0);

        $measurement = new IdentifyMeasurementFile('tests/fixtures/paperclip/tests_files/animal_22x33-444x5mm66X77_88.8x99.9.jpg');

        Assert::expect($measurement->getWidth())->to_equal(640.0);
        Assert::expect($measurement->getHeight())->to_equal(480.0);
    }
}
