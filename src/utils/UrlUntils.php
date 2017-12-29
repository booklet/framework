<?php
class UrlUntils
{
    public static function getHDomainWithProtocol($url)
    {
        return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
    }
}
