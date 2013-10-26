<?php
namespace php_rutils\test;

use php_rutils\RUtils;
use php_rutils\struct\TimeParams;

class DistanceOfTimeInWordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \php_rutils\Dt
     */
    private $_object;

    protected function setUp()
    {
        parent::setUp();
        $this->_object = RUtils::dt();
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testAccuracyYear()
    {
        $nowTime = strtotime('now');
        $tomorrow = strtotime('tomorrow');
        $afterTomorrow = strtotime('tomorrow + 24 hours');
        $dNowTomorrow = $tomorrow - $nowTime;

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
            date('Y-m-d H:i:s', $nowTime - 32*24*60*60) => 'месяц назад',
            date('Y-m-d H:i:s', $nowTime - 2*32*24*60*60) => '2 месяца назад',
            ($nowTime - 366*24*60*60) => 'год назад',
            ($nowTime - 2*370*24*60*60) => '2 года назад',
            ($nowTime - 10*370*24*60*60) => '10 лет назад',

            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? 'через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60) => ($dNowTomorrow >= 120 ? 'через 2 минуты' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60) => ($dNowTomorrow >= 300 ? 'через 5 минут' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60*60) => ($dNowTomorrow >= 3600 ? 'через час' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60*60) => ($dNowTomorrow >= 7200 ? 'через 2 часа' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60*60) => ($dNowTomorrow >= 18000 ? 'через 5 часов' : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3*24*60*60) => 'через 3 дня',
            date('Y-m-d H:i:s', $nowTime + 8*24*60*60) => 'через 8 дней',
            date('Y-m-d H:i:s', $nowTime + 32*24*60*60) => 'через месяц',
            date('Y-m-d H:i:s', $nowTime + 2*32*24*60*60) => 'через 2 месяца',
            ($nowTime + 366*24*60*60) => 'через год',
            ($nowTime + 2*370*24*60*60) => 'через 2 года',
            ($nowTime + 10*370*24*60*60) => 'через 10 лет',
        );

        foreach ($testData as $toTime => $expected)
            $this->assertEquals($expected, $this->_object->distanceOfTimeInWords($toTime));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testAccuracyMonth()
    {
        $nowTime = strtotime('now');
        $tomorrow = strtotime('tomorrow');
        $afterTomorrow = strtotime('tomorrow + 24 hours');
        $dNowTomorrow = $tomorrow - $nowTime;

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
            date('Y-m-d H:i:s', $nowTime - 32*24*60*60) => 'месяц назад',
            date('Y-m-d H:i:s', $nowTime - 2*32*24*60*60) => '2 месяца назад',
            ($nowTime - 366*24*60*60) => 'год назад',
            ($nowTime - 2*370*24*60*60) => '2 года назад',
            ($nowTime - 10*370*24*60*60 + 2*24*60*60 + 12) => '10 лет, %d месяц%S назад',

            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? 'через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60) => ($dNowTomorrow >= 120 ? 'через 2 минуты' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60) => ($dNowTomorrow >= 300 ? 'через 5 минут' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60*60) => ($dNowTomorrow >= 3600 ? 'через час' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60*60) => ($dNowTomorrow >= 7200 ? 'через 2 часа' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60*60) => ($dNowTomorrow >= 18000 ? 'через 5 часов' : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3*24*60*60) => 'через 3 дня',
            date('Y-m-d H:i:s', $nowTime + 8*24*60*60) => 'через 8 дней',
            date('Y-m-d H:i:s', $nowTime + 32*24*60*60) => 'через месяц',
            date('Y-m-d H:i:s', $nowTime + 2*32*24*60*60) => 'через 2 месяца',
            ($nowTime + 366*24*60*60) => 'через год',
            ($nowTime + 2*370*24*60*60) => 'через 2 года',
            ($nowTime + 10*370*24*60*60 + 2*24*60*60 + 12) => 'через 10 лет, %d месяц%S',
        );

        foreach ($testData as $toTime => $format)
            $this->assertStringMatchesFormat($format, $this->_object->distanceOfTimeInWords($toTime, null, RUtils::ACCURACY_MONTH));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testAccuracyDay()
    {
        $nowTime = strtotime('now');
        $tomorrow = strtotime('tomorrow');
        $afterTomorrow = strtotime('tomorrow + 24 hours');
        $dNowTomorrow = $tomorrow - $nowTime;

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
            date('Y-m-d H:i:s', $nowTime - 32*24*60*60) => '1 месяц, %d д%s назад',
            date('Y-m-d H:i:s', $nowTime - 2*32*24*60*60) => '2 месяца, %d д%s назад',
            ($nowTime - 366*24*60*60) => '1 год, %d д%s назад',
            ($nowTime - 2*370*24*60*60 - 72*24*60*60 - 12*60) => '2 года, 2 месяца, %d д%s назад',
            ($nowTime - 10*370*24*60*60 + 2*24*60*60 + 12) => '10 лет, %d месяц%S назад',
            ($nowTime - 10*370*24*60*60 - 62*24*60*60) => '10 лет, %d месяц%s, %d д%s назад',

            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? 'через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60) => ($dNowTomorrow >= 120 ? 'через 2 минуты' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60) => ($dNowTomorrow >= 300 ? 'через 5 минут' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60*60) => ($dNowTomorrow >= 3600 ? 'через час' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60*60) => ($dNowTomorrow >= 7200 ? 'через 2 часа' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60*60) => ($dNowTomorrow >= 18000 ? 'через 5 часов' : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3*24*60*60) => 'через 3 дня',
            date('Y-m-d H:i:s', $nowTime + 8*24*60*60) => 'через 8 дней',
            date('Y-m-d H:i:s', $nowTime + 32*24*60*60) => 'через 1 месяц, %d д%s',
            date('Y-m-d H:i:s', $nowTime + 2*32*24*60*60) => 'через 2 месяца, %d д%s',
            ($nowTime + 367*24*60*60) => 'через 1 год, %d д%s',
            ($nowTime + 2*370*24*60*60) => 'через 2 года, %d д%s',
            ($nowTime + 10*370*24*60*60 + 65*24*60*60 + 12) => 'через 10 лет, %d месяц%S, %d д%s',
        );

        foreach ($testData as $toTime => $format)
            $this->assertStringMatchesFormat($format, $this->_object->distanceOfTimeInWords($toTime, null, RUtils::ACCURACY_DAY));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testAccuracyMinute()
    {
        $nowTime = strtotime('now');
        $tomorrow = strtotime('tomorrow');
        $dNowTomorrow = $tomorrow - $nowTime;

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
            date('Y-m-d H:i:s', $nowTime - 8*24*60*60 - 1*60*60) => '8 дней, 1 час назад',
            date('Y-m-d H:i:s', $nowTime - 32*24*60*60) => '1 месяц, %d д%s назад',
            date('Y-m-d H:i:s', $nowTime - 2*32*24*60*60) => '2 месяца, %d д%s назад',
            ($nowTime - 366*24*60*60) => '1 год, %d д%s назад',
            ($nowTime - 2*370*24*60*60 - 72*24*60*60 - 12*60) => '2 года, 2 месяца, %d д%s назад',
            ($nowTime - 10*370*24*60*60 + 2*24*60*60 + 12) => '10 лет, %d месяц%S назад',
            ($nowTime - 10*370*24*60*60 - 62*24*60*60) => '10 лет, %d месяц%s, %d д%s назад',
            ($nowTime - 10*370*24*60*60 - 65*24*60*60 - 90*60) => '10 лет, %d месяц%S, %d д%s, %d ч%s, %d минут%S назад',

            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? 'через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60) => ($dNowTomorrow >= 120 ? 'через 2 минуты' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60) => ($dNowTomorrow >= 300 ? 'через 5 минут' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60*60) => ($dNowTomorrow >= 3600 ? 'через час' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2*60*60) => ($dNowTomorrow >= 7200 ? 'через 2 часа' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5*60*60) => ($dNowTomorrow >= 18000 ? 'через 5 часов' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 3*24*60*60) => 'через 3 дня',
            date('Y-m-d H:i:s', $nowTime + 8*24*60*60) => 'через 8 дней',
            date('Y-m-d H:i:s', $nowTime + 32*24*60*60) => 'через 1 месяц, %d д%s',
            date('Y-m-d H:i:s', $nowTime + 2*32*24*60*60) => 'через 2 месяца, %d д%s',
            ($nowTime + 367*24*60*60) => 'через 1 год, %d д%s',
            ($nowTime + 2*370*24*60*60) => 'через 2 года, %d д%s',
            ($nowTime + 10*370*24*60*60 + 65*24*60*60 + 90*60) => 'через 10 лет, %d месяц%S, %d д%s, %d ч%s, %d минут%S',
        );

        foreach ($testData as $toTime => $format)
            $this->assertStringMatchesFormat($format, $this->_object->distanceOfTimeInWords($toTime, null, RUtils::ACCURACY_MINUTE));
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testFromTimePast()
    {
        $fromTime = new \DateTime();
        $fromTime->sub(new \DateInterval('P56Y'));

        $toTime = new \DateTime();
        $toTime->add(new \DateInterval('P11Y3M12DT4H2M'));

        $this->assertEquals(
            'через 67 лет, 3 месяца, 12 дней, 4 часа, 2 минуты',
            $this->_object->distanceOfTimeInWords($toTime, $fromTime, RUtils::ACCURACY_MINUTE)
        );
    }

    /**
     * @covers \php_rutils\Dt::distanceOfTimeInWords
     */
    public function testFromTimeFuture()
    {
        $fromTime = new \DateTime();
        $fromTime->add(new \DateInterval('P56Y'));

        $toTime = new \DateTime();
        $toTime->sub(new \DateInterval('P11Y3M12DT4H2M'));

        $this->assertEquals(
            '67 лет, 3 месяца, 12 дней, 4 часа, 2 минуты назад',
            $this->_object->distanceOfTimeInWords($toTime, $fromTime, RUtils::ACCURACY_MINUTE)
        );
    }
}
