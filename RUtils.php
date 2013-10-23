<?php
namespace php_rutils;

class RUtils
{
    const MALE = 1; //sex - male
    const FEMALE = 2; //sex - female
    const NEUTER = 3; //sex - neuter

    /**
     * Default encoding for multibyte strings
     * @var string
     */
    public static $encoding = 'UTF-8';

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
        return number_format($number, $decimals, ',', ' ');
    }
}
