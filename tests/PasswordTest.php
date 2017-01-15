<?php
class PasswordTest extends TesterCase
{
    public function testEncrypt()
    {
        $password_digest = Password::encrypt('my_password');
        Assert::expect($password_digest)->to_equal('393ddeab73fa104ecf2c36f7f6e5dad72237df35');
    }
}
