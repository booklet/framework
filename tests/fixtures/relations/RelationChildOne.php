<?php
class RelationChildOne
{
    public static function relations()
    {
        return [
            'parent' => ['relation' => 'belongs_to', 'class' => 'RelationParent']
        ];
    }
}
