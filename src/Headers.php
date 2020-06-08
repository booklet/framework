<?php
class Headers
{
    private $headers;

    function __construct()
    {
        $this->headers = apache_request_headers();
    }

    public function authorizationToken()
    {
        // Base on server we got: authorization, Authorization, AUTHORIZATION
        // so we standardize array keys by array_change_key_case to lowercase
        $headers = array_change_key_case($this->headers);

        if (isset($headers['authorization'])) {
            return $headers['authorization'];
        }

        return null;
    }

    public function isTesterTestRequest()
    {
        $headers = array_change_key_case($this->headers);
        if (isset($headers['testertestrequestbkt'])) {
            return true;
        }

        return false;
    }
}

// HOOK
// add function for home.pl
if (!function_exists('apache_request_headers'))
{
    function apache_request_headers() {
        $arh = [];
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = [];
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }

        return($arh);
    }
}
