<?php
class TesterChildModel extends Model
{
    // model allowed attributes
    public $id;
    public $tester_parent_model_id;
    public $address;
    public $created_at;
    public $updated_at;

    // for database queries and for validation
    public function fields()
    {
        return [
            'id'                    => ['type' => 'integer',  'default' => null],
            'tester_parent_model_id' => ['type' => 'integer',  'default' => null, 'validations' => ['required']],
            'address'               => ['type' => 'string',   'default' => null, 'validations' => ['required', 'email', 'unique']],
            'created_at'            => ['type' => 'datetime', 'default' => null],
            'updated_at'            => ['type' => 'datetime', 'default' => null],
        ];
    }

    public static function relations()
    {
        return [
            'parent'      => ['relation' => 'belongs_to', 'class' => 'TesterParentModel'],
            'grandsons'   => ['relation' => 'has_many', 'class' => 'TesterGrandsonModel']
        ];
    }

    public function acceptsNestedAtributesFor()
    {
        return ['grandsons'];
    }
}
