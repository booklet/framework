<?php
class ClientCategory extends Model
{
    // model allowed attributes
    public $id;
    public $name;
    public $created_at;
    public $updated_at;

    // for database queries and for validation
    public function fields()
    {
        return [
            'id'              => ['type' => 'integer',  'default' => null],
            'name'            => ['type' => 'string',   'default' => null, 'validations' => ['required', 'max_length:190']],
            'created_at'      => ['type' => 'datetime', 'default' => null],
            'updated_at'      => ['type' => 'datetime', 'default' => null],
        ];
    }

    public static function relations()
    {
        return [
            'clients'         => ['relation' => 'has_and_belongs_to_many', 'class' => 'Client']
        ];
    }

    public static function customPluralizeClassName()
    {
        return 'ClientCategories';
    }



}
