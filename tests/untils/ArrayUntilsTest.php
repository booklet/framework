<?php
class ArrayUntilsTest extends TesterCase
{
    public function testFnIsAssocArray()
    {
        $is_array = Util::isAssocArray(['item1', 'item2', 'item3']);
        Assert::expect($is_array)->to_equal(false);

        $is_array = Util::isAssocArray(['attr1'=>'item1', 'attr2'=>'item2', 'attr2'=>'item3']);
        Assert::expect($is_array)->to_equal(true);
    }
}
