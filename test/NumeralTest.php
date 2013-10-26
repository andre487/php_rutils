<?php
namespace php_rutils\test;

use php_rutils\RUtils;

class NumeralTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \php_rutils\Numeral
     */
    private $_object;
    private $_variants = array('гвоздь', 'гвоздя', 'гвоздей');

    protected function setUp()
    {
        parent::setUp();
        $this->_object = RUtils::numeral();
    }

    /**
     * @covers \php_rutils\RUTils::numeral
     */
    public function testInstance()
    {
        $this->assertInstanceOf('\php_rutils\Numeral', $this->_object);
    }

    /**
     * @covers \php_rutils\Numeral::choosePlural
     */
    public function testChoosePlural()
    {
        $testData = array(
            -1 => 'гвоздь',
            1 => 'гвоздь',
            2 => 'гвоздя',
            -2 => 'гвоздя',
            3 => 'гвоздя',
            -3 => 'гвоздя',
            4 => 'гвоздя',
            5 => 'гвоздей',
            11 => 'гвоздей',
            21 => 'гвоздь',
            12 => 'гвоздей',
            101 => 'гвоздь',
            104 => 'гвоздя',
            111 => 'гвоздей',
        );
        foreach ($testData as $amount => $expected)
            $this->_assertChoosePlural($amount, $expected);
    }

    private function _assertChoosePlural($amount, $expected)
    {
        $this->assertEquals($expected, $this->_object->choosePlural($amount, $this->_variants));
    }

    /**
     * @covers \php_rutils\Numeral::getPlural
     */
    public function testGetPlural()
    {
        $testData = array(
            -1 => '-1 гвоздь',
            2 => '2 гвоздя',
            11 => '11 гвоздей',
            1104 => "1\xE2\x80\x89104 гвоздя",
            1111 => "1\xE2\x80\x89111 гвоздей",
        );
        foreach ($testData as $amount => $expected)
            $this->_assertGetPlural($amount, $expected);
    }

    private function _assertGetPlural($amount, $expected)
    {
        $this->assertEquals($expected, $this->_object->getPlural($amount, $this->_variants));
    }

    /**
     * @covers \php_rutils\Numeral::getPlural
     */
    public function testGetPluralWithAbsence()
    {
        $absence = 'нет гвоздей';
        $this->assertEquals($absence, $this->_object->getPlural(0, $this->_variants, $absence));
    }

    /**
     * @covers \php_rutils\Numeral::sumString
     */
    public function testSumStringMaleSuccess()
    {
        $variants = array('гвоздь', 'гвоздя', 'гвоздей');

        $testData = array(
            0 => 'ноль гвоздей',
            1 => 'один гвоздь',
            2 => 'два гвоздя',
            10 => 'десять гвоздей',
            12 => 'двенадцать гвоздей',
            31 => 'тридцать один гвоздь',
            104 => 'сто четыре гвоздя',
            1000000 => 'один миллион гвоздей',
            1102003 => 'один миллион сто две тысячи три гвоздя',
            1100000001 => 'один миллиард сто миллионов один гвоздь',
        );
        foreach ($testData as $amount => $expected)
            $this->assertEquals($expected, $this->_object->sumString($amount, RUtils::MALE, $variants));
    }

    /**
     * @covers \php_rutils\Numeral::sumString
     */
    public function testSumStringFemaleSuccess()
    {
        $variants = array('шляпка', 'шляпки', 'шляпок');

        $testData = array(
            0 => 'ноль шляпок',
            1 => 'одна шляпка',
            2 => 'две шляпки',
            10 => 'десять шляпок',
            12 => 'двенадцать шляпок',
            31 => 'тридцать одна шляпка',
            104 => 'сто четыре шляпки',
            1000000 => 'один миллион шляпок',
            1102003 => 'один миллион сто две тысячи три шляпки',
            1100000001 => 'один миллиард сто миллионов одна шляпка',
        );
        foreach ($testData as $amount => $expected)
            $this->assertEquals($expected, $this->_object->sumString($amount, RUtils::FEMALE, $variants));
    }

    /**
     * @covers \php_rutils\Numeral::sumString
     */
    public function testSumStringRangeException()
    {
        $variants = array('гвоздь', 'гвоздя', 'гвоздей');
        try {
            var_dump($this->_object->sumString(PHP_INT_MAX+1, RUtils::MALE, $variants));
            $this->fail('No RangeException');
        }
        catch (\RangeException $e) {
        }
    }

    /**
     * @covers \php_rutils\Numeral::getInWordsInt
     */
    public function testInWordsInt()
    {
        $this->assertEquals('сто два', $this->_object->getInWordsInt(102));
        $this->assertEquals('сто две тысячи', $this->_object->getInWordsInt(102000));
        $this->assertEquals('сто две тысячи одна', $this->_object->getInWordsInt(102001, RUtils::FEMALE));
    }

    /**
     * @covers \php_rutils\Numeral::getInWordsFloat
     */
    public function testInWordsFloat()
    {
        $testData = array(
            '0.2' => 'ноль целых две десятых',
            '10.0' => 'десять целых ноль десятых',
            '2.25' => 'две целых двадцать пять сотых',
            '0.01' => 'ноль целых одна сотая',
            '0.1' => 'ноль целых одна десятая',
            '0.000000001' => 'ноль целых одна миллиардная',
        );
        foreach ($testData as $amount => $expected)
            $this->assertEquals($expected, $this->_object->getInWordsFloat($amount));

    }

    /**
     * @covers \php_rutils\Numeral::getInWords
     */
    public function testInWordsGeneral()
    {
        $testData = array(
            102 => 'сто две',
            102000 => 'сто две тысячи',
            '0.2' => 'ноль целых две десятых',
            '10.0' => 'десять',
            '2.25' => 'две целых двадцать пять сотых',
            '0.01' => 'ноль целых одна сотая',
            '0.1' => 'ноль целых одна десятая',
            '0.000000001' => 'ноль целых одна миллиардная',
        );
        foreach ($testData as $amount => $expected)
            $this->assertEquals($expected, $this->_object->getInWords($amount, RUtils::FEMALE));
    }

    /**
     * @covers \php_rutils\Numeral::getRubles
     */
    public function testGetRubles()
    {
        $testData = array(
            102 => 'сто два рубля',
            1000 => 'одна тысяча рублей',
            '0.2' => 'двадцать копеек',
            '2.25' => 'два рубля двадцать пять копеек',
            '0.01' => 'одна копейка',
        );
        foreach ($testData as $amount => $expected)
            $this->assertEquals($expected, $this->_object->getRubles($amount));
    }
}
