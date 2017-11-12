<?php
class AssetStampTest extends TesterCase
{
    public function testGetStamp()
    {
        $asset = new AssetStamp([
            'tests/fixtures/asset_minifier/file1.js',
            'tests/fixtures/asset_minifier/file2.js',
        ]);

        $stamp = $asset->getStamp();
        Assert::expect(strlen($stamp))->to_equal(32);
        Assert::expect($stamp)->to_be_not_equal('d41d8cd98f00b204e9800998ecf8427e'); // empty string hash
    }
}
