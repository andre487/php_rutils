<?php
namespace php_rutils;

class RUtils
{
    /**
     * Plural forms and in-word representation for numerals
     * @return \php_rutils\Numeral
     */
    public static function numeral()
    {
        return new Numeral();
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
