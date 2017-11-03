<?php
require_once 'tests/fixtures/models/FWTestModelUser.php';

class ModelUntilsTraitTest extends TesterCase
{
    public function testIsNewRecord()
    {
        $user = new FWTestModelUser([
            'username' => 'Uzytkownik nr1',
            'email' => 'user1@booklet.pl',
            'role' => 'admin',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        Assert::expect($user->isNewRecord())->to_equal(true);

        $user->save();

        Assert::expect($user->isNewRecord())->to_equal(false);
    }

    public function testPluralizeClassName()
    {
        $user = new FWTestModelUser([
            'username' => 'Uzytkownik nr1',
            'email' => 'user1@booklet.pl',
            'role' => 'admin',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        Assert::expect($user->PluralizeClassName())->to_equal('FWTestModelUsers');
    }
}
