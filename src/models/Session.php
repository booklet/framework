<?php
class Session extends Model
{
    // model allowed attributes
    public $id;
    public $token;
    public $user_id;
    public $created_at;
    public $updated_at;

    // for database queries and for validation
    public function fields()
    {
        return [
            'id'              => ['type' => 'integer',  'default' => null],
            'user_id'         => ['type' => 'integer',  'default' => null, 'validations' => ['required']],
            'token'           => ['type' => 'string',   'default' => null, 'validations' => ['required', 'unique']],
            'created_at'      => ['type' => 'datetime', 'default' => null],
            'updated_at'      => ['type' => 'datetime', 'default' => null],
        ];
    }
}
