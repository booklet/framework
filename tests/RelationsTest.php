<?php
include 'tests/fixtures/relations/RelationParent.php';
include 'tests/fixtures/relations/RelationChildOne.php';

class RelationsTest extends TesterCase
{
    use CreateUserWithSession;

    public function testHasManyRelation()
    {

        $parent = new RelationParent();
        $parent->id = 1;

        $relation = new Relations($parent, 'childs', []);

        Assert::expect($relation->sqlQuery())->to_equal('relation_parent_id = ?');
        Assert::expect($relation->sqlParams())->to_equal(['relation_parent_id' => 1]);
        Assert::expect($relation->sqlModel())->to_equal('RelationChildOne');
    }

    public function testWrongHasManyRelation()
    {

        $parent = new RelationParent();
        $parent->id = 1;

        $relation = new Relations($parent, 'no_exist_relation', []);

        Assert::expect($relation->sqlQuery())->to_equal(false);
        Assert::expect($relation->sqlParams())->to_equal(false);
        Assert::expect($relation->sqlModel())->to_equal(false);
    }

    public function testBelongsToRelation()
    {

        $parent = new RelationParent();
        $parent->id = 11;

        $child = new RelationChildOne();
        $child->id = 22;
        $child->parent_id = 11;

        $relation = new Relations($child, 'parent', []);

        Assert::expect($relation->sqlQuery())->to_equal('id = ?');
        Assert::expect($relation->sqlParams())->to_equal(['id' => 11]);
        Assert::expect($relation->sqlModel())->to_equal('RelationParent');
    }

    public function testIsRelationMethod()
    {
        $parent = new RelationParent();
        $is_relation_method = Relations::isRelationMethod($parent, 'childs');

        Assert::expect($is_relation_method)->to_equal(true);

        $is_relation_method = Relations::isRelationMethod($parent, 'no_exist_relation');

        Assert::expect($is_relation_method)->to_equal(false);
    }

    public function testHABTMGetObjects()
    {

        $client1 = ClientFactory::client(['name' => 'Client 1']);
        $client1->save();
        $client2 = ClientFactory::client(['name' => 'Client 2']);
        $client2->save();

        $category1 = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category1->save();
        $category2 = ClientCategoryFactory::category(['name' => 'Category 2']);
        $category2->save();

        // add object
        $client1->categoriesPush($category1);
        $client1->categoriesPush($category2);

        $c1 = Client::find(1);
        Assert::expect(count( $c1->categories() ))->to_equal(2);

        $client2->categoriesPush([$category1, $category2]);

        $c2 = Client::find(2);
        Assert::expect(count( $c2->categories() ))->to_equal(2);

        $c2->categoriesPush($category1);
        Assert::expect(count($c2->categories()))->to_equal(2);

        // remove object
        $c1 = Client::find(1);
        Assert::expect(count($c1->categories()))->to_equal(2);

        $c1->categoriesDelete($category1);
        Assert::expect(count($c1->categories()))->to_equal(1);

        $c2 = Client::find(2);
        Assert::expect(count($c2->categories()))->to_equal(2);

        $c2->categoriesDelete([$category1, $category2]);
        Assert::expect(count($c2->categories()))->to_equal(0);
    }

    public function testHABTMCheckTypeObjects()
    {

        $client = ClientFactory::client(['name' => 'Client 1']);
        $client->save();

        $category = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category->save();

        $wrong_object_type = ClientFactory::client(['name' => 'Wrong object type']);
        $wrong_object_type->save();

        // add object
        try {
            $client->categoriesPush($wrong_object_type);
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal("Wrong push object class, expect ClientCategory got Client");
        }

        $c1 = Client::find(1);
        Assert::expect(count($c1->categories()))->to_equal(0);

        // remove object
        $c1 = Client::find(1);
        $c1->categoriesPush($category);
        Assert::expect(count($c1->categories()))->to_equal(1);

        try {
            $client->categoriesDelete($wrong_object_type);
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal("Wrong delete object class, expect ClientCategory got Client");
        }
    }

    public function testHABTMApiAddCategoriesToObject()
    {
      $this->pending();
        $client1 = ClientFactory::client(['name' => 'Client name']);
        $client1->save();

        $category1 = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category1->save();
        $category2 = ClientCategoryFactory::category(['name' => 'Category 2']);
        $category2->save();

        $client1->categoriesPush($category1);

        list($user, $token) = $this->createUserWithSession();
        $data = ['client' => [
                    'name' => 'Client new name',
                    'categories_ids' => ['1', '2']
                ]];
        $response = $this->request('PUT', Config::get('api_url').'/clients/1', $token, $data);
        $response_body = json_decode($response->body);

        $client1 = Client::find($response_body->id);

        Assert::expect($client1->name)->to_equal('Client new name');
        Assert::expect(count($client1->categories()))->to_equal(2);
    }


    public function testHABTMApiRemoveCategoriesToObject()
    {
      $this->pending();
        $client1 = ClientFactory::client(['name' => 'Client name']);
        $client1->save();

        $category1 = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category1->save();
        $category2 = ClientCategoryFactory::category(['name' => 'Category 2']);
        $category2->save();
        $category3 = ClientCategoryFactory::category(['name' => 'Category 3']);
        $category3->save();

        $client1->categoriesPush($category1);
        $client1->categoriesPush($category2);

        list($user, $token) = $this->createUserWithSession();
        $data = ['client' => [
                    'name' => 'Client new name',
                    'categories_ids' => ['1', '3']
                ]];
        $response = $this->request('PUT', Config::get('api_url').'/clients/1', $token, $data);
        $response_body = json_decode($response->body);

        $client1 = Client::find($response_body->id);

        Assert::expect($client1->name)->to_equal('Client new name');
        Assert::expect(count($client1->categories()))->to_equal(2);

        Assert::expect($client1->categories()[0]->id)->to_equal(1);
        Assert::expect($client1->categories()[1]->id)->to_equal(3);
    }

    public function testHABTMApiCreateObjectWithCategories()
    {
      $this->pending();
        $category1 = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category1->save();
        $category2 = ClientCategoryFactory::category(['name' => 'Category 2']);
        $category2->save();

        list($user, $token) = $this->createUserWithSession();
        $data = ['client' => [
                    'name' => 'Client name',
                    'categories_ids' => ['1', '2']
                ]];
        $response = $this->request('POST', Config::get('api_url').'/clients', $token, $data);
        $response_body = json_decode($response->body);

        $client1 = Client::find($response_body->id);

        Assert::expect($client1->name)->to_equal('Client name');
        Assert::expect(count($client1->categories()))->to_equal(2);

        Assert::expect($client1->categories()[0]->id)->to_equal(1);
        Assert::expect($client1->categories()[1]->id)->to_equal(2);
    }

    public function testHABTMApiRemoveCategoriesFromObject()
    {
      $this->pending();
        $client1 = ClientFactory::client(['name' => 'Client name']);
        $client1->save();

        $category1 = ClientCategoryFactory::category(['name' => 'Category 1']);
        $category1->save();
        $category2 = ClientCategoryFactory::category(['name' => 'Category 2']);
        $category2->save();

        $client1->categoriesPush($category1);
        $client1->categoriesPush($category2);

        Assert::expect(count($client1->categories()))->to_equal(2);

        list($user, $token) = $this->createUserWithSession();
        $data = ['client'=> [
                    'name' => 'Client new name',
                    'categories_ids' => []
                ]];
        $response = $this->request('PUT', Config::get('api_url').'/clients/1', $token, $data);
        $response_body = json_decode($response->body);

        $client1 = Client::find($response_body->id);

        Assert::expect($client1->name)->to_equal('Client new name');
        Assert::expect(count($client1->categories()))->to_equal(0);
    }
}
