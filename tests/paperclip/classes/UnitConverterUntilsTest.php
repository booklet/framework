<?php
class UnitConverterUntilsTest extends TesterCase
{
    public $skip_database_clear_before = ['all'];

    public function testPtToMm()
    {
        Assert::expect(round(UnitConverterUntils::ptToMm(100), 8))->to_equal(round(35.277786107255, 8));
        Assert::expect(UnitConverterUntils::ptToMm(100, ['precision' => 2]))->to_equal(35.28);
        Assert::expect(round(UnitConverterUntils::ptToMm(348.7), 8))->to_equal(round(123.013640156, 8));
        Assert::expect(UnitConverterUntils::ptToMm(100, ['precision' => 0]))->to_equal(35.0);
    }

    public function testPtToPx()
    {
        Assert::expect(UnitConverterUntils::ptToPx(100))->to_equal(417);
        Assert::expect(UnitConverterUntils::ptToPx(100, ['dpi' => 72]))->to_equal(100);
        Assert::expect(UnitConverterUntils::ptToPx(2399.8))->to_equal(9999);
        Assert::expect(UnitConverterUntils::ptToPx(35.277786107255))->to_equal(147);
    }

    public function testPxToMm()
    {
        Assert::expect(UnitConverterUntils::pxToMm(100, ['precision' => 2]))->to_equal(8.47);
        Assert::expect(UnitConverterUntils::pxToMm(100, ['dpi' => 72, 'precision' => 2]))->to_equal(35.28);
        Assert::expect(UnitConverterUntils::pxToMm(118098, ['precision' => 2]))->to_equal(9998.96);
        Assert::expect(UnitConverterUntils::pxToMm(899, ['precision' => 2]))->to_equal(76.12);
    }

    public function testPxToPt()
    {
        Assert::expect(UnitConverterUntils::pxToPt(100))->to_equal(24);
        Assert::expect(UnitConverterUntils::pxToPt(100, ['dpi' => 72]))->to_equal(100);
        Assert::expect(UnitConverterUntils::pxToPt(118098))->to_equal(28343.52);
        Assert::expect(UnitConverterUntils::pxToPt(899))->to_equal(215.76);
    }

    public function testMmToPx()
    {
        Assert::expect(UnitConverterUntils::mmToPx(100))->to_equal(1181);
        Assert::expect(UnitConverterUntils::mmToPx(100, ['dpi' => 72]))->to_equal(283);
        Assert::expect(UnitConverterUntils::mmToPx(20.5))->to_equal(242);
    }
}
