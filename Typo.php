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
        array('#\s+([\.,?!:;\)]+)#u', '$1'),
        //add spaces after punctuation marks
        array('#([^\.][\.,?!:;\)]+)([^\.!,?\)]+)#u', '$1 $2'),
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
    private static $_ELLIPSIS_PATTERN = array(
        '#([^\.]|^)\.\.\.([^\.]|$)#u',
        '#(^|"|“|«)\s*…\s*([[:alpha:]])#ui'
    );
    private static $_ELLIPSIS_REPLACEMENT = '$1…$2';

    //DASHES RULE
    private static $_DASHES_PATTERN = array(
        //dash in the beginning of the sentence
        '#(^|(?:[\.\?!…]\s*))--?\s*(.|$)#u',
        //dash between words
        '#([[:alpha:]])(?:\s+--?\s+)|(?:--)(.|$)#u',
        //dash in range of numbers
        '#(\d)\s*--?\s*(\d)#u',
        '#([+-]?\d)\s*--?\s*([+-]?\d)#u'
    );
    private static $_DASHES_REPLACEMENT = array(
        "$1—\xE2\x80\x89$2",
        "$1\xE2\x80\x89— $2",
        '$1—$2',
        '$1…$2',
    );

    //WORD GLUE RULE
    private static $_GLUE_PATTERN = array(
        //particles
        '#(\S)\s+(же|ли|ль|бы|б|ж|ка)([\s\.,!\?:;…]*)#u',
        //short words
        '#([[:^alpha:]][[:alpha:]]{1,3})\s+(\S)#u',
        '#^([[:alpha:]]{1,3})\s+(\S)#u',
        //dashes
        '#(\s+)([—-]+)(\s+)#u',
    );
    private static $_GLUE_REPLACEMENT = array(
        "$1\xC2\xA0$2$3",
        "$1\xC2\xA0$2",
        "$1\xC2\xA0$2",
        "\xE2\x80\x89$2$3",
    );

    //MARKS RULE
    private static $_MARKS_TABLE = array(
        array('#((?:-|\+)?\d+)\s*([fc]\W)#ui', "$1\xE2\x80\x89°$2"),
        array('#\(c\)#ui', '©'),
        array('#\(r\)#ui', '®'),
        array('#\(p\)#ui', '§'),
        array('#\(tm\)#ui', '™'),
        array('#(©)\s*(\d+)#u', "$1\xE2\x80\x89$2"),
        array('#([^+])((?:\+-)|(?:-\+))#u', '$1±'),
        array('#(\w)\s+(®|™)#u', '$1$2'),
        array('#\s(no|№)\s*(\d+)#ui', "\xC2\xA0№\xE2\x80\x89$2"),
    );
    private static $_MARKS_PATTERN, $_MARKS_REPLACEMENT;

    //QUOTES RULE
    private static $_QUOTES_PATTERN = array(
        '#(^|\s)(")(\w)#u',
        '#(\w)(")([\s,;:?!\.]|$)#u',
        '#(^|\s)(\')(\w)#u',
        '#(\w)(\')([\s,;:?!\.]|$)#u',
    );
    private static $_QUOTES_REPLACEMENT = array('$1«$3', '$1»$3', "$1“$3", "$1”$3");

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

        self::$_MARKS_PATTERN = array();
        self::$_MARKS_REPLACEMENT = array();

        foreach (self::$_MARKS_TABLE as $pair) {
            self::$_MARKS_PATTERN[] = $pair[0];
            self::$_MARKS_REPLACEMENT[] = $pair[1];
        }
    }

    /**
     * Clean double spaces, trailing spaces, heading spaces,
     * spaces before punctuations
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
        return preg_replace(
            '#([А-Я])\.\s*([А-Я])\.\s*([А-Я][а-я]+)#u',
            "$1.\xE2\x80\x89$2.\xE2\x80\x89$3",
            $text
        );
    }

    /**
     * Replace dash to long/medium dashes
     * @param string $text
     * @return string
     */
    public function rlDashes($text)
    {
        return preg_replace(self::$_DASHES_PATTERN, self::$_DASHES_REPLACEMENT, $text);
    }

    /**
     * Glue (set nonbreakable space) short words with word before/after
     * @param string $text
     * @return string
     */
    public function rlWordGlue($text)
    {
        return preg_replace(self::$_GLUE_PATTERN, self::$_GLUE_REPLACEMENT, $text);
    }

    /**
     * Replace +-, (c), (tm), (r), (p), etc by its typographic equivalents
     * @param string $text
     * @return string
     */
    public function rlMarks($text)
    {
        return preg_replace(self::$_MARKS_PATTERN, self::$_MARKS_REPLACEMENT, $text);
    }

    /**
     * Replace quotes by typographic quotes
     * @param string $text
     * @return string
     */
    public static function rlQuotes($text)
    {
        return preg_replace(self::$_QUOTES_PATTERN, self::$_QUOTES_REPLACEMENT, $text);
    }

    /**
     * Typography applier
     * @param string $text Text for handle
     * @param array $rules Rules array. Look TypoRules class. By default using TypoRules::$STANDARD_RULES
     * @return string
     * @throws \InvalidArgumentException
     */
    public function typography($text, array $rules=null)
    {
        if ($rules === null)
            $rules = TypoRules::$STANDARD_RULES;
        if (array_diff($rules, TypoRules::$EXTENDED_RULES))
            throw new \InvalidArgumentException('Invalid typo rules');

        foreach ($rules as $rule) {
            $funcName = 'rl'.$rule;
            $text = call_user_func(array($this, $funcName), $text);
        }
        return $text;
    }
}

Typo::StaticConstructor();
