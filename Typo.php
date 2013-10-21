<?php
namespace php_rutils;

/**
 * Russian typography
 * Class Typo
 * @package php_rutils
 */
class Typo
{
    //CLEAN SPACES RULE
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

    //ELLIPSIS RULE
    private static $_ELLIPSIS_PATTERN = array('#([^\.]|^)\.\.\.([^\.]|$)#u', '#(^|"|“|«)\s*…\s*([а-яa-z])#ui');
    private static $_ELLIPSIS_REPLACEMENT = '$1…$2';

    /**
     * "Constructor" for class variables
     */
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

    /**
     * Replace three dots to ellipsis
     * @param string $text
     * @return string
     */
    public function rlEllipsis($text)
    {
        return preg_replace(self::$_ELLIPSIS_PATTERN, self::$_ELLIPSIS_REPLACEMENT, $text);
    }

    /**
     * Replace space between initials and surname by thin space
     * @param string $text
     * @return string
     */
    public function rlInitials($text)
    {
        return preg_replace('#([А-Я])\.\s*([А-Я])\.\s*([А-Я][а-я]+)#u', "$1.\xC2\xA0$2.\xC2\xA0$3", $text);
    }
}

Typo::StaticConstructor();
