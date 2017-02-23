<?php
class JSONBuilderTest extends TesterCase
{
    public function testPresetPath()
    {
        $response_body = new JSONBuilder(['atrib1' => 'value1', 'atrib2' => 'value2'], 'tests/fixtures/jsonbuilder/test1.php');

        Assert::expect($response_body->render())->to_equal(['a' => 'value1', 'b' => 'value2']);
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
    }
}
