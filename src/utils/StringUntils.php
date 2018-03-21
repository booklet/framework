<?php
class StringUntils
{
    /**
     * @param string oneTwoThreeFour
     *
     * @return array ['one','Two','Three','Four']
     */
    public static function explodeCamelcase($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        return $matches[0];
    }

    /**
     * @param string one-two-three-four|one_two_three_four
     *
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
        $arr = self::explodeCamelcase($string);
        foreach ($arr as &$word) {
            $word = strtolower($word);
        }

        return implode('_', $arr);
    }

    /**
     * "tests/models/users_test.php" => UsersTest
     * "tests/models/UsersTest.php" => UsersTest.
     */
    public static function fileNameFormPathToClass($string)
    {
        $file_name = pathinfo($string)['filename'];

        return self::toCamelCase($file_name);
    }

    public static function isInclude($source, $text)
    {
        if (strpos($source, $text) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public static function isContainsNumbers($string)
    {
        return preg_match('/\\d/', $string) > 0;
    }

    public static function isContainsLetters($string)
    {
        return preg_match('/[A-Za-z]/', $string) > 0;
    }

    /**
     * @param string „UTASZ-SPEED” Sp. z o.o.
     *
     * @return string utaszspeedspzoo
     */
    public static function transliterate($string)
    {
        $normalized = self::removeAccentsAndDiacritics($string);
        $normalized = strtolower($normalized);
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);

        return $normalized;
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = self::generateRandom($length, $characters);

        return $randomString;
    }

    public static function generateRandomDigitsString($length = 10)
    {
        $characters = '0123456789';
        $randomString = self::generateRandom($length, $characters);

        return $randomString;
    }

    public static function encryptPassword($password, $salt = '')
    {
        $salt = Config::get('password_salt') ?? $salt;

        return sha1($salt . $password);
    }

    public static function removeAccentsAndDiacritics($string)
    {
        // Earlier version ':: Any-Latin; :: Latin-ASCII; :: [:Punctuation:] Remove;'
        // remove characters like ,-/ , but we want only remove diacritics
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII;');

        return $transliterator->transliterate($string);
    }

    public static function slug($string, $delimiter = '-')
    {
        $clean = (string) self::removeAccentsAndDiacritics($string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    // Pł\\ik ź_pół'śki/mi-&-(Żnąkąmi)_.02?.pdf => Plik_z_polskimi_Znakami_.02.pdf
    public static function sanitizeFileName($string)
    {
        $string = str_replace(' ', '_', $string);
        $string = str_replace('-', '_', $string);
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII;');
        $string = $transliterator->transliterate($string);
        $string = preg_replace('/[^A-Za-z0-9._]/', '', $string);
        $string = preg_replace('!\_+!', '_', $string); // multiple ___ to single _

        return $string;
    }

    public static function areStartsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return substr($haystack, 0, $length) === $needle;
    }

    public static function areEndsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 || (substr($haystack, -$length) === $needle);
    }

    private static function generateRandom($length = 10, $characters)
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function removeWordsShorterThan($string, $min_word_lenght = 3)
    {
        $string = preg_replace('/\b\w{1,' . $min_word_lenght . '}\b/u', '', $string);
        $string = trim($string);

        return preg_replace('/\s+/', ' ', $string);
    }

    public static function truncate($text, $length, $pad = '...')
    {
        $length = abs((int) $length);
        if (mb_strlen($text) > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1' . $pad, $text);
        }

        return $text;
    }
}
