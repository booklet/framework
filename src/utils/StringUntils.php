<?php
trait StringUntils
{
    // oneTwoThreeFour => ['one','Two','Three','Four']
    public static function explodeCamelcaseString($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        return $matches[0];
    }

    // 'one-two-three-four', 'one_two_three_four' => 'OneTwoThreeFour'
    public static function toCamelCaseString($string)
    {
        // dashes
        $string = str_replace('-', ' ', $string);
        // undescore
        $string = str_replace('_', ' ', $string);

        return str_replace(' ', '', ucwords($string));
    }

    public static function camelCaseStringToUnderscore($string)
    {
        $arr = Util::explodeCamelcaseString($string);
        foreach ($arr as &$word)
            $word = strtolower($word);

        return implode('_',$arr);
    }

    // "tests/models/users_test.php" => UsersTest
    // "tests/models/UsersTest.php" => UsersTest
    public static function fileNameFormPathToClass($string)
    {
        $file_name = pathinfo($string)['filename'];
        return Util::toCamelCaseString($file_name);
    }


    public static function isStringInclude($source, $text)
    {
        if (strpos($source, $text) !== false) {
            return true;
        } else {
            return false;
        }
    }

    // „UTASZ-SPEED” Sp. z o.o. => utaszspeedspzoo
    public static function transliterate($string)
    {
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: [:Punctuation:] Remove;');
        $normalized = $transliterator->transliterate($string);
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
}
