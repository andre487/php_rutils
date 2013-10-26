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
        //Non-alphabet symbols
        array("‘", "'"),
        array("’", "'"),
        array("«", '"'),
        array("»", '"'),
        array("“", '"'),
        array("”", '"'),
        array("№", "#"),

        //Alphabet (ISO9 [ГОСТ 7.79—2000], Scheme B)
        //3-symbolic
        array('Щ', 'Shh'), array('щ', 'shh'),

        //2-symbolic
        array('Ё', 'Yo'), array('ё', 'yo'),
        array('Ж', 'Zh'), array('ж', 'zh'),
        array('Ц', 'Cz'), array('ц', 'cz'),
        array('Ч', 'Ch'), array('ч', 'ch'),
        array('Ш', 'Sh'), array('ш', 'sh'),
        array('ъ', '``'), array('Ъ', '``'),
        array('Ы', 'Y`'), array('ы', 'y`'),
        array('Э', 'E`'), array('э', 'e`'),
        array('Ю', 'Yu'), array('ю', 'yu'),
        array('Я', 'Ya'), array('я', 'ya'),

        //1-symbolic
        array('А', 'A'), array('а', 'a'),
        array('Б', 'B'), array('б', 'b'),
        array('В', 'V'), array('в', 'v'),
        array('Г', 'G'), array('г', 'g'),
        array('Д', 'D'), array('д', 'd'),
        array('Е', 'E'), array('е', 'e'),
        array('З', 'Z'), array('з', 'z'),
        array('И', 'I'), array('и', 'i'),
        array('Й', 'J'), array('й', 'j'),
        array('К', 'K'), array('к', 'k'),
        array('Л', 'L'), array('л', 'l'),
        array('М', 'M'), array('м', 'm'),
        array('Н', 'N'), array('н', 'n'),
        array('О', 'O'), array('о', 'o'),
        array('П', 'P'), array('п', 'p'),
        array('Р', 'R'), array('р', 'r'),
        array('С', 'S'), array('с', 's'),
        array('Т', 'T'), array('т', 't'),
        array('У', 'U'), array('у', 'u'),
        array('Ф', 'F'), array('ф', 'f'),
        array('Х', 'X'), array('х', 'x'),
        array('ь', '`'), array('Ь', '`'),
    );  //Translation table

    private static $_RU_ALPHABET;
    private static $_EN_ALPHABET;

    private static $_CORRECTION_PATTERN = array('#(\w)«#u', '#(\w)“#u', '#(\w)‘#u');
    private static $_CORRECTION_REPLACEMENT = array('$1»', '$1”', '$1’');

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

    /**
     * Detranslify
     * @param string $inString Input string
     * @return string Detransliterated string
     */
    public function detranslify($inString)
    {
        $dirtyResult = str_replace(self::$_EN_ALPHABET, self::$_RU_ALPHABET, $inString);
        return preg_replace(self::$_CORRECTION_PATTERN, self::$_CORRECTION_REPLACEMENT, $dirtyResult);
    }

    /**
     * Prepare string for slug (i.e. URL or file/dir name)
     * @param string $inString Input string
     * @return string Slug-string
     */
    public function slugify($inString)
    {
        //convert & to "and"
        $inString = preg_replace('/(?:&amp;)|&/u' , ' and ', $inString);

        //replace spaces
        $inString = preg_replace('/[—−-\s\t]+/u', '-', $inString);

        $translitString = strtolower($this->translify($inString));
        return preg_replace('/[^a-z0-9_-]+/i', '', $translitString);
    }
}

Translit::StaticConstructor();
