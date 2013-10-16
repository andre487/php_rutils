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
            1104 => '1 104 гвоздя',
            1111 => '1 111 гвоздей',
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
}
