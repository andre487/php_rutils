<?php
namespace php_rutils;

class RUtils
{
    //gender constants
    const MALE = 1;
    const FEMALE = 2;
    const NEUTER = 3;

    //accuracy for Dt::distanceOfTimeInWords function
    const ACCURACY_YEAR = 1;
    const ACCURACY_MONTH = 2;
    const ACCURACY_DAY = 3;
    const ACCURACY_HOUR = 4;
    const ACCURACY_MINUTE = 5;

    private static $_numeral;
    private static $_dt;
    private static $_translit;
    private static $_typo;

    /**
     * Plural forms and in-word representation for numerals
     * @return \php_rutils\Numeral
     */
    public static function numeral()
    {
        if (self::$_numeral === null)
            self::$_numeral = new Numeral();
        return self::$_numeral;
    }

    /**
     * Russian dates without locales
     * @return \php_rutils\Dt
     */
    public static function dt()
    {
        if (self::$_dt === null)
            self::$_dt = new Dt();
        return self::$_dt;
    }

    /**
     * Simple transliteration
     * @return \php_rutils\Translit
     */
    public static function translit()
    {
        if (self::$_translit === null)
            self::$_translit = new Translit();
        return self::$_translit;
    }

    /**
     * Russian typography
     * @return \php_rutils\Typo
     */
    public static function typo()
    {
        if (self::$_typo === null)
            self::$_typo = new Typo();
        return self::$_typo;
    }

    /**
     * Format number with russian locale
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function formatNumber($number, $decimals=0)
    {
        $number = number_format($number, $decimals, ',', ' ');
        return str_replace(' ', "\xE2\x80\x89", $number);
    }
}
