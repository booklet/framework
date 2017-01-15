<?php
class JSONBuilderTest extends TesterCase
{
    public function testPresetPath()
    {
        $response_body = new JSONBuilder(['atrib1' => 'value1', 'atrib2' => 'value2'], 'tests/fixtures/jsonbuilder/test1.php');

        Assert::expect($response_body->data)->to_equal('{"atrib1":"value1","atrib2":"value2"}');
    }

    public function testCallFromController()
    {
        $fake_user = new stdClass();
        $fake_user->id = 1;
        $fake_user->email = "test@booklet.pl";
        $fake_user->username = "Nazwa uzytkownia";

        try {
            $response_body = new JSONBuilder(['token' => 'TOKEN', 'user' => $fake_user], 'SessionController::create');
        } catch (Exception $e) {
            Assert::expect($e->getMessage())->to_equal("Missing view file.");
        }

        // Assert::expect($response_body->view)->to_equal('app/views/sessions/create.php');
    }
}
