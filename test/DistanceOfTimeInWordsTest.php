<?php
namespace php_rutils\test;

use php_rutils\RUtils;

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
            date('Y-m-d H:i:s', $nowTime - 60) => "минуту\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60) => "2 минуты\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60) => "5 минут\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 60 * 60) => "час\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60 * 60) => "2 часа\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60 * 60) => "5 часов\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 24 * 60 * 60) => 'вчера',
            date('Y-m-d H:i:s', $nowTime - 2 * 24 * 60 * 60) => 'позавчера',
            date('Y-m-d H:i:s', $nowTime - 3 * 24 * 60 * 60) => "3 дня\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 8 * 24 * 60 * 60 - 1 * 60 * 60) => "8 дней\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 32 * 24 * 60 * 60) => "месяц\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 32 * 24 * 60 * 60) => "2 месяца\xC2\xA0назад",
            ($nowTime - 366 * 24 * 60 * 60) => "год\xC2\xA0назад",
            ($nowTime - 2 * 370 * 24 * 60 * 60) => "2 года\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60) => "10 лет\xC2\xA0назад",
            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? "через\xC2\xA0минуту" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60) => ($dNowTomorrow >= 120 ? "через\xC2\xA02 минуты" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60) => ($dNowTomorrow >= 300 ? "через\xC2\xA05 минут" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60 * 60) => ($dNowTomorrow >= 3600 ? "через\xC2\xA0час" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60 * 60) => ($dNowTomorrow >= 7200 ? "через\xC2\xA02 часа" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60 * 60) => ($dNowTomorrow >= 18000 ? "через\xC2\xA05 часов" : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3 * 24 * 60 * 60) => "через\xC2\xA03 дня",
            date('Y-m-d H:i:s', $nowTime + 8 * 24 * 60 * 60) => "через\xC2\xA08 дней",
            date('Y-m-d H:i:s', $nowTime + 32 * 24 * 60 * 60) => "через\xC2\xA0месяц",
            date('Y-m-d H:i:s', $nowTime + 2 * 32 * 24 * 60 * 60) => "через\xC2\xA02 месяца",
            ($nowTime + 366 * 24 * 60 * 60) => "через\xC2\xA0год",
            ($nowTime + 2 * 370 * 24 * 60 * 60) => "через\xC2\xA02 года",
            ($nowTime + 10 * 370 * 24 * 60 * 60) => "через\xC2\xA010 лет",
        );

        foreach ($testData as $toTime => $expected) {
            $this->assertEquals($expected, $this->_object->distanceOfTimeInWords($toTime));
        }
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
            date('Y-m-d H:i:s', $nowTime - 60) => "минуту\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60) => "2 минуты\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60) => "5 минут\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 60 * 60) => "час\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60 * 60) => "2 часа\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60 * 60) => "5 часов\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 24 * 60 * 60) => 'вчера',
            date('Y-m-d H:i:s', $nowTime - 2 * 24 * 60 * 60) => 'позавчера',
            date('Y-m-d H:i:s', $nowTime - 3 * 24 * 60 * 60) => "3 дня\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 8 * 24 * 60 * 60 - 1 * 60 * 60) => "8 дней\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 32 * 24 * 60 * 60) => "месяц\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 32 * 24 * 60 * 60) => "2 месяца\xC2\xA0назад",
            ($nowTime - 366 * 24 * 60 * 60) => "год\xC2\xA0назад",
            ($nowTime - 2 * 370 * 24 * 60 * 60) => "2 года\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 + 2 * 24 * 60 * 60 + 12) => "10 лет, %d месяц%S\xC2\xA0назад",
            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? "через\xC2\xA0минуту" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60) => ($dNowTomorrow >= 120 ? "через\xC2\xA02 минуты" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60) => ($dNowTomorrow >= 300 ? "через\xC2\xA05 минут" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60 * 60) => ($dNowTomorrow >= 3600 ? "через\xC2\xA0час" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60 * 60) => ($dNowTomorrow >= 7200 ? "через\xC2\xA02 часа" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60 * 60) => ($dNowTomorrow >= 18000 ? "через\xC2\xA05 часов" : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3 * 24 * 60 * 60) => "через\xC2\xA03 дня",
            date('Y-m-d H:i:s', $nowTime + 8 * 24 * 60 * 60) => "через\xC2\xA08 дней",
            date('Y-m-d H:i:s', $nowTime + 32 * 24 * 60 * 60) => "через\xC2\xA0месяц",
            date('Y-m-d H:i:s', $nowTime + 2 * 32 * 24 * 60 * 60) => "через\xC2\xA02 месяца",
            ($nowTime + 366 * 24 * 60 * 60) => "через\xC2\xA0год",
            ($nowTime + 2 * 370 * 24 * 60 * 60) => "через\xC2\xA02 года",
            ($nowTime + 10 * 370 * 24 * 60 * 60 + 2 * 24 * 60 * 60 + 12) => "через\xC2\xA010 лет, %d месяц%S",
        );

        foreach ($testData as $toTime => $format) {
            $this->assertStringMatchesFormat(
                $format,
                $this->_object->distanceOfTimeInWords($toTime, null, RUtils::ACCURACY_MONTH)
            );
        }
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
            date('Y-m-d H:i:s', $nowTime - 60) => "минуту\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60) => "2 минуты\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60) => "5 минут\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 60 * 60) => "час\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60 * 60) => "2 часа\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60 * 60) => "5 часов\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 24 * 60 * 60) => 'вчера',
            date('Y-m-d H:i:s', $nowTime - 2 * 24 * 60 * 60) => 'позавчера',
            date('Y-m-d H:i:s', $nowTime - 3 * 24 * 60 * 60) => "3 дня\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 8 * 24 * 60 * 60 - 1 * 60 * 60) => "8 дней\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 32 * 24 * 60 * 60) => "1 месяц, %d д%s\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 32 * 24 * 60 * 60) => "2 месяца, %d д%s\xC2\xA0назад",
            ($nowTime - 366 * 24 * 60 * 60) => "1 год, %d д%s\xC2\xA0назад",
            ($nowTime - 2 * 370 * 24 * 60 * 60 - 72 * 24 * 60 * 60 - 12 * 60) => "2 года, 2 месяца, %d д%s\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 + 2 * 24 * 60 * 60 + 12) => "10 лет, %d месяц%S\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 - 62 * 24 * 60 * 60) => "10 лет, %d месяц%s, %d д%s\xC2\xA0назад",
            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? "через\xC2\xA0минуту" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60) => ($dNowTomorrow >= 120 ? "через\xC2\xA02 минуты" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60) => ($dNowTomorrow >= 300 ? "через\xC2\xA05 минут" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60 * 60) => ($dNowTomorrow >= 3600 ? "через\xC2\xA0час" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60 * 60) => ($dNowTomorrow >= 7200 ? "через\xC2\xA02 часа" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60 * 60) => ($dNowTomorrow >= 18000 ? "через\xC2\xA05 часов" : 'завтра'),
            date('Y-m-d H:i:s', $tomorrow) => 'завтра',
            date('Y-m-d H:i:s', $afterTomorrow) => 'послезавтра',
            date('Y-m-d H:i:s', $nowTime + 3 * 24 * 60 * 60) => "через\xC2\xA03 дня",
            date('Y-m-d H:i:s', $nowTime + 8 * 24 * 60 * 60) => "через\xC2\xA08 дней",
            date('Y-m-d H:i:s', $nowTime + 32 * 24 * 60 * 60) => "через\xC2\xA01 месяц, %d д%s",
            date('Y-m-d H:i:s', $nowTime + 2 * 32 * 24 * 60 * 60) => "через\xC2\xA02 месяца, %d д%s",
            ($nowTime + 367 * 24 * 60 * 60) => "через\xC2\xA01 год, %d д%s",
            ($nowTime + 2 * 370 * 24 * 60 * 60) => "через\xC2\xA02 года, %d д%s",
            ($nowTime + 10 * 370 * 24 * 60 * 60 + 65 * 24 * 60 * 60 + 12) => "через\xC2\xA010 лет, %d месяц%S, %d д%s",
        );

        foreach ($testData as $toTime => $format) {
            $this->assertStringMatchesFormat(
                $format,
                $this->_object->distanceOfTimeInWords($toTime, null, RUtils::ACCURACY_DAY)
            );
        }
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
            date('Y-m-d H:i:s', $nowTime - 60) => "минуту\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60) => "2 минуты\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60) => "5 минут\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 60 * 60) => "час\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 60 * 60) => "2 часа\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 5 * 60 * 60) => "5 часов\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 24 * 60 * 60) => 'вчера',
            date('Y-m-d H:i:s', $nowTime - 2 * 24 * 60 * 60) => 'позавчера',
            date('Y-m-d H:i:s', $nowTime - 3 * 24 * 60 * 60) => "3 дня\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 8 * 24 * 60 * 60 - 1 * 60 * 60) => "8 дней, 1 час\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 32 * 24 * 60 * 60) => "1 месяц, %d д%s\xC2\xA0назад",
            date('Y-m-d H:i:s', $nowTime - 2 * 32 * 24 * 60 * 60) => "2 месяца, %d д%s\xC2\xA0назад",
            ($nowTime - 366 * 24 * 60 * 60) => "1 год, %d д%s\xC2\xA0назад",
            ($nowTime - 2 * 370 * 24 * 60 * 60 - 72 * 24 * 60 * 60 - 12 * 60) => "2 года, 2 месяца, %d д%s\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 + 2 * 24 * 60 * 60 + 12) => "10 лет, %d месяц%S\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 - 62 * 24 * 60 * 60) => "10 лет, %d месяц%s, %d д%s\xC2\xA0назад",
            ($nowTime - 10 * 370 * 24 * 60 * 60 - 65 * 24 * 60 * 60 - 90 * 60) => "10 лет, %d месяц%S, %d д%s, %d ч%s, %d минут%S\xC2\xA0назад",
            //future
            date('Y-m-d H:i:s', $nowTime + 1) => ($dNowTomorrow >= 1 ? 'менее чем через минуту' : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60) => ($dNowTomorrow >= 60 ? "через\xC2\xA0минуту" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60) => ($dNowTomorrow >= 120 ? "через\xC2\xA02 минуты" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60) => ($dNowTomorrow >= 300 ? "через\xC2\xA05 минут" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 60 * 60) => ($dNowTomorrow >= 3600 ? "через\xC2\xA0час" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 2 * 60 * 60) => ($dNowTomorrow >= 7200 ? "через\xC2\xA02 часа" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 5 * 60 * 60) => ($dNowTomorrow >= 18000 ? "через\xC2\xA05 часов" : 'завтра'),
            date('Y-m-d H:i:s', $nowTime + 3 * 24 * 60 * 60) => "через\xC2\xA03 дня",
            date('Y-m-d H:i:s', $nowTime + 8 * 24 * 60 * 60) => "через\xC2\xA08 дней",
            date('Y-m-d H:i:s', $nowTime + 32 * 24 * 60 * 60) => "через\xC2\xA01 месяц, %d д%s",
            date('Y-m-d H:i:s', $nowTime + 2 * 32 * 24 * 60 * 60) => "через\xC2\xA02 месяца, %d д%s",
            ($nowTime + 367 * 24 * 60 * 60) => "через\xC2\xA01 год, %d д%s",
            ($nowTime + 2 * 370 * 24 * 60 * 60) => "через\xC2\xA02 года, %d д%s",
            ($nowTime + 10 * 370 * 24 * 60 * 60 + 65 * 24 * 60 * 60 + 90 * 60) => "через\xC2\xA010 лет, %d месяц%S, %d д%s, %d ч%s, %d минут%S",
        );

        foreach ($testData as $toTime => $format) {
            $this->assertStringMatchesFormat(
                $format,
                $this->_object->distanceOfTimeInWords(
                    $toTime,
                    null,
                    RUtils::ACCURACY_MINUTE
                )
            );
        }
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
            "через\xC2\xA067 лет, 3 месяца, 12 дней, 4 часа, 2 минуты",
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
            "67 лет, 3 месяца, 12 дней, 4 часа, 2 минуты\xC2\xA0назад",
            $this->_object->distanceOfTimeInWords($toTime, $fromTime, RUtils::ACCURACY_MINUTE)
        );
    }
}
