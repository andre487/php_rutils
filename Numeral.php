<?php
namespace php_rutils;

class Numeral
{
    const MALE = 1; //sex - male
    const FEMALE = 2; //sex - female
    const NEUTER = 3; //sex - neuter

    /**
     * Choose proper case depending on amount
     * @param int $amount Amount of objects
     * @param string[] $variants Variants (forms) of object in such form: array('1 object', '2 objects', '5 objects')
     * @return string Proper variant
     * @throws \InvalidArgumentException Variants' length lesser than 3
     */
    public function choosePlural($amount, array $variants)
    {
        if (sizeof($variants) < 3)
            throw new \InvalidArgumentException('Incorrect values length (must be 3)');

        $amount = abs($amount);
        $mod10 = $amount%10;
        $mod100 = $amount%100;

        if ($mod10 == 1 && $mod100 != 11)
            $variant = 0;
        elseif ($mod10 >= 2 && $mod10 <= 4 && !($mod100 > 10 && $mod100 < 20))
            $variant = 1;
        else
            $variant = 2;

        return $variants[$variant];
    }

    /**
     * Get proper case with value
     * @param int $amount Amount of objects
     * @param array $variants Variants (forms) of object in such form: array('1 object', '2 objects', '5 objects')
     * @param string|null $absence If amount is zero will return it
     * @return string|null
     */
    public function getPlural($amount, array $variants, $absence=null)
    {
        if ($amount || $absence === null)
            $result = RUtils::formatNumber($amount).' '.$this->choosePlural($amount, $variants);
        else
            $result = $absence;
        return $result;
    }
}
