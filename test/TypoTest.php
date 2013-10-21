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

    /**
     * @covers \php_rutils\Typo::rlCleanSpaces
     */
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

    /**
     * @covers \php_rutils\Typo::rlEllipsis
     */
    public function testRlCleanEllipsis()
    {
        $testData = array(
            'Мдя..... могло быть лучше' => 'Мдя..... могло быть лучше',
            '...Дааааа' => '…Дааааа',
            '... Дааааа' => '…Дааааа'
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlEllipsis($testValue));
    }

    /**
     * @covers \php_rutils\Typo::rlInitials
     */
    public function testRlInitials()
    {
        $testData = array(
            'Председатель В.И.Иванов выступил на собрании'
                => "Председатель В.\xC2\xA0И.\xC2\xA0Иванов выступил на собрании",
            'Председатель В.И. Иванов выступил на собрании'
                => "Председатель В.\xC2\xA0И.\xC2\xA0Иванов выступил на собрании",
            "1. В.И.Иванов\r\n2. С.П.Васечкин"
                => "1. В.\xC2\xA0И.\xC2\xA0Иванов\r\n2. С.\xC2\xA0П.\xC2\xA0Васечкин",
            "Председатель В.\r\nИ.\r\nИванов выступил на собрании"
                => "Председатель В.\xC2\xA0И.\xC2\xA0Иванов выступил на собрании",
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlInitials($testValue));
    }

    /**
     * @covers \php_rutils\Typo::rlDashes
     */
    public function testRlDashes()
    {
        $testData = array(
            "- Я пошел домой...\n- Может останешься? - Нет, ухожу."
                => "—\xE2\x80\xAfЯ пошел домой...\n—\xE2\x80\xAfМожет останешься? —\xE2\x80\xAfНет, ухожу.",
            '-- Я пошел домой... -- Может останешься? -- Нет, ухожу.'
                => "—\xE2\x80\xAfЯ пошел домой... —\xE2\x80\xAfМожет останешься? —\xE2\x80\xAfНет, ухожу.",
            "-- Я\xC2\xA0пошел домой…\xC2\xA0-- Может останешься?\xC2\xA0-- Нет,\xC2\xA0ухожу."
                => "—\xE2\x80\xAfЯ\xC2\xA0пошел домой…\xC2\xA0—\xE2\x80\xAfМожет останешься?\xC2\xA0—\xE2\x80\xAfНет,\xC2\xA0ухожу.",
            'Муха - это маленькая птичка' => "Муха\xE2\x80\xAf— это маленькая птичка",
            'Муха--это маленькая птичка' => "Муха\xE2\x80\xAf— это маленькая птичка",
            'Ползать по-пластунски' => 'Ползать по-пластунски',
            'Диапазон: 9 -  15' => 'Диапазон: 9—15',
            'Температура: -1 - +2' => 'Температура: -1…+2'
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlDashes($testValue));
    }

    /**
     * @covers \php_rutils\Typo::rlWordGlue
     */
    public function testRlWordGlue()
    {
        $testData = array(
            'Вроде бы оператор согласен' => "Вроде\xE2\x80\xAFбы\xC2\xA0оператор согласен",
            'Он не поверил глазам' => "Он\xC2\xA0не\xC2\xA0поверил глазам",
            'Муха — это маленькая птичка' => "Муха\xE2\x80\xAf— это\xC2\xA0маленькая птичка",
            'увидел в газете (это была "Сермяжная правда" № 45) рубрику Weather Forecast'
                => "увидел в\xC2\xA0газете (это\xC2\xA0была \"Сермяжная правда\" № 45) рубрику Weather Forecast"
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlWordGlue($testValue));
    }
}
