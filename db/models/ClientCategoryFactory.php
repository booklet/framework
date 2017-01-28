<?php
class ClientCategoryFactory
{
    public static function category(Array $overwrite_params = [])
    {
        $client_category = new ClientCategory(['name' => 'Category name']);

        foreach ($overwrite_params as $key => $value) {
            $client_category->$key = $value;
        }

        return $client_category;
    }
}
