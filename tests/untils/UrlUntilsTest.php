<?php
class UrlUntilsTest extends TesterCase
{
    public function testGetHost()
    {
        $host = UrlUntils::getDomainWithProtocol('https://www.booklet.pl/test/subpatch?param=val#test');
        Assert::expect($host)->to_equal('https://booklet.pl');

        $host = UrlUntils::getDomainWithProtocol('http://www.booklet.pl/test/subpatch?param=val#test');
        Assert::expect($host)->to_equal('http://booklet.pl');

        $host = UrlUntils::getDomainWithProtocol('www.booklet.pl/test/subpatch?param=val#test');
        Assert::expect($host)->to_equal('http://booklet.pl');

        $host = UrlUntils::getDomainWithProtocol('booklet.pl');
        Assert::expect($host)->to_equal('http://booklet.pl');

        // TODO Add support to invalid urls
        // $host = UrlUntils::getDomainWithProtocol('booklet');
        // Assert::expect($host)->to_equal(false);
    }
}
