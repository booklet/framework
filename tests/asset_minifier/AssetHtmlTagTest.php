<?php
class AssetHtmlTagTest extends TesterCase
{
    public function testGetHtmlTagsJs()
    {
        $tag = new AssetHtmlTag([
            'tests/fixtures/asset_minifier/file1.js',
            'tests/fixtures/asset_minifier/file2.js',
        ], 'js');

        $html = $tag->getHtmlTags();
        Assert::expect($html)->to_equal("<script type=\"text/javascript\" src=\"/tests/fixtures/asset_minifier/file1.js\"></script>\n\r<script type=\"text/javascript\" src=\"/tests/fixtures/asset_minifier/file2.js\"></script>\n\r");
    }

    public function testGetHtmlTagsCss()
    {
        $tag = new AssetHtmlTag([
            'tests/fixtures/asset_minifier/file1.css',
            'tests/fixtures/asset_minifier/file2.css',
        ], 'css');

        $html = $tag->getHtmlTags();
        Assert::expect($html)->to_equal("<link rel=\"stylesheet\" media=\"all\" href=\"/tests/fixtures/asset_minifier/file1.css\" />\n\r<link rel=\"stylesheet\" media=\"all\" href=\"/tests/fixtures/asset_minifier/file2.css\" />\n\r");
    }
}
