<?php
namespace php_rutils\test;

use php_rutils\RUtils;
use php_rutils\TypoRules;

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
                => "Председатель В.\xE2\x80\x89И.\xE2\x80\x89Иванов выступил на собрании",
            'Председатель В.И. Иванов выступил на собрании'
                => "Председатель В.\xE2\x80\x89И.\xE2\x80\x89Иванов выступил на собрании",
            "1. В.И.Иванов\r\n2. С.П.Васечкин"
                => "1. В.\xE2\x80\x89И.\xE2\x80\x89Иванов\r\n2. С.\xE2\x80\x89П.\xE2\x80\x89Васечкин",
            "Председатель В.\r\nИ.\r\nИванов выступил на собрании"
                => "Председатель В.\xE2\x80\x89И.\xE2\x80\x89Иванов выступил на собрании",
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
                => "—\xE2\x80\x89Я пошел домой...\n—\xE2\x80\x89Может останешься? —\xE2\x80\x89Нет, ухожу.",
            '-- Я пошел домой... -- Может останешься? -- Нет, ухожу.'
                => "—\xE2\x80\x89Я пошел домой... —\xE2\x80\x89Может останешься? —\xE2\x80\x89Нет, ухожу.",
            "-- Я\xC2\xA0пошел домой…\xC2\xA0-- Может останешься?\xC2\xA0-- Нет,\xC2\xA0ухожу."
                => "—\xE2\x80\x89Я\xC2\xA0пошел домой…\xC2\xA0—\xE2\x80\x89Может останешься?\xC2\xA0—\xE2\x80\x89Нет,\xC2\xA0ухожу.",
            'Муха - это маленькая птичка' => "Муха\xE2\x80\x89— это маленькая птичка",
            'Муха--это маленькая птичка' => "Муха\xE2\x80\x89— это маленькая птичка",
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
            'Вроде бы оператор согласен' => "Вроде\xC2\xA0бы\xC2\xA0оператор согласен",
            'Он не поверил глазам' => "Он\xC2\xA0не\xC2\xA0поверил глазам",
            'Муха — это маленькая птичка' => "Муха\xE2\x80\x89— это\xC2\xA0маленькая птичка",
            'увидел в газете (это была "Сермяжная правда" № 45) рубрику Weather Forecast'
                => "увидел в\xC2\xA0газете (это\xC2\xA0была \"Сермяжная правда\" № 45) рубрику Weather Forecast"
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlWordGlue($testValue));
    }

    /**
     * @covers \php_rutils\Typo::rlMarks
     */
    public function testRlMarks()
    {
        $testData = array(
            'Когда В. И. Пупкин увидел в газете рубрику Weather Forecast (r), он не поверил своим глазам - температуру обещали +-451F.'
                => "Когда В. И. Пупкин увидел в газете рубрику Weather Forecast®, он не поверил своим глазам - температуру обещали ±451\xE2\x80\x89°F.",
            '14 Foo' => '14 Foo',
            'Coca-cola(tm)' => 'Coca-cola™',
            "(c)  2008\xE2\x80\x89Юрий Юревич" => "©\xE2\x80\x892008\xE2\x80\x89Юрий Юревич",
            "Microsoft (R) Windows (tm)" => "Microsoft® Windows™",
            "Школа-гимназия No 3" => "Школа-гимназия\xC2\xA0№\xE2\x80\x893",
            'Школа-гимназия No3' => "Школа-гимназия\xC2\xA0№\xE2\x80\x893",
            'Школа-гимназия №3' => "Школа-гимназия\xC2\xA0№\xE2\x80\x893",
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlMarks($testValue));
    }

    /**
     * @covers \php_rutils\Typo::rlQuotes
     */
    public function testRlQuotes()
    {
        $testData = array(
            'ООО "МСК "Аско-Забота"' => 'ООО «МСК «Аско-Забота»',
            "\"МСК\xC2\xA0\"Аско-Забота\"" => "«МСК\xC2\xA0«Аско-Забота»",
            "Двигатели 'Pratt&Whitney'" => "Двигатели “Pratt&Whitney”",
            "\"Вложенные \"кавычки\" - бич всех типографик\", не правда ли"
                => "«Вложенные «кавычки» - бич всех типографик», не правда ли",
            "'Pratt&Whitney' никогда не использовались на самолетах \"Аэрофлота\""
                => "“Pratt&Whitney” никогда не использовались на самолетах «Аэрофлота»"
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->rlQuotes($testValue));
    }

    /**
     * @covers \php_rutils\Typo::typography
     */
    public function testTypographyStandard()
    {
        $text = <<<TEXT
...Когда В. И. Пупкин увидел в газете ( это была "Сермяжная правда" № 45) рубрику Weather Forecast (r),
он не поверил своим глазам - температуру обещали +-451F.
TEXT;
        $text = preg_replace('#\r?\n#', "\n", $text);

        $expected = <<<TEXT
...Когда В. И. Пупкин увидел в газете (это была «Сермяжная правда»\xC2\xA0№\xE2\x80\x8945) рубрику Weather Forecast®,
он не поверил своим глазам\xE2\x80\x89— температуру обещали ±451 °F.
TEXT;
        $expected = preg_replace('#\r?\n#', "\n", $expected);

        $this->assertEquals($expected, $this->_object->typography($text));
    }

    /**
     * @covers \php_rutils\Typo::typography
     */
    public function testTypographyExtended()
    {
        $text = <<<TEXT
...Когда В. И. Пупкин увидел в газете ( это была "Сермяжная правда" № 45) рубрику Weather Forecast (r),
он не поверил своим глазам - температуру обещали +-451F.
TEXT;
            $text = preg_replace('#\r?\n#', "\n", $text);

            $expected = <<<TEXT
…Когда В.\xE2\x80\x89И.\xE2\x80\x89Пупкин увидел в\xC2\xA0газете (это\xC2\xA0была «Сермяжная правда»\xC2\xA0№\xE2\x80\x8945) рубрику Weather Forecast®,
он\xC2\xA0не поверил своим глазам\xE2\x80\x89— температуру обещали ±451 °F.
TEXT;
            $expected = preg_replace('#\r?\n#', "\n", $expected);

            $this->assertEquals($expected, $this->_object->typography($text, TypoRules::$EXTENDED_RULES));
        }
}
