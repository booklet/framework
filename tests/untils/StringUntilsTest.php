<?php
class StringUntilsTest extends TesterCase
{
    public function testFnexplodeCamelcase()
    {
        $testdata = [
            'oneTwoThreeFour' => ['one', 'Two', 'Three', 'Four'],
            'oneTwo' => ['one', 'Two'],
            'onetwothreefour' => ['onetwothreefour'],
            'Some4Numbers234' => ['Some4', 'Numbers234'],
        ];

        foreach ($testdata as $from => $to) {
            $exploded_arr = StringUntils::explodeCamelcase($from);
            Assert::expect($exploded_arr)->to_equal($to);
        }
    }

    public function testFntoCamelCase()
    {
        $testdata = [
            'one-two-three-four' => 'OneTwoThreeFour',
            'one_two_three_four' => 'OneTwoThreeFour',
            'OneTwoThreeFour' => 'OneTwoThreeFour',
        ];

        foreach ($testdata as $from => $to) {
            $camel_case = StringUntils::toCamelCase($from);
            Assert::expect($camel_case)->to_equal($to);
        }
    }

    public function testFnfileNameFormPathToClass()
    {
        $testdata = [
            'tests/models/users_test.php' => 'UsersTest',
            'tests/models/UsersTest.php' => 'UsersTest',
            'UsersTest.php' => 'UsersTest',
        ];

        foreach ($testdata as $from => $to) {
            $file_name = StringUntils::fileNameFormPathToClass($from);
            Assert::expect($file_name)->to_equal($to);
        }
    }

    public function testFnStringInclude()
    {
        $is_include = StringUntils::isInclude('my test string', 'test');
        Assert::expect($is_include)->to_equal(true);

        $is_include = StringUntils::isInclude('my test string', 'notexist');
        Assert::expect($is_include)->to_equal(false);
    }

    public function testIsContainsNumbers()
    {
        $is_contains_numbers = StringUntils::isContainsNumbers('no # numbers text % ]');
        Assert::expect($is_contains_numbers)->to_equal(false);

        $is_contains_numbers = StringUntils::isContainsNumbers('text with 123 digits');
        Assert::expect($is_contains_numbers)->to_equal(true);
    }

    public function testIsContainsLetters()
    {
        $is_contains_letters = StringUntils::isContainsLetters('1234 $%^ () 09');
        Assert::expect($is_contains_letters)->to_equal(false);

        $is_contains_letters = StringUntils::isContainsLetters('123 text With letters');
        Assert::expect($is_contains_letters)->to_equal(true);
    }

    public function testFnTransliterate()
    {
        $testdata = [
            '2015-11-27 15:02:37.820652' => '20151127150237820652',
            'Валерий Глошанчук' => 'valerijglosancuk',
            '„UTASZ-SPEED” Sp. z o.o.' => 'utaszspeedspzoo',
            ' „AL-JAN” Janusz Kluczewski' => 'aljanjanuszkluczewski',
            '“AZIS” -Mining Service Sp. z o.o.' => 'azisminingservicespzoo',
            'F.H.U. \"MAX-SPORT\"' => 'fhumaxsport',
            'N-ctwo Gł.Bród - ochl - Dorota Ławreszuk' => 'nctwoglbrodochldorotalawreszuk',
            'Black&Tan kontakt' => 'blacktankontakt',
            'STONE+GLASS' => 'stoneglass',
            'Półśkię żńąki' => 'polskieznaki',
        ];

        foreach ($testdata as $from => $to) {
            $clear_text = StringUntils::transliterate($from);
            Assert::expect($clear_text)->to_equal($to);
        }
    }

    public function testGenerateRandomString()
    {
        $string = StringUntils::generateRandomString(50);
        Assert::expect(strlen($string))->to_equal(50);
    }

    public function testGenerateRandomDigitsString()
    {
        $string = StringUntils::generateRandomDigitsString(10);
        Assert::expect(preg_match_all('/[0-9]/', $string))->to_equal(10);
    }

    public static function generateRandomDigitsString($length = 10)
    {
        $characters = '0123456789';
        $randomString = self::generateRandom($length, $characters);

        return $randomString;
    }

    public function testFncamelCaseToUnderscore()
    {
        $testdata = [
            'simpleTest' => 'simple_test',
            'easy' => 'easy',
            'HTML' => 'html',
            'simpleXML' => 'simple_xml',
            'PDFLoad' => 'pdf_load',
            'startMIDDLELast' => 'start_middle_last',
            'AString' => 'a_string',
            'Some4Numbers234' => 'some4_numbers234',
            'TEST123String' => 'test123_string',
        ];

        // TODO
        // add extra cases:
        // return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
        // hello_world => hello_world
        // hello__world => hello__world
        // _hello_world_ => _hello_world_
        // hello_World => hello_world
        // HelloWorld => hello_world
        // helloWorldFoo => hello_world_foo
        // hello-world => hello-world
        // myHTMLFiLe => my_html_fi_le
        // aBaBaB => a_ba_ba_b
        // BaBaBa => ba_ba_ba
        // libC => lib_c

        foreach ($testdata as $camel_case => $underscore) {
            $underscored_camel_case = StringUntils::camelCaseToUnderscore($camel_case);
            Assert::expect($underscored_camel_case)->to_equal($underscore);
        }
    }

    public function testRemoveAccentsAndDiacritics()
    {
        $normalize = StringUntils::removeAccentsAndDiacritics('Śtręët ņąmę Ćity, ńąmę Čómpąńy-ńąmę Ċõntaçt/ňamê Γεια σου κόσμε');
        Assert::expect($normalize)->to_equal('Street name City, name Company-name Contact/name Geia sou kosme');
    }

    public function testSlug()
    {
        $normalize = StringUntils::slug('Śtręët  ņąmę Ćity ńąmę /_Čómpąńy ńąmę Ċõntaçt ňamê');
        Assert::expect($normalize)->to_equal('street-name-city-name-company-name-contact-name');
    }

    public function testSanitizeFileName()
    {
        $sanitize = StringUntils::sanitizeFileName('Pł\\ik $ź_pÓł\'śki/mi-"&-(Żnąk*^ąmi)_.02?.pdf');
        Assert::expect($sanitize)->to_equal('Plik_z_pOlskimi_Znakami_.02.pdf');
    }

    public function testStartsWith()
    {
        Assert::expect(StringUntils::areStartsWith('ItemsPush', 'Push'))->to_equal(false);
        Assert::expect(StringUntils::areStartsWith('ItemsPush', 'Items'))->to_equal(true);
        Assert::expect(StringUntils::areStartsWith('ItemsPush', 'ItemsPush'))->to_equal(true);
        Assert::expect(StringUntils::areStartsWith('ItemsPush', 'ItemsPushPush'))->to_equal(false);
    }

    public function testEndsWith()
    {
        Assert::expect(StringUntils::areEndsWith('ItemsPush', 'Push'))->to_equal(true);
        Assert::expect(StringUntils::areEndsWith('ItemsPush', 'Items'))->to_equal(false);
        Assert::expect(StringUntils::areEndsWith('ItemsPush', 'ItemsPush'))->to_equal(true);
        Assert::expect(StringUntils::areEndsWith('ItemsPush', 'ItemsPushPush'))->to_equal(false);
    }

    public function testRemoveWordsShorterThan()
    {
        Assert::expect(StringUntils::removeWordsShorterThan('Naklejki na słoiki'))->to_equal('Naklejki słoiki');
        Assert::expect(StringUntils::removeWordsShorterThan('Co to za naklejki na słoiki hmm'))->to_equal('naklejki słoiki');
        Assert::expect(StringUntils::removeWordsShorterThan('1 a aa aaa 99 aaaa aaa aaaaa aaa aa a 0'))->to_equal('aaaa aaaaa');
    }

    public function testTruncate()
    {
        Assert::expect(StringUntils::truncate('Co to za naklejki na słoiki hmm', 19, ''))->to_equal('Co to za naklejki');
        Assert::expect(StringUntils::truncate('Co to za naklejki', 20))->to_equal('Co to za naklejki');
        Assert::expect(StringUntils::truncate('Limiting or Truncating strings using PHP', 37))->to_equal('Limiting or Truncating strings using...');
    }
}
