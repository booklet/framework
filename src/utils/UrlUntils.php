<?php
class UrlUntils
{
    public static function getDomainWithProtocol($url)
    {
        if (strpos($url, 'http://') === false && strpos($url, 'https://') === false) {
            $url = 'http://' . $url;
        }

        $parsed_url = parse_url($url);
        if (isset($parsed_url['host'])) {
            $url = $parsed_url['scheme'] . '://' . preg_replace('#^www\.(.+\.)#i', '$1', $parsed_url['host']);
        }

        return $url;
    }
}
