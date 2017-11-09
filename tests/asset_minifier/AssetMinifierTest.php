<?php
class AssetMinifierTest extends TesterCase
{
    public function jsData()
    {
        return [
            'output_path' => 'tests/fixtures/asset_minifier/output',
            'output_file_name_prefix' => 'test-js',
            'input_files' => [
                'tests/fixtures/asset_minifier/file1.js',
                'tests/fixtures/asset_minifier/file2.js',
            ],
        ];
    }

    public function testMinifierJs()
    {
        $asset = new AssetMinifierJs($this->jsData());
        $path = $asset->getMinifierFilePath();

        Assert::expect($path)->to_include_string('tests/fixtures/asset_minifier/output/test-js-');
        Assert::expect($path)->to_include_string('.js');
        Assert::expect(filesize($path))->to_equal(79);

        $html = $asset->getHtmlTag();

        Assert::expect($html)->to_include_string('<script type="text/javascript" src="/tests/fixtures/asset_minifier/output/test-js-');
        Assert::expect($html)->to_include_string('.js"></script>');

        unlink($path);
    }

    public function testMinifierJsDevelopment()
    {
        $data = $this->jsData();
        $data['environment'] = 'development';
        $asset = new AssetMinifierJs($data);
        $html = $asset->getHtmlTag();

        Assert::expect($html)->to_include_string('<script type="text/javascript" src="/tests/fixtures/asset_minifier/file1.js"></script>');
        Assert::expect($html)->to_include_string('<script type="text/javascript" src="/tests/fixtures/asset_minifier/file2.js"></script>');
    }

    public function cssData()
    {
        return [
            'output_path' => 'tests/fixtures/asset_minifier/output',
            'output_file_name_prefix' => 'test-css',
            'input_files' => [
                'tests/fixtures/asset_minifier/file1.css',
                'tests/fixtures/asset_minifier/file2.css',
            ],
        ];
    }

    public function testMinifierCss()
    {
        $asset = new AssetMinifierCss($this->cssData());
        $path = $asset->getMinifierFilePath();

        Assert::expect($path)->to_include_string('tests/fixtures/asset_minifier/output/test-css-');
        Assert::expect($path)->to_include_string('.css');
        Assert::expect(filesize($path))->to_equal(126);

        $html = $asset->getHtmlTag();

        Assert::expect($html)->to_include_string('<link rel="stylesheet" media="all" href="/tests/fixtures/asset_minifier/output/test-css-');
        Assert::expect($html)->to_include_string('.css" />');

        unlink($path);
    }

    public function testMinifierCssDevelopment()
    {
        $data = $this->cssData();
        $data['environment'] = 'development';
        $asset = new AssetMinifierCss($data);
        $html = $asset->getHtmlTag();

        Assert::expect($html)->to_include_string('<link rel="stylesheet" media="all" href="/tests/fixtures/asset_minifier/file1.css" />');
        Assert::expect($html)->to_include_string('<link rel="stylesheet" media="all" href="/tests/fixtures/asset_minifier/file2.css" />');
    }
}
