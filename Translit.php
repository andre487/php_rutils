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
        // three-symbols replacements
        array('э', 'je'), array('ё', 'jo'), array('я', 'ya'), array('ю', 'yu'), array('ы', 'y'), array('ж', 'zh'),
        array('й', 'y'), array('щ', 'shch'), array('ч', 'ch'), array('ш', 'sh'), array('э', 'ea'), array('а', 'a'),
        array('б', 'b'), array('в', 'v'), array('г', 'g'), array('д', 'd'), array('е', 'e'), array('з', 'z'),
        array('и', 'i'), array('к', 'k'), array('л', 'l'), array('м', 'm'), array('н', 'n'), array('о', 'o'),
        array('п', 'p'), array('р', 'r'), array('с', 's'), array('т', 't'), array('у', 'u'), array('ф', 'f'),
        array('х', 'h'), array('ц', 'c'), array('э', 'e'), array('ь', ''), array('ъ', ''), array('й', 'y'),

        array('Э', 'JE'), array('Ё', 'JO'), array('Я', 'YA'), array('Ю', 'YU'), array('Ы', 'Y'), array('Ж', 'ZH'),
        array('Й', 'Y'), array('Щ', 'SHCH'), array('Ч', 'CH'), array('Ш', 'SH'), array('Э', 'E'), array('А', 'A'),
        array('Б', 'B'), array('В', 'V'), array('Г', 'G'), array('Д', 'D'), array('Е', 'E'), array('З', 'Z'),
        array('И', 'I'), array('К', 'K'), array('Л', 'L'), array('М', 'M'), array('Н', 'N'), array('О', 'O'),
        array('П', 'P'), array('Р', 'R'), array('С', 'S'), array('Т', 'T'), array('У', 'U'), array('Ф', 'F'),
        array('Х', 'H'), array('Ц', 'C'), array('Э', 'E'), array('Ь', ''), array('Ъ', ''), array('Й', 'Y'),
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
        return str_replace(self::$_EN_ALPHABET, self::$_RU_ALPHABET, $inString);
    }

    /**
     * Prepare string for slug (i.e. URL or file/dir name)
     * @param string $inString Input string
     * @param string|null $encoding
     * @return string Slug-string
     */
    public function slugify($inString, $encoding=null)
    {
        if ($encoding === null)
            $encoding = RUtils::$encoding;
        $inString = mb_strtolower($inString, $encoding);

        //convert & to "and"
        $inString = preg_replace('/(?:&amp;)|&/u' , ' and ', $inString);
        // replace spaces by hyphen
        $inString = preg_replace('/[-\s\t]+/u', '-', $inString);

        $translitString = $this->translify($inString);
        return preg_replace('/[^\w-]+/', '', $translitString);
    }
}

Translit::StaticConstructor();
