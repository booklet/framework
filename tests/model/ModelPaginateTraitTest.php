<?php
class ModelPaginateTraitTest extends TesterCase
{
    public function populateUserTable()
    {
        for ($i = 1; $i < 101; ++$i) {
            $role = $i > 50 ? 'customer_service' : 'admin';

            $user = new FWTestModelUser([
                'username' => 'Uzytkownik nr ' . $i,
                'email' => 'user' . $i . '@booklet.pl',
                'role' => $role,
                'password' => 'password1',
                'password_confirmation' => 'password1', ]);
            $user->save();
        }
    }

    public function testAllWithPaginate()
    {
        $this->populateUserTable();
        $all_users = FWTestModelUser::all();

        Assert::expect(count($all_users))->to_equal(100);

        $params = ['page' => 3];
        list($users, $paginate_data) = FWTestModelUser::allWithPaginate(['order' => 'id DESC'], $params);

        Assert::expect(count($users))->to_equal(30);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 4,
            'current_page' => 3,
            'total_items' => 100,
            'items_per_page' => 30,
        ]);

        $params = ['page' => 4];
        list($users, $paginate_data) = FWTestModelUser::allWithPaginate(['order' => 'id DESC'], $params);

        Assert::expect(count($users))->to_equal(10);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 4,
            'current_page' => 4,
            'total_items' => 100,
            'items_per_page' => 30,
        ]);

        $params = ['page' => 99];
        list($users, $paginate_data) = FWTestModelUser::allWithPaginate(['order' => 'id DESC'], $params);

        Assert::expect(count($users))->to_equal(0);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 4,
            'current_page' => 99,
            'total_items' => 100,
            'items_per_page' => 30,
        ]);
    }

    public function testWhereWithPaginate()
    {
        $this->populateUserTable();
        $all_users = FWTestModelUser::all(['order' => 'id DESC']);

        Assert::expect(count($all_users))->to_equal(100);

        $params = ['page' => 2];
        list($users, $paginate_data) = FWTestModelUser::whereWithPaginate('role = ?', ['role' => 'admin'], [], $params);

        Assert::expect(count($users))->to_equal(20);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 2,
            'current_page' => 2,
            'total_items' => 50,
            'items_per_page' => 30,
        ]);

        $params = ['page' => 99];
        list($users, $paginate_data) = FWTestModelUser::whereWithPaginate('role = ?', ['role' => 'admin'], [], $params);

        Assert::expect(count($users))->to_equal(0);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 2,
            'current_page' => 99,
            'total_items' => 50,
            'items_per_page' => 30,
        ]);
    }

    public function testAllWithPaginateZeroResults()
    {
        $all_users = FWTestModelUser::all();

        Assert::expect(count($all_users))->to_equal(0);

        $params = ['page' => 1];
        list($users, $paginate_data) = FWTestModelUser::allWithPaginate(['order' => 'id DESC'], $params);

        Assert::expect(count($users))->to_equal(0);
        Assert::expect($paginate_data)->to_equal([
            'total_pages' => 0,
            'current_page' => 1,
            'total_items' => 0,
            'items_per_page' => 30,
        ]);
    }
}
