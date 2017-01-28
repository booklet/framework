<?php
class InflectorTest extends TesterCase
{
    public function testFnCeilTo()
    {
        $word = Inflector::pluralize('Client');
        Assert::expect($word)->to_equal("Clients");

        $word = Inflector::pluralize('ClientCategory');
        Assert::expect($word)->to_equal("ClientCategories");
    }


}
