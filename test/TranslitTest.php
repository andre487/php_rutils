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
            'правда ли это' => 'pravda li e`to',
            'Щука' => 'Shhuka',
            '«Вот так вот»' => '"Vot tak vot"',
            '‘Или вот так’' => "'Ili vot tak'",
            'Двигатель “Pratt&Whitney”' => 'Dvigatel` "Pratt&Whitney"'
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
            'pravda li e`to' => 'правда ли это',
            'Shhuka' => 'Щука',
            '"Vot tak vot"' => '«Вот так вот»',
            "'Ili vot tak'" => '‘Или вот так’',
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
            'и еще один тест' => 'i-eshhe-odin-test',
            'Проверка связи…' => 'proverka-svyazi',
            "Проверка\x0aсвязи 2" => 'proverka-svyazi-2',
            'World of Warcraft' => 'world-of-warcraft',
            'Юнит-тесты - наше всё' => 'yunit-testy-nashe-vsyo',
            'Юнит-тесты — наше всё' => 'yunit-testy-nashe-vsyo',
            '95−34' => '95-34',
            'Эмгыр вар Эмрейс тоже хочет быть в slugify' => 'emgyr-var-emrejs-tozhe-xochet-byt-v-slugify',
        );
        foreach ($testData as $testValue => $expected)
            $this->assertEquals($expected, $this->_object->slugify($testValue));
    }

    /**
     * @covers \php_rutils\Translit::translify
     * @covers \php_rutils\Translit::detranslify
     */
    public function testUniqueness()
    {
        $originalText = file_get_contents(__DIR__.'/data/zarathustra.txt');

        $translitText = $this->_object->translify($originalText);
        $cyrText = $this->_object->detranslify($translitText);

        $this->assertEquals($originalText, $cyrText);
    }
}
