<?php
class GlobalFunctionsTest extends TesterCase
{
    public function testH()
    {
        Assert::expect(h())->to_equal(null);
        Assert::expect(h('123'))->to_equal('123');
        Assert::expect(h('<script>alert("test")</script>'))->to_equal('&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;');

        try {
            Assert::expect(h(['test' => 'val']))->to_equal(null);
        } catch (Throwable $t) {
            Assert::expect($t->getMessage())->to_include_string('Argument 1 passed to h() must be of the type string, array given');
        }
    }
}
