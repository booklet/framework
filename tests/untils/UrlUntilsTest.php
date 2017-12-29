<?php
class UrlUntilsTest extends TesterCase
{
    public function testGetHost()
    {
        $host = UrlUntils::getHDomainWithProtocol('https://booklet.pl/test/subpatach?param=val#test');

        Assert::expect($host)->to_equal('https://booklet.pl');
    }
}
