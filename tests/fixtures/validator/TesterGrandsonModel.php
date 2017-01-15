<?php
class TesterGrandsonModel extends Model
{
    // model allowed attributes
    public $id;
    public $tester_child_model_id;
    public $description;
    public $created_at;
    public $updated_at;

    // for database queries and for validation
    public function fields()
    {
        return [
            'id'                    => ['type' => 'integer',  'default' => null],
            'tester_child_model_id'  => ['type' => 'integer',  'default' => null, 'validations' => ['required']],
            'description'           => ['type' => 'text',     'default' => null, 'validations' => ['required']],
            'created_at'            => ['type' => 'datetime', 'default' => null],
            'updated_at'            => ['type' => 'datetime', 'default' => null],
        ];
    }

    public static function relations()
    {
        return [
            'parent'                => ['relation' => 'belongs_to', 'class' => 'TesterChildModel']
        ];
    }
}
