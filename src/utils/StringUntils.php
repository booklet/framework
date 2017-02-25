<?php
class StringUntils
{
    /**
    * @param string oneTwoThreeFour
    * @return array ['one','Two','Three','Four']
    */
    public static function explodeCamelcase($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        return $matches[0];
    }

    /**
    * @param string one-two-three-four|one_two_three_four
    * @return string OneTwoThreeFour
    */
    public static function toCamelCase($string)
    {
        // dashes
        $string = str_replace('-', ' ', $string);
        // undescore
        $string = str_replace('_', ' ', $string);

        return str_replace(' ', '', ucwords($string));
    }

    public static function camelCaseToUnderscore($string)
    {
        $arr = StringUntils::explodeCamelcase($string);
        foreach ($arr as &$word) {
            $word = strtolower($word);
        }

        return implode('_',$arr);
    }

    /**
    * "tests/models/users_test.php" => UsersTest
    * "tests/models/UsersTest.php" => UsersTest
    */
    public static function fileNameFormPathToClass($string)
    {
        $file_name = pathinfo($string)['filename'];
        return StringUntils::toCamelCase($file_name);
    }


    public static function isInclude($source, $text)
    {
        if (strpos($source, $text) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * @param string „UTASZ-SPEED” Sp. z o.o.
    * @return string utaszspeedspzoo
    */
    public static function transliterate($string)
    {
        $normalized = self::removeAccentsAndDiacritics($string);
        $normalized = strtolower($normalized);
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);

        return $normalized;
    }

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function encryptPassword($password, $salt = '') {
        $salt = Config::get('password_salt') ?? $salt;

        return sha1($salt.$password);
    }

    public static function removeAccentsAndDiacritics($string) {
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: [:Punctuation:] Remove;');
        return $transliterator->transliterate($string);
    }

    public static function slug($string, $delimiter = '-') {
        $clean = (string) self::removeAccentsAndDiacritics($string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
}
