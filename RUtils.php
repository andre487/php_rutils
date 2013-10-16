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
     * Format number with locale conventions
     * @param float $number
     * @return string
     */
    public static function formatNumber($number)
    {
        $params = localeconv();
        return number_format($number, $params['decimal_point'], $params['thousands_sep']);
    }
}
