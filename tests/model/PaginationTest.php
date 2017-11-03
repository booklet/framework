<?php
require_once 'tests/fixtures/models/FWTestModelUser.php';
require_once 'tests/fixtures/models/FWTestCustomModel.php';

class PaginationTest extends TesterCase
{
    public function testPagination()
    {
        for ($i = 0; $i < 250; ++$i) {
            $user = new FWTestModelUser([
                'username' => 'user' . $i,
                'email' => 'user' . $i . '@test.com',
                'role' => 'user',
                'password' => 'none',
                'password_confirmation' => 'none',
            ]);
            $user->save();
        }

        $users = FWTestModelUser::all(['paginate' => 2]);
        Assert::expect(count($users))->to_equal(25);
        Assert::expect($users[0]->id)->to_equal(26);

        $users = FWTestModelUser::all(['paginate' => 2, 'per_page' => 50]);
        Assert::expect(count($users))->to_equal(50);
        Assert::expect($users[0]->id)->to_equal(51);

        $users = FWTestModelUser::all(['paginate' => 3, 'per_page' => 100]);
        Assert::expect(count($users))->to_equal(50);
        Assert::expect($users[0]->id)->to_equal(201);

        $users = FWTestModelUser::all(['paginate' => 33, 'per_page' => 100]);
        Assert::expect(count($users))->to_equal(0);
    }
}
