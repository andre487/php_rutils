<?php
namespace php_rutils;

class RUtils
{
    const MALE = 1; //sex - male
    const FEMALE = 2; //sex - female
    const NEUTER = 3; //sex - neuter

    /**
     * Plural forms and in-word representation for numerals
     * @return \php_rutils\Numeral
     */
    public static function numeral()
    {
        return new Numeral();
    }

    /**
     * Russian dates without locales
     * @return \php_rutils\Dt
     */
    public static function dt()
    {
        return new Dt();
    }

    /**
     * Simple transliteration
     * @return \php_rutils\Translit
     */
    public static function translit()
    {
        return new Translit();
    }

    /**
     * Russian typography
     * @return \php_rutils\Typo
     */
    public function typo()
    {
        return new Typo();
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
