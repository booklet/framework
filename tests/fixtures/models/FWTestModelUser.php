<?php
class FWTestModelUser extends Model
{
#    use UserRoles;
    use HasSecurePassword;

    function __construct(Array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setSecurePassword();
    }

    // for database queries (type) and for validation
    public function fields()
    {
        return [
            'id'                     => ['type' => 'integer',  'default' => null],
            'username'               => ['type' => 'string',   'default' => null, 'validations' => ['required', 'max_length:190']],
            'email'                  => ['type' => 'string',   'default' => null, 'validations' => ['required', 'email']],
            'role'                   => ['type' => 'string',   'default' => null, 'validations' => ['required']],
            'password_digest'        => ['type' => 'string',   'default' => null, 'validations' => ['required', 'password']],
#            'rebound_device_user_id' => ['type' => 'integer',  'default' => null],
            'created_at'             => ['type' => 'datetime', 'default' => null],
            'updated_at'             => ['type' => 'datetime', 'default' => null],
        ];
    }

#    public function afterSave()
#    {
#
#    }
#
#    // return user last (if user has more that one) session token
#    public function sessionToken()
#    {
#        $session = Session::where("user_id = ?", ['user_id' => $this->id]);
#        if (empty($session)) {
#            die("User has no sessions");
#        }
#        return end($session)->token; # end => get last element of array
#    }
#
#    // create and return new user auth token
#    public function createToken()
#    {
#        $token = SessionTokenGenerator::generate();
#        // new sesion and encode token
#        $hashed_token = SessionTokenGenerator::hashToken($token);
#        $session = new Session(['user_id' => $this->id, 'token' => $hashed_token]);
#        if ($session->save()) {
#            return $token;
#        }
#    }
#
#    // return user if login and password are correct
#    public static function autorize($login, $password)
#    {
#        $user = User::findBy('email', $login);
#        $password_digest = Util::encryptPassword($password);
#
#        if ($user && ($user->password_digest == $password_digest)) {
#            return $user;
#        } else {
#            return false;
#        }
#    }
#


}
