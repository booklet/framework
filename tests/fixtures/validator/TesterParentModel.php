<?php
class TesterParentModel extends Model
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
            'name'            => ['type' => 'string',   'default' => null, 'validations' => ['required']],
            'created_at'      => ['type' => 'datetime', 'default' => null],
            'updated_at'      => ['type' => 'datetime', 'default' => null],
        ];
    }

    public static function relations()
    {
        return [
            'childs'          => ['relation' => 'has_many', 'class' => 'TesterChildModel']
        ];
    }

    public function acceptsNestedAtributesFor()
    {
        return ['childs'];
    }
}
