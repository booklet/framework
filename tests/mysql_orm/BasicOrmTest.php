<?php
class BasicOrmTest extends TesterCase
{
    public function populateUserTable()
    {
        $user1 = new FWTestModelUser(['username' => 'Uzytkownik nr1', 'email' => 'user1@booklet.pl', 'role' => 'admin', 'password' => 'password1', 'password_confirmation' => 'password1']);
        $user1->save();
        $user2 = new FWTestModelUser(['username' => 'Uzytkownik nr2', 'email' => 'user2@booklet.pl', 'role' => 'customer_service', 'password' => 'password2', 'password_confirmation' => 'password2']);
        $user2->save();
        $user3 = new FWTestModelUser(['username' => 'Uzytkownik nr3', 'email' => 'user3@booklet.pl', 'role' => 'customer_service', 'password' => 'password3', 'password_confirmation' => 'password3']);
        $user3->save();
        $user4 = new FWTestModelUser(['username' => 'Uzytkownik nr4', 'email' => 'user4@booklet.pl', 'role' => 'customer_service', 'password' => 'password4', 'password_confirmation' => 'password4']);
        $user4->save();
        $user5 = new FWTestModelUser(['username' => 'Uzytkownik nr5', 'email' => 'user5@booklet.pl', 'role' => 'admin', 'password' => 'password5', 'password_confirmation' => 'password5']);
        $user5->save();
        $user6 = new FWTestModelUser(['username' => 'Uzytkownik nr6', 'email' => 'user6@booklet.pl', 'role' => 'customer_service', 'password' => 'password6', 'password_confirmation' => 'password6']);
        $user6->save();
    }

    public function testAll()
    {

        $this->populateUserTable();
        $users = FWTestModelUser::all();

        Assert::expect(count($users))->to_equal(6);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr1');
    }

    public function testAllByOrder()
    {
        $this->populateUserTable();
        $users = FWTestModelUser::all(['order'=>'id DESC']);

        Assert::expect(count($users))->to_equal(6);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr6');
    }

    public function testAllByLimitAndPage()
    {
        $this->populateUserTable();
        $users = FWTestModelUser::all(['limit'=>'2', 'page'=>2]);

        Assert::expect(count($users))->to_equal(2);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr3');
    }

    public function testFind()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::find(3);

        Assert::expect($user->username)->to_equal('Uzytkownik nr3');
    }

    public function testFindWithWrongId()
    {
        $this->populateUserTable();

        try {
            $user = FWTestModelUser::find(100);
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal("Couldn't find FWTestModelUser with id=100");
        }
    }

    public function testFindBy()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::findBy('email', 'user2@booklet.pl');

        Assert::expect($user->username)->to_equal('Uzytkownik nr2');
    }

    public function testFirst()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::first();
        Assert::expect($user->username)->to_equal('Uzytkownik nr1');
    }

    public function testFast()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::last();
        Assert::expect($user->username)->to_equal('Uzytkownik nr6');
    }

    public function testWhere()
    {
        $this->populateUserTable();
        $users = FWTestModelUser::where("role = ?", ['role'=>'customer_service']);

        Assert::expect(count($users))->to_equal(4);
    }

    public function testWhereTwoWar()
    {
        $this->populateUserTable();
        $users = FWTestModelUser::where("role = ? AND username = ?", ['role'=>'customer_service', 'username'=>'Uzytkownik nr3']);

        Assert::expect(count($users))->to_equal(1);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr3');
    }

    public function testSave()
    {

    }

    public function testUpdate()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::find(3);
        $user->update(['username' => 'Nowa nazwa', 'email' => 'nowyemail@booklet.pl']);

        $user_reload = FWTestModelUser::find(3);
        Assert::expect($user_reload->username)->to_equal('Nowa nazwa');
        Assert::expect($user_reload->email)->to_equal('nowyemail@booklet.pl');
    }

    public function testDestroy()
    {
        $this->populateUserTable();

        $user = FWTestModelUser::find(3);
        Assert::expect($user->destroy())->to_equal(true);

        $users = FWTestModelUser::all();
        Assert::expect(count($users))->to_equal(5);
    }

    public function testFnCreateDbObject()
    {
        $this->populateUserTable();
        $user = FWTestModelUser::first();

        $user->username = 'Nowa nazwa uzytkownia';
        $user->email = 'user101@booklet.pl';

        $orm = new MysqlORM(null, $user);
        $db_obj = MysqlORMObjectCreator::createDbObject($orm->model_obj);

        Assert::expect(ObjectUntils::objToArray($db_obj))->to_equal(['username' => 'Nowa nazwa uzytkownia', 'email' => 'user101@booklet.pl']);

        $user->save();
        $user = FWTestModelUser::first();
        Assert::expect($user->username)->to_equal('Nowa nazwa uzytkownia');
        Assert::expect($user->email)->to_equal('user101@booklet.pl');
    }
}
