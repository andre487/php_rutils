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
            'правда ли это' => 'pravda li eto',
            'Щука' => 'Schuka',
            '«Вот так вот»' => '"Vot tak vot"',
            '‘Или вот так’' => "'Ili vot tak'",
            '– Да…' => '- Da...',
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
            'Schuka' => 'Щука',
            'SCHuka' => 'Щука',
            '- Da...' => '– Да…',
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->detranslify($testValue));
    }
}
