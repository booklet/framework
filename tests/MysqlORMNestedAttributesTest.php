<?php
include_once 'tests/fixtures/support/ParentChildGrandsonMysqlTables.php';

class MysqlORMNestedAttributesTest extends TesterCase
{
    use ParentChildGrandsonMysqlTables;

    public function testSaveObjectWithNestedAttributes()
    {

        $this->createParentChildGrandsonMysqlTables();

        $data = [
            'name'=>'Parent name',
            'childs_attributes' => [
                ['address' => 'email1@test.com'],
                ['address' => 'email2@test.com'],
            ]
        ];

        $parent = new TesterParentModel($data);
        $parent->save();

        Assert::expect($parent->id)->to_equal(1);
        Assert::expect(count($parent->childs()))->to_equal(2);

        $childs = TesterChildModel::where('tester_parent_model_id = ?', ['tester_parent_model_id' => 1]);
        Assert::expect(count($childs))->to_equal(2);
    }



    public function testSaveObjectWithWrongNestedAttributes()
    {

        $this->createParentChildGrandsonMysqlTables();

        $data = [
            'name'=>'Parent name',
            'childs_attributes' => [
                ['address' => 'email1@test.com'],
                ['address' => ''],
                ['address' => 'email1@test.com'],
            ]
        ];

        $parent = new TesterParentModel($data);
        $parent->save();

        Assert::expect($parent->id)->to_equal(null);
        Assert::expect(count($parent->errors))->to_equal(3);
    }


    public function testAddChildsToExistObjects()
    {

        $this->createParentChildGrandsonMysqlTables();

        $parent = new TesterParentModel(['name' => 'Parent name']);
        $parent->save();

        $child1 = new TesterChildModel(['address' => 'email1@test.com', 'tester_parent_model_id' => $parent->id]);
        $child1->save();

        $child2 = new TesterChildModel(['address' => 'email2@test.com', 'tester_parent_model_id' => $parent->id]);
        $child2->save();

        $data = [
            'id' => $parent->id,
            'name'=>'Parent name',
            'childs_attributes' => [
                ['id' => $child1->id, 'address' => 'email1@test.com'],
                ['id' => $child2->id, 'address' => 'email2@test.com'],
                ['address' => 'email3@test.com'],
            ]
        ];

        $parent->update($data);

        Assert::expect($parent->id)->to_equal(1);
        Assert::expect(count($parent->childs()))->to_equal(3);

        $childs = TesterChildModel::where('tester_parent_model_id = ?', ['tester_parent_model_id' => 1]);
        Assert::expect(count($childs))->to_equal(3);
    }

    public function testDeleteFormNestedObjects()
    {

        $this->createParentChildGrandsonMysqlTables();

        $parent = new TesterParentModel(['name' => 'Parent name']);
        $parent->save();

        $child1 = new TesterChildModel(['address' => 'email1@test.com', 'tester_parent_model_id' => $parent->id]);
        $child1->save();

        $child2 = new TesterChildModel(['address' => 'email2@test.com', 'tester_parent_model_id' => $parent->id]);
        $child2->save();

        $data = [
            'id' => $parent->id,
            'name'=>'Parent name',
            'childs_attributes' => [
                ['id' => $child1->id, 'address' => 'email1@test.com'],
                ['id' => $child2->id, 'address' => 'email2@test.com', '_destroy' => '1'],
            ]
        ];

        $parent->update($data);

        Assert::expect($parent->id)->to_equal(1);
        Assert::expect(count($parent->childs()))->to_equal(1);

        $childs = TesterChildModel::where('tester_parent_model_id = ?', ['tester_parent_model_id' => 1]);
        Assert::expect(count($childs))->to_equal(1);
    }


    public function testUpdateNestedObjects()
    {

        $this->createParentChildGrandsonMysqlTables();

        $parent = new TesterParentModel(['name' => 'Parent name']);
        $parent->save();

        $child1 = new TesterChildModel(['address' => 'email1@test.com', 'tester_parent_model_id' => $parent->id]);
        $child1->save();

        $child2 = new TesterChildModel(['address' => 'email2@test.com', 'tester_parent_model_id' => $parent->id]);
        $child2->save();

        $data = [
            'id' => $parent->id,
            'name'=>'Parent name',
            'childs_attributes' => [
                ['id' => $child1->id, 'address' => 'email1@test.com'],
                ['id' => $child2->id, 'address' => 'new_email2@test.com'],
            ]
        ];

        $parent->update($data);

        Assert::expect($parent->id)->to_equal(1);
        Assert::expect(count($parent->childs()))->to_equal(2);

        $childs = TesterChildModel::where('tester_parent_model_id = ?', ['tester_parent_model_id' => 1]);
        Assert::expect(count($childs))->to_equal(2);

        Assert::expect( $childs[1]->address)->to_equal('new_email2@test.com');

        $this->dropDownParentChildGrandsonMysqlTables();
    }
}
