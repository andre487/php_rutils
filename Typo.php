<?php
namespace php_rutils;

/**
 * Russian typography
 * Class Typo
 * @package php_rutils
 */
class Typo
{
    // arguments for preg_replace: pattern and replacement
    private static $_CLEAN_SPACES_TABLE = array(
        //remove spaces before punctuation marks
        array('#\s+([\.,?!\)]+)#u', '$1'),
        //add spaces after punctuation marks
        array('#([\.,?!\)]+)([^\.!,?\)]+)#u', '$1 $2'),
        //remove spaces after opening bracket
        array('#(\S+)\s*(\()\s+(\S+)#u', '$1 ($3'),
        //remove heading spaces
        array('#^\s+#um', ''),
        //remove trailing spaces
        array('#\s+$#um', ''),
        //remove double spaces
        array('#[ ]+#um', ' '),
    );

    private static $_CLEAN_SPACES_PATTERN, $_CLEAN_SPACES_REPLACEMENT;

    public static function StaticConstructor()
    {
        self::$_CLEAN_SPACES_PATTERN = array();
        self::$_CLEAN_SPACES_REPLACEMENT = array();

        foreach (self::$_CLEAN_SPACES_TABLE as $pair) {
            self::$_CLEAN_SPACES_PATTERN[] = $pair[0];
            self::$_CLEAN_SPACES_REPLACEMENT[] = $pair[1];
        }
    }

    /**
     *  Clean double spaces, trailing spaces, heading spaces,
     *  spaces before punctuations
     * @param string $text
     * @return string
     */
    public function rlCleanSpaces($text)
    {
        return preg_replace(self::$_CLEAN_SPACES_PATTERN, self::$_CLEAN_SPACES_REPLACEMENT, $text);
    }
}

Typo::StaticConstructor();
