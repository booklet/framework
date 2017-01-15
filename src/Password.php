<?php
class Password
{
    public static function encrypt($password) {
        return sha1(Config::get('password_salt').$password);
    }
}
