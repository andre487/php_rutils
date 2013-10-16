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
}
