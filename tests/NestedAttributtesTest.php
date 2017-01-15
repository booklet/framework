<?php
class NestedAttributtesTest extends TesterCase
{
    use CreateUserWithSession;

    public function offtestCreateClientWithEmails()
    {
        list($user, $token) = $this->createUserWithSession();
        $data = ['client'=> [
                    'name'=>'Kontakt z emailem',
                    'name_search'=>'kontakt z emailem',
                    'emails_attributes' => [
                        ['address' => 'kontakt@com.pl'],
                        ['address' => 'biuro@com.pl'],
                    ]
                ]];
        $response = $this->request('POST', Config::get('api_url').'/clients', $token, $data);
        $response_body = json_decode($response->body);

        Assert::expect($response_body->name)->to_equal('Kontakt z emailem');

        $client = Client::find($response_body->id);

        Assert::expect(count($client->emails()))->to_equal(2);
    }

    public function offtestCreateClientWithEmailsWrong()
    {
        list($user, $token) = $this->createUserWithSession();
        $data = ['client'=> [
                    'name'=>'Kontakt z emailem',
                    'name_search'=>'kontakt z emailem',
                    'emails_attributes' => [
                        ['address' => 'kontakt@com.pl'],
                        ['address' => 'kontakt@com.pl'],
                    ]
                ]];
        $response = $this->request('POST', Config::get('api_url').'/clients', $token, $data);
        $response_body = json_decode($response->body);

        Assert::expect(count($response_body->errors))->to_equal(1);
        Assert::expect($response->body)->to_equal('{"errors":{"emails[1].address":["is not unique."]}}');
        Assert::expect($response->http_code)->to_equal(422);
    }
}
