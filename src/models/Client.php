<?php
class Client extends Model
{
    // model allowed attributes
    public $id;
    public $name;
    public $name_search;
    public $created_at;
    public $updated_at;

    // for database queries and for validation
    public function fields()
    {
        return [
            'id'              => ['type' => 'integer',  'default' => null],
            'name'            => ['type' => 'string',   'default' => null, 'validations' => ['required', 'max_length:190']],
            'name_search'     => ['type' => 'string',   'default' => null, 'validations' => ['required', 'max_length:190']],
            'created_at'      => ['type' => 'datetime', 'default' => null],
            'updated_at'      => ['type' => 'datetime', 'default' => null],
        ];
    }

    public static function relations()
    {
        return [
            'emails'          => ['relation' => 'has_many', 'class' => 'ClientEmail'],
            'contacts'        => ['relation' => 'has_many', 'class' => 'Contact'],
            'categories'      => ['relation' => 'has_and_belongs_to_many', 'class' => 'ClientCategory']
        ];
    }

    public function acceptsNestedAtributesFor()
    {
        return ['emails', 'contacts'];
    }

    public function beforeValidate()
    {
        // to simplify search text: '„UTASZ-SPEED” Sp. z o.o.' to: 'utaszspeedspzoo'
        // use in search query this same transliterate method
        $this->name_search = Util::transliterate($this->name);
    }

    public function specialPropertis()
    {
        return ['categories'];
    }
}
