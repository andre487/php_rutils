<?php
namespace php_rutils\test;

use php_rutils\RUtils;

class TypoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \php_rutils\Typo
     */
    private $_object;

    protected function setUp()
    {
        parent::setUp();
        $this->_object = RUtils::typo();
    }

    public function testRlCleanSpaces()
    {
        $testData = array(
            " Точка ,точка , запятая, вышла рожица  кривая . "
                => "Точка, точка, запятая, вышла рожица кривая.",
            " Точка ,точка , \nзапятая,\n вышла  рожица \n кривая . "
                => "Точка, точка,\nзапятая,\nвышла рожица\nкривая.",
            "Газета ( ее принес мальчишка утром ) всё еще лежала на столе."
                => "Газета (ее принес мальчишка утром) всё еще лежала на столе.",
            "Газета, утром принесенная мальчишкой (\r это был сосед, подзарабатывающий летом\n )\r\n , всё еще лежала на столе."
                => "Газета, утром принесенная мальчишкой (это был сосед, подзарабатывающий летом), всё еще лежала на столе."
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlCleanSpaces($testValue));
    }
}
