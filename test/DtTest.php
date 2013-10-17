<?php
namespace php_rutils\test;

use php_rutils\RUtils;
use php_rutils\struct\TimeParams;

class DtTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \php_rutils\Dt
     */
    private $_object;

    /**
     * @var \DateTime
     */
    private $_date;

    /**
     * @var array
     */
    private $_defaultParams;

    protected function setUp()
    {
        parent::setUp();
        $this->_object = RUtils::dt();
        $this->_date = '1988-01-01 6:40:34';
        $this->_defaultParams = array(
            'date' => $this->_date,
            'timezone' => 'UTC',
        );
    }

    /**
     * @covers \php_rutils\Dt::ruStrFTime
     */
    public function testRuStrFTimeFixed()
    {
        $testData = array(
            'd.m.Y' => '01.01.1988',
            'тест D' => 'тест пт',
            'тест l' => 'тест пятница',
            'тест M' => 'тест янв',
            'тест F' => 'тест январь',
            'd M Y' => '1 янв 1988',
            'd F Y' => '1 январь 1988',
        );

        $params = $this->_defaultParams;
        foreach ($testData as $format => $expected) {
            $params['format'] = $format;
            $strTime = $this->_object->ruStrFTime(TimeParams::create($params));
            $this->assertEquals($expected, $strTime);
        }
    }

    /**
     * @covers \php_rutils\Dt::ruStrFTime
     */
    public function testRuStrFTimePreposition()
    {
        $testData = array(
            'тест D' => "тест в\xa0пт",
            'тест l' => "тест в\xa0пятницу",
        );

        $params = $this->_defaultParams;
        $params['preposition'] = true;
        foreach ($testData as $format => $expected) {
            $params['format'] = $format;
            $strTime = $this->_object->ruStrFTime($params);
            $this->assertEquals($expected, $strTime);
        }
    }

    /**
     * @covers \php_rutils\Dt::ruStrFTime
     */
    public function testRuStrFTimeInflected()
    {
        $testData = array(
            'тест M' => "тест янв",
            'тест F' => "тест января",
            'd M Y' => "1 янв 1988",
            'd F Y' => "1 января 1988",
            'тест выполнен d F Y года' => 'тест выполнен 1 января 1988 года',
            'тестируем D' => "тестируем пт",
            'тестируем l' => "тестируем пятницу",
        );

        $params = $this->_defaultParams;
        $params['preposition'] = false;
        $params['dayInflected'] = true;
        $params['monthInflected'] = true;
        foreach ($testData as $format => $expected) {
            $params['format'] = $format;
            $strTime = $this->_object->ruStrFTime($params);
            $this->assertEquals($expected, $strTime);
        }
    }
}
