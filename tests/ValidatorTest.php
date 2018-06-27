<?php
require_once 'tests/fixtures/models/FWTestModelUser.php';
require_once 'tests/fixtures/models/FWTestCustomModel.php';

class ValidatorTest extends TesterCase
{
    public function testValidRequired()
    {
        $obj = new stdClass();
        $obj->name = 'Name';

        $rules = ['name' => ['required']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->name = null;
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['name'][0])->to_equal('is required.');

        // test fix
        $obj->name = 'ok name';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->name = '';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['name'][0])->to_equal('is required.'); // TODO nie $valid a obiekt
    }

    public function testValidAllowNull()
    {
        $obj = new stdClass();
        $obj->quanity = null;
        $rules = ['quanity' => ['greater_than_or_equal_to:2:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 10;
        $rules = ['quanity' => ['greater_than_or_equal_to:2:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 1;
        $rules = ['quanity' => ['greater_than_or_equal_to:2:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);

        $obj->quanity = '';
        $rules = ['quanity' => ['greater_than_or_equal_to:2:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);

        $obj->quanity = null;
        $rules = ['quanity' => ['greater_than_or_equal_to:2:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 0;
        $rules = ['quanity' => ['greater_than_or_equal_to:0:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 0;
        $rules = ['quanity' => ['greater_than_or_equal_to:1:allow_null']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
    }

    public function testValidTypeStringText()
    {
        $obj = new stdClass();
        $obj->name = 'Name';

        $rules = ['name' => ['type:string']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->name = 12345;
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['name'][0])->to_equal('is not string type.');
    }

    public function testValidTypeInteger()
    {
        $obj = new stdClass();
        $obj->parent_id = 1;

        $rules = ['parent_id' => ['type:integer']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->parent_id = '12345';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->parent_id = 'onetwo';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['parent_id'][0])->to_equal('is not integer type.');
    }

    public function testValidTypeDouble()
    {
        $obj = new stdClass();
        $obj->price = 99.99;

        $rules = ['price' => ['type:double']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->price = '99.99';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->price = 'onetwo';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['price'][0])->to_equal('is not double type.');
    }

    public function testValidTypeDatetime()
    {
        $obj = new stdClass();
        $obj->created_at = '2016-10-14 11:09:29';

        $rules = ['created_at' => ['type:datetime']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->created_at = '2016-10-14';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['created_at'][0])->to_equal('is not datetime type.');
    }

    public function testValidMaxLength()
    {
        $obj = new stdClass();
        $obj->name = '12345678901234567890';

        $rules = ['name' => ['max_length:20']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->name = '12345678901';
        $rules = ['name' => ['max_length:10']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['name'][0])->to_equal('is too long (max 10).');
    }

    public function testValidMaxLengthEmoji()
    {
        $obj = new stdClass();
        $obj->name = '👍🏿✌😍🇺🇸';

        // success
        $rules = ['name' => ['max_length:6']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $rules = ['name' => ['max_length:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
    }

    public function testValidGreaterThanOrEqualTo()
    {
        $obj = new stdClass();
        $obj->quanity = 6;
        $rules = ['quanity' => ['greater_than_or_equal_to:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 5;
        $rules = ['quanity' => ['greater_than_or_equal_to:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->quanity = 4;
        $rules = ['quanity' => ['greater_than_or_equal_to:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['quanity'][0])->to_equal('is low value (min 5).');

        $obj->quanity = 0.05;
        $rules = ['quanity' => ['greater_than_or_equal_to:0.01']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 0.001;
        $rules = ['quanity' => ['greater_than_or_equal_to:0.01']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['quanity'][0])->to_equal('is low value (min 0.01).');

        $obj->quanity = 0;
        $rules = ['quanity' => ['greater_than_or_equal_to:0.01']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['quanity'][0])->to_equal('is low value (min 0.01).');
    }

    public function testValidLessThanOrEqualTo()
    {
        $obj = new stdClass();
        $obj->quanity = 4;
        $rules = ['quanity' => ['less_than_or_equal_to:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->quanity = 5;
        $rules = ['quanity' => ['less_than_or_equal_to:5']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->quanity = 5;
        $rules = ['quanity' => ['less_than_or_equal_to:4']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['quanity'][0])->to_equal('is to high value (max 4).');
    }

    public function testValidEmail()
    {
        $obj = new stdClass();
        $rules = ['user_email' => ['email']];

        $valid_emails = [
            'name.lastname@domain.com',
            'a@bar.com',
            'aaa@[123.123.123.123]',
            'a-b@bar.com',
            '+@b.com',
            'a@b.co-foo.uk',
            '"hello my name is"@stutter.com',
            '"Test \"Fail\" Ing"@example.com',
            'valid@special.museum',
            '"Abc\@def"@example.com',
            '"Fred Bloggs"@example.com',
            '"Joe\Blow"@example.com',
            '"Abc@def"@example.com',
            'customer/department=shipping@example.com',
            '$A12345@example.com',
            '!def!xyz%abc@example.com',
            '_somename@example.com',
            'Test \ Folding \ Whitespace@example.com',
//            'HM2Kinsists@(that comments are allowed)this.is.ok',
            'user%uucp!path@somehost.edu',
            'kamil@blacklight.digital',
        ];

        foreach ($valid_emails as $email) {
            $obj->user_email = $email;
            $valid = new Validator($obj, $rules);

            Assert::expect($valid->isValid())->to_equal(true);
        }

        $invalid_emails = [
            '.@',
//            'a@b',
            '@bar.com',
            '@@bar.com',
            'aaa.com',
            'aaa@.com',
            'aaa@.123',
//            'aaa@[123.123.123.123]a',
//            'aaa@[123.123.123.333]',
            'a@bar.com.',
//            'a@bar',
//            '+@b.c',
            'a@-b.com',
            'a@b-.com',
            '-@..com',
            '-@a..com',
            'invalid@special.museum-',
//            'shaitan@my-domain.thisisminekthx',
            'test@...........com',
//            'foobar@192.168.0.1',
        ];

        foreach ($invalid_emails as $email) {
            $obj->user_email = $email;
            $valid = new Validator($obj, $rules);

            Assert::expect($valid->isValid())->to_equal(false);
        }
    }

    //    public function testValidUnique()
    //    {
    //        // how test uniques witout use database
    //        $this->pending();
    //        $user1 = UserFactory::user();
    //        $user1->save();
    //
    //        $rules = ['username' => ['unique']];
    //
    //        // success valid save object?
    //        $valid = new Validator($user1, $rules);
    //
    //        Assert::expect($valid->isValid())->to_equal(true);
    //
    //        $user2 = UserFactory::user();
    //        $valid = new Validator($user2, $rules);
    //
    //        Assert::expect($valid->isValid())->to_equal(false);
    //        Assert::expect($user2->errors['username'][0])->to_equal('is not unique.');
    //    }

    public function testValidIn()
    {
        $obj = new stdClass();
        $rules = ['role' => ['in:admin,customer_service,production_worker,web,client,agency']];

        // success
        $obj->role = 'customer_service';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->role = 'xxx';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
    }

    public function testValidPassword()
    {
        $user = new FWTestModelUser(['username' => 'Name', 'email' => 'test@booklet.pl', 'role' => 'admin', 'password' => '', 'password_confirmation' => '']);
        $user->isValid();
        Assert::expect($user->errors['password'][0])->to_equal('cannot be blank.');

        $user = new FWTestModelUser(['username' => 'Name', 'email' => 'test@booklet.pl', 'role' => 'admin', 'password' => 'haslo', 'password_confirmation' => '']);
        $user->isValid();
        Assert::expect($user->errors['password'][0])->to_equal('confirmation cannot be blank.');
        Assert::expect($user->errors['password'][1])->to_equal('confirmation doesn\'t match.');

        $user = new FWTestModelUser(['username' => 'Name', 'email' => 'test@booklet.pl', 'role' => 'admin', 'password' => 'haslo', 'password_confirmation' => 'HASLO']);
        $user->isValid();
        Assert::expect($user->errors['password'][0])->to_equal('confirmation doesn\'t match.');

        $user = new FWTestModelUser(['username' => 'Name', 'email' => 'test@booklet.pl', 'role' => 'admin', 'password' => 'haslo', 'password_confirmation' => 'haslo']);
        Assert::expect($user->isValid())->to_equal(true);
        Assert::expect(strlen($user->password_digest))->to_equal(40);
    }

    public function testValidZipCode()
    {
        $obj = new stdClass();
        $obj->zip = '00-123';
        $rules = ['zip' => ['zip_code']];
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->zip = '00123';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->zip = '000';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['zip'][0])->to_equal('is not zip code.');

        $obj->zip = 12345;
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);
    }

    public function testValidTypeBoolean()
    {
        $obj = new stdClass();
        $obj->bool_field = 1;

        $rules = ['bool_field' => ['type:boolean']];

        // success
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        $obj->bool_field = 0;
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);

        // error
        $obj->bool_field = '0';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(true);
        //Assert::expect($valid->errors()['bool_field'][0])->to_equal('is not boolean type.');

        $obj->bool_field = 'true';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['bool_field'][0])->to_equal('is not boolean type.');

        $obj->bool_field = 'yes';
        $valid = new Validator($obj, $rules);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['bool_field'][0])->to_equal('is not boolean type.');
    }

    public function testCustomValidators()
    {
        $test_calas = new FWTestCustomModel();
        $test_calas->variable = 0;

        // success
        $valid = new Validator($test_calas, []);

        Assert::expect($valid->isValid())->to_equal(false);
        Assert::expect($valid->errors()['variable'][0])->to_equal('must be greater than 0.');
    }
}
