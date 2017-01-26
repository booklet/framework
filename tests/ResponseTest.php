<?php
class ResponseTest extends TesterCase
{
    public function testFnRaiseError()
    {

        $res = Response::raiseError(401, ['Missing login or password.']);
        Assert::expect($res)->to_equal('{"errors":[{"message":"Missing login or password."}]}');

        $res = Response::raiseError(401, ['Missing login or password.', 'Other error message']);
        Assert::expect($res)->to_equal('{"errors":[{"message":"Missing login or password."},{"message":"Other error message"}]}');
    }
}
