<?php
class UserFactory
{
    public static function user(Array $overwrite_params = [])
    {
        $user = new User(['username' => 'Jhone Doe', 'email' => 'exmail@test.com', 'role' => 'customer_service', 'password' => 'p@ssw0rd', 'password_confirmation' => 'p@ssw0rd']);

        foreach ($overwrite_params as $key => $value) {
          $user->$key = $value;
        }

        return $user;
    }
}
