<?php
class ClientFactory
{
    public static function client(Array $overwrite_params = [])
    {
        $client = new Client(['name' => 'Jhone Doe']);

        foreach ($overwrite_params as $key => $value) {
            $client->$key = $value;
        }

        return $client;
    }
}
