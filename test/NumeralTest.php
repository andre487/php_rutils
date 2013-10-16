<?php
namespace php_rutils\test;

use php_rutils\RUtils;

class NumeralTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $Class = RUtils::numeral();
        $this->assertInstanceOf('\php_rutils\Numeral', new $Class());
    }
}
