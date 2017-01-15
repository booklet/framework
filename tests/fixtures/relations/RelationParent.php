<?php
class RelationParent
{
    public static function relations()
    {
        return [
            'childs' => ['relation' => 'has_many', 'class' => 'RelationChildOne']
        ];
    }
}
