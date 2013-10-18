<?php
namespace php_rutils;

/**
 * Simple transliteration
 * Class Translit
 * @package php_rutils
 */
class Translit
{
    private static $_TRANSLATION_TABLE = array(
        array("'", "'"),
        array('"', '"'),
        array("‘", "'"),
        array("’", "'"),
        array("«", '"'),
        array("»", '"'),
        array("“", '"'),
        array("”", '"'),
        array("–", "-"), // en dash
        array("—", "-"), // em dash
        array("‒", "-"), // figure dash
        array("−", "-"), // minus
        array("…", "..."),
        array("№", "#"),
        // upper
        // three-symbols replacements
        array("Щ", "Sch"),
        // on russian->english translation only first replacement will be done
        // i.e. Sch
        // but on english->russian translation both variants (Sch and SCH) will play
        array("Щ", "SCH"),
        // two-symbol replacements
        array("Ё", "Yo"),
        array("Ё", "YO"),
        array("Ж", "Zh"),
        array("Ж", "ZH"),
        array("Ц", "Ts"),
        array("Ц", "TS"),
        array("Ч", "Ch"),
        array("Ч", "CH"),
        array("Ш", "Sh"),
        array("Ш", "SH"),
        array("Ы", "Yi"),
        array("Ы", "YI"),
        array("Ю", "Y"),
        array("Ю", "Y"),
        array("Я", "Ya"),
        array("Я", "YA"),
        // one-symbol replacements
        array("А", "A"),
        array("Б", "B"),
        array("В", "V"),
        array("Г", "G"),
        array("Д", "D"),
        array("Е", "E"),
        array("З", "Z"),
        array("И", "I"),
        array("Й", "J"),
        array("К", "K"),
        array("Л", "L"),
        array("М", "M"),
        array("Н", "N"),
        array("О", "O"),
        array("П", "P"),
        array("Р", "R"),
        array("С", "S"),
        array("Т", "T"),
        array("У", "U"),
        array("Ф", "F"),
        array("Х", "H"),
        array("Э", "E"),
        array("Ъ", "`"),
        array("Ь", "'"),
        //# lower
        // three-symbols replacements
        array("щ", "sch"),
        // two-symbols replacements
        array("ё", "yo"),
        array("ж", "zh"),
        array("ц", "ts"),
        array("ч", "ch"),
        array("ш", "sh"),
        array("ы", "yi"),
        array("ю", "y"),
        array("я", "ya"),
        // one-symbol replacements
        array("а", "a"),
        array("б", "b"),
        array("в", "v"),
        array("г", "g"),
        array("д", "d"),
        array("е", "e"),
        array("з", "z"),
        array("и", "i"),
        array("й", "j"),
        array("к", "k"),
        array("л", "l"),
        array("м", "m"),
        array("н", "n"),
        array("о", "o"),
        array("п", "p"),
        array("р", "r"),
        array("с", "s"),
        array("т", "t"),
        array("у", "u"),
        array("ф", "f"),
        array("х", "h"),
        array("э", "e"),
        array("ъ", "`"),
        array("ь", "'"),
        // Make english alphabet full: append english-english pairs
        // for symbols which is not used in russian-english
        // translations. Used in slugify.
        array("c", "c"),
        array("q", "q"),
        array("y", "y"),
        array("x", "x"),
        array("w", "w"),
        array("1", "1"),
        array("2", "2"),
        array("3", "3"),
        array("4", "4"),
        array("5", "5"),
        array("6", "6"),
        array("7", "7"),
        array("8", "8"),
        array("9", "9"),
        array("0", "0"),
    );  //Translation table

    private static $_RU_ALPHABET;
    private static $_EN_ALPHABET;
    private static $_ALPHABET;

    /**
     * "Constructor" for class variables
     */
    public static function StaticConstructor()
    {
        self::$_RU_ALPHABET = array();
        self::$_EN_ALPHABET = array();

        foreach (self::$_TRANSLATION_TABLE as $pair) {
            self::$_RU_ALPHABET[] = $pair[0];
            self::$_EN_ALPHABET[] = $pair[1];
        }

        self::$_ALPHABET = self::$_EN_ALPHABET + self::$_EN_ALPHABET;
    }

    /**
     * Translify russian text
     * @param string $inString Input string
     * @return string Transliterated string
     */
    public function translify($inString)
    {
        return str_replace(self::$_RU_ALPHABET, self::$_EN_ALPHABET, $inString);
    }
}

Translit::StaticConstructor();
