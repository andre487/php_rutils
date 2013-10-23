<?php
namespace php_rutils\test;

use php_rutils\RUtils;

class TranslitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \php_rutils\Translit
     */
    private $_object;

    protected function setUp()
    {
        parent::setUp();
        $this->_object = RUtils::translit();
    }

    /**
     * @covers \php_rutils\Translit::translify
     */
    public function testTranslify()
    {
        $testData = array(
            'тест №1' => 'test #1',
            'проверка' => 'proverka',
            'транслит' => 'translit',
            'правда ли это' => 'pravda li jeto',
            'Щука' => 'SHCHuka',
            '«Вот так вот»' => '"Vot tak vot"',
            '‘Или вот так’' => "'Ili vot tak'",
            '– Да…' => '- Da...',
            'Двигатель “Pratt&Whitney”' => 'Dvigatel "Pratt&Whitney"'
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->translify($testValue));
    }

    /**
     * @covers \php_rutils\Translit::detranslify
     */
    public function testDetranslify()
    {
        $testData = array(
            'test #1' => 'тест №1',
            'proverka' => 'проверка',
            'translit' => 'транслит',
            'SHCHuka' => 'Щука',
            '- Da...' => '– Да…',
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->detranslify($testValue));
    }

    /**
     * @covers \php_rutils\Translit::slugify
     */
    public function testSlugify()
    {
        $testData = array(
            'тест' => 'test',
            'Проверка связи' => 'proverka-svyazi',
            'me&you' => 'me-and-you',
            'и еще один тест' => 'i-eshche-odin-test',
            'Проверка связи…' => 'proverka-svyazi',
            "Проверка\x0aсвязи 2" => 'proverka-svyazi-2',
            "Проверка\201связи 3" => 'proverkasvyazi-3',
            'World of Warcraft' => 'world-of-warcraft',
            'Юнит-тесты — наше всё' => 'yunit-testy---nashe-vsjo',
            'Юнит-тесты ‒ наше всё' => 'yunit-testy---nashe-vsjo',
            '95−34' => '95-34',
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->slugify($testValue));
    }
}
