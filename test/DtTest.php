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
            'тест D' => "тест в\xC2\xA0пт",
            'тест l' => "тест в\xC2\xA0пятницу",
        );

        $params = $this->_defaultParams;
        $params['preposition'] = true;
        $params['date'] = strtotime($this->_defaultParams['date']);
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

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testDistanceOfTimeToCurrent()
    {
        $nowTime = strtotime('now');

        $testData = array(
            //past
            date('Y-m-d H:i:s', $nowTime - 1) => 'менее минуты назад',
            date('Y-m-d H:i:s', $nowTime - 60) => 'минуту назад',
            date('Y-m-d H:i:s', $nowTime - 2*60) => '2 минуты назад',
            date('Y-m-d H:i:s', $nowTime - 5*60) => '5 минут назад',
            date('Y-m-d H:i:s', $nowTime - 60*60) => 'час назад',
            date('Y-m-d H:i:s', $nowTime - 2*60*60) => '2 часа назад',
            date('Y-m-d H:i:s', $nowTime - 5*60*60) => '5 часов назад',
            date('Y-m-d H:i:s', $nowTime - 24*60*60) => 'вчера',
            date('Y-m-d H:i:s', $nowTime - 2*24*60*60) => 'позавчера',
            date('Y-m-d H:i:s', $nowTime - 3*24*60*60) => '3 дня назад',
            date('Y-m-d H:i:s', $nowTime - 8*24*60*60 - 1*60*60) => '8 дней назад',
            ($nowTime - 365*24*60*60) => '365 дней назад',

            //future
            date('Y-m-d H:i:s', $nowTime + 1) => 'менее чем через минуту',
            date('Y-m-d H:i:s', $nowTime + 60) => 'через минуту',
            date('Y-m-d H:i:s', $nowTime + 2*60) => 'через 2 минуты',
            date('Y-m-d H:i:s', $nowTime + 5*60) => 'через 5 минут',
            date('Y-m-d H:i:s', $nowTime + 60*60) => 'через час',
            date('Y-m-d H:i:s', $nowTime + 2*60*60) => 'через 2 часа',
            date('Y-m-d H:i:s', $nowTime + 5*60*60) => 'через 5 часов',
            date('Y-m-d H:i:s', $nowTime + 24*60*60) => 'завтра',
            date('Y-m-d H:i:s', $nowTime + 2*24*60*60) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3*24*60*60) => 'через 3 дня',
            date('Y-m-d H:i:s', $nowTime + 8*24*60*60) => 'через 8 дней',
            ($nowTime + 365*24*60*60) => 'через 365 дней',
        );

        foreach ($testData as $toTime => $expected)
            $this->assertEquals($expected, $this->_object->distanceOfTimeInWords($toTime));

        $toTime = new \DateTime();
        $toTime->setTimestamp($nowTime + 365*24*60*60);
        $this->assertEquals('через 365 дней', $this->_object->distanceOfTimeInWords($toTime));

        $toTime = ($nowTime + 365 * 24 * 60 * 60 + 5*60);
        $this->assertEquals('через 365 дней, 5 минут', $this->_object->distanceOfTimeInWords($toTime, null, 3));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testDistanceOfTimeToTime()
    {
        $nowTimeStamp = strtotime('now');
        $toTimeStamp = $nowTimeStamp + 365*24*60*60;

        $timeZone = new \DateTimeZone('Europe/London');
        $toTime = new \DateTime('now', $timeZone);

        $fromTime = new \DateTime('now');
        $fromTime->setTimestamp($toTimeStamp);

        $expected = 'через 365 дней';
        $this->assertEquals(
            $expected, $this->_object->distanceOfTimeInWords($toTimeStamp, $nowTimeStamp, 1, $timeZone)
        );
        $this->assertEquals($expected, $this->_object->distanceOfTimeInWords($fromTime, $toTime, 1, $timeZone));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testDistanceOfTimeAccuracy()
    {
        $nowTime = strtotime('now');
        $futureTime = $nowTime + 364*24*60*60 + 3*60*60 + 4*60;
        $pastTime = $nowTime - 364*24*60*60 - 3*60*60 - 4*60;

        //future
        $this->assertEquals('через 364 дня', $this->_object->distanceOfTimeInWords($futureTime, $nowTime, 1));
        $this->assertEquals('через 364 дня, 3 часа', $this->_object->distanceOfTimeInWords($futureTime, $nowTime, 2));
        $this->assertEquals(
            'через 364 дня, 3 часа, 4 минуты', $this->_object->distanceOfTimeInWords($futureTime, $nowTime, 3)
        );

        //past
        $this->assertEquals('364 дня назад', $this->_object->distanceOfTimeInWords($pastTime, $nowTime, 1));
        $this->assertEquals('364 дня, 3 часа назад', $this->_object->distanceOfTimeInWords($pastTime, $nowTime, 2));
        $this->assertEquals(
            '364 дня, 3 часа, 4 минуты назад', $this->_object->distanceOfTimeInWords($pastTime, $nowTime, 3)
        );
    }

    /**
     * @covers \php_rutils\Dt::getAge
     */
    public function testGetAge()
    {
        $birthDate = time() - 86400 * 800; // 2 full years

        $this->assertEquals(2, $this->_object->getAge($birthDate));
    }
}
