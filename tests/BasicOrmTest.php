<?php
class BasicOrmTest extends TesterCase
{
    public function populateUserTable()
    {
        $user1 = new User(['username' => 'Uzytkownik nr1', 'email' => 'user1@booklet.pl', 'role' => 'admin', 'password' => 'password1', 'password_confirmation' => 'password1']);
        $user1->save();
        $user2 = new User(['username' => 'Uzytkownik nr2', 'email' => 'user2@booklet.pl', 'role' => 'customer_service', 'password' => 'password2', 'password_confirmation' => 'password2']);
        $user2->save();
        $user3 = new User(['username' => 'Uzytkownik nr3', 'email' => 'user3@booklet.pl', 'role' => 'customer_service', 'password' => 'password3', 'password_confirmation' => 'password3']);
        $user3->save();
        $user4 = new User(['username' => 'Uzytkownik nr4', 'email' => 'user4@booklet.pl', 'role' => 'customer_service', 'password' => 'password4', 'password_confirmation' => 'password4']);
        $user4->save();
        $user5 = new User(['username' => 'Uzytkownik nr5', 'email' => 'user5@booklet.pl', 'role' => 'admin', 'password' => 'password5', 'password_confirmation' => 'password5']);
        $user5->save();
        $user6 = new User(['username' => 'Uzytkownik nr6', 'email' => 'user6@booklet.pl', 'role' => 'customer_service', 'password' => 'password6', 'password_confirmation' => 'password6']);
        $user6->save();
    }

    public function testAll()
    {
      $this->pending();
        $this->populateUserTable();
        $users = User::all();

        Assert::expect(count($users))->to_equal(6);
        Assert::expect($users[1]->username)->to_equal('Uzytkownik nr2');
    }

    public function testAllByOrder()
    {
      $this->pending();
        $this->populateUserTable();
        $users = User::all(['order'=>'id DESC']);

        Assert::expect(count($users))->to_equal(6);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr6');
    }

    public function testAllByLimitAndPage()
    {
      $this->pending();
        $this->populateUserTable();
        $users = User::all(['limit'=>'2', 'page'=>2]);

        Assert::expect(count($users))->to_equal(2);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr3');
    }

    public function testFind()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::find(3);

        Assert::expect($user->username)->to_equal('Uzytkownik nr3');
    }

    public function testFindWithWrongId()
    {
      $this->pending();
        $this->populateUserTable();

        try {
            $user = User::find(100);
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal("Couldn't find User with id=100");
        }
    }

    public function testFindBy()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::findBy('email', 'user2@booklet.pl');

        Assert::expect($user->username)->to_equal('Uzytkownik nr2');
    }

    public function testFirst()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::first();
        Assert::expect($user->username)->to_equal('Uzytkownik nr1');
    }

    public function testFast()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::last();
        Assert::expect($user->username)->to_equal('Uzytkownik nr6');
    }

    public function testWhere()
    {
      $this->pending();
        $this->populateUserTable();
        $users = User::where("role = ?", ['role'=>'customer_service']);

        Assert::expect(count($users))->to_equal(4);
    }

    public function testWhereTwoWar()
    {
      $this->pending();
        $this->populateUserTable();
        $users = User::where("role = ? AND username = ?", ['role'=>'customer_service', 'username'=>'Uzytkownik nr3']);

        Assert::expect(count($users))->to_equal(1);
        Assert::expect($users[0]->username)->to_equal('Uzytkownik nr3');
    }

    public function testSave()
    {
        // TODO jak to przetestować?
    }

    public function testUpdate()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::find(3);
        $user->update(['username' => 'Nowa nazwa', 'email' => 'nowyemail@booklet.pl']);

        $user_reload = User::find(3);
        Assert::expect($user_reload->username)->to_equal('Nowa nazwa');
        Assert::expect($user_reload->email)->to_equal('nowyemail@booklet.pl');
    }

    public function testDestroy()
    {
      $this->pending();
        $this->populateUserTable();

        $user = User::find(3);
        Assert::expect($user->destroy())->to_equal(true);

        $users = User::all();
        Assert::expect(count($users))->to_equal(5);
    }

    public function testFnCreateDbObject()
    {
      $this->pending();
        $this->populateUserTable();
        $user = User::first();

        $user->username = 'Nowa nazwa uzytkownia';
        $user->email = 'user101@booklet.pl';

        $orm = new MysqlORM(null, $user);
        $db_obj = $orm->createDbObject();

        Assert::expect(ObjectUntils::toArray($db_obj))->to_equal(['username' => 'Nowa nazwa uzytkownia', 'email' => 'user101@booklet.pl']);

        $user->save();
        $user = User::first();
        Assert::expect($user->username)->to_equal('Nowa nazwa uzytkownia');
        Assert::expect($user->email)->to_equal('user101@booklet.pl');
    }

    public function testFnExtraParams()
    {
      $this->pending();
        $orm = new MysqlORM(null, new User);

        $sql1 = $orm->extraParams(['limit' => 1, 'order'=>'id DESC']);
        Assert::expect($sql1)->to_equal(' ORDER BY id DESC LIMIT 0, 1');

        $sql2 = $orm->extraParams(['limit' => 1]);
        Assert::expect($sql2)->to_equal(' LIMIT 0, 1');

        $sql3 = $orm->extraParams(['order'=>'name DESC']);
        Assert::expect($sql3)->to_equal(' ORDER BY name DESC');
    }
}
