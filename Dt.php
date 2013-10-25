<?php
namespace php_rutils;

use php_rutils\struct\TimeParams;

/**
 * Russian dates without locales
 * Class Dt
 * @package php_rutils
 */
class Dt
{
    const PREFIX_IN = "через"; //Day names (abbreviated, full, inflected, preposition)
    const SUFFIX_AGO = "назад"; //Month names (abbreviated, full, inflected)

    private static $_DAY_NAMES = array(
        array('пн', 'понедельник', 'понедельник', "в\xC2\xA0"),
        array('вт', 'вторник', 'вторник', "во\xC2\xA0"),
        array('ср', 'среда', 'среду', "в\xC2\xA0"),
        array('чт', 'четверг', 'четверг', "в\xC2\xA0"),
        array('пт', 'пятница', 'пятницу', "в\xC2\xA0"),
        array('сб', 'суббота', 'субботу', "в\xC2\xA0"),
        array('вск', 'воскресенье', 'воскресенье', "в\xC2\xA0")
    ); //Day alternatives (i.e. one day ago -> yesterday)

    private static $_MONTH_NAMES = array(
        array("янв", "январь", "января"),
        array("фев", "февраль", "февраля"),
        array("мар", "март", "марта"),
        array("апр", "апрель", "апреля"),
        array("май", "май", "мая"),
        array("июн", "июнь", "июня"),
        array("июл", "июль", "июля"),
        array("авг", "август", "августа"),
        array("сен", "сентябрь", "сентября"),
        array("окт", "октябрь", "октября"),
        array("ноя", "ноябрь", "ноября"),
        array("дек", "декабрь", "декабря"),
    ); //Forms (1, 2, 5) for noun 'day'

    private static $_PAST_ALTERNATIVES = array("вчера", "позавчера");
    private static $_DAY_VARIANTS = array("день", "дня", "дней"); //Forms (1, 2, 5) for noun 'minute'
    private static $_HOUR_VARIANTS = array("час", "часа", "часов"); //Prefix 'in' (i.e. B{in} three hours)
    private static $_MINUTE_VARIANTS = array("минуту", "минуты", "минут"); //Prefix 'ago' (i.e. three hours B{ago})

    /**
     * Russian \DateTime::format
     * @param array|\php_rutils\struct\TimeParams $params Params structure
     * @return string Date/time string representation
     */
    public function ruStrFTime($params = null)
    {
        //Params handle
        if ($params === null)
            $params = new TimeParams();
        elseif (is_array($params))
            $params = TimeParams::create($params);
        else
            $params = clone $params;

        if ($params->date === null)
            $params->date = new \DateTime();
        else
            $params->date = $this->_processDateTime($params->date);

        if (is_string($params->timezone))
            $params->timezone = new \DateTimeZone($params->timezone);
        if ($params->timezone)
            $params->date->setTimezone($params->timezone);

        //Format processing
        $weekday = $params->date->format('N') - 1;
        $month = $params->date->format('n') - 1;

        $prepos = $params->preposition ? self::$_DAY_NAMES[$weekday][3] : '';

        $monthIdx = $params->monthInflected ? 2 : 1;
        $dayIdx = ($params->dayInflected || $params->preposition) ? 2 : 1;

        $search = array('D', 'l', 'M', 'F');
        $replace = array(
            $prepos.self::$_DAY_NAMES[$weekday][0],
            $prepos.self::$_DAY_NAMES[$weekday][$dayIdx],
            self::$_MONTH_NAMES[$month][0],
            self::$_MONTH_NAMES[$month][$monthIdx],
        );

        //for russian typography standard,
        //1 April 2007, but 01.04.2007
        if (strpos($params->format, 'F') !== false || strpos($params->format, 'M') !== false) {
            $search[] = 'd';
            $replace[] = 'j';
        }

        $params->format = str_replace($search, $replace, $params->format);

        //Create date/time string
        return $params->date->format($params->format);
    }

    /**
     * Process mixed format date
     * @param mixed $dateTime
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    private function _processDateTime($dateTime)
    {
        if (is_numeric($dateTime)) {
            $timestamp = $dateTime;
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp);
        }
        elseif (empty($dateTime)) {
            throw new \InvalidArgumentException('Date/time is empty');
        }
        elseif (is_string($dateTime)) {
            $dateTime = new \DateTime($dateTime);
        }

        if (!($dateTime instanceof \DateTime)) {
            throw new \InvalidArgumentException('Incorrect date/time type');
        }
        return $dateTime;
    }

    /**
     * Represents distance of time in words
     * @param string|int|\DateTime $toTime Source time
     * @param string|int|\DateTime $fromTime Target time
     * @param int $accuracy Level of accuracy (1..3), default=1
     * @param string|\DateTimeZone|null $timeZone Time zone
     * @throws \InvalidArgumentException
     * @return string Distance of time in words
     */
    public function distanceOfTimeInWords($toTime, $fromTime = null, $accuracy = 1, $timeZone = null)
    {
        if ($accuracy < 1 || $accuracy > 3)
            throw new \InvalidArgumentException('Wrong accuracy value (must be 1..3)');

        /* @var $toTime \DateTime */
        /* @var $fromTime \DateTime */
        /* @var $timeZone \DateTimeZone|null */
        /* @var $fromCurrent bool */
        list($toTime, $fromTime, $timeZone, $fromCurrent) = $this->_createFunctionParams($toTime, $fromTime, $timeZone);
        $interval = $toTime->diff($fromTime);

        $words = array();
        $values = array();
        $alternatives = array();
        $this->_fillCollections($interval, $fromCurrent, $words, $values, $alternatives);
        $this->_trimArrays($words);
        $this->_trimArrays($values);

        $limit = min($accuracy, sizeof($words));
        $realWords = array_slice($words, 0, $limit);
        $realValues = array_slice($values, 0, $limit);

        $length0 = sizeof($realValues);
        $this->_trimArrays($realValues, $realWords);
        $limit -= ($length0 - sizeof($realValues));

        //if diff less than one minute
        if ($interval->i == 0 && !$realWords) {
            if ($interval->invert)
                $zeroStr = 'менее чем через минуту';
            else
                $zeroStr = 'менее минуты назад';
            return $zeroStr;
        }

        //if diff is 1 or 2 days
        $days = $interval->days;
        if ($fromCurrent && $limit == 1 && $days < 3) {
            if ($interval->invert == 0 && ($days == 1 || $days == 2)) {
                $variant = $days - 1;
                return self::$_PAST_ALTERNATIVES[$variant];
            }
            elseif ($interval->invert && ($days == 0 || $days == 1)) {
                $tomorrow = new \DateTime('today', $timeZone);
                $tomorrow->add(new \DateInterval('P1D'));
                $afterTomorrow = new \DateTime('today', $timeZone);
                $afterTomorrow->add(new \DateInterval('P2D'));

                if ($toTime >= $tomorrow && $toTime < $afterTomorrow)
                    return 'завтра';
                elseif ($days == 1 && $toTime >= $afterTomorrow)
                    return 'послезавтра';
            }
        }

        //general case
        $realStr = implode(', ', $realWords);
        if ($limit == 1 && $fromCurrent && $alternatives)
            $altStr = $alternatives[0];
        else
            $altStr = '';

        $resultStr = $altStr ? $altStr : $realStr;
        if ($interval->invert)
            $resultStr = self::PREFIX_IN.' '.$resultStr;
        else
            $resultStr = $resultStr.' '.self::SUFFIX_AGO;

        return $resultStr;
    }

    private function _createFunctionParams($toTime, $fromTime, $timeZone)
    {
        $toTime = $this->_processDateTime($toTime);

        $fromCurrent = false;
        if ($fromTime === null) {
            $fromTime = new \DateTime();
            $fromCurrent = true;
        }
        else {
            $fromTime = $this->_processDateTime($fromTime);
        }

        if (is_string($timeZone))
            $timeZone = new \DateTimeZone($timeZone);

        if ($timeZone) {
            $toTime->setTimezone($timeZone);
            $fromTime->setTimezone($timeZone);
        }

        return array($toTime, $fromTime, $timeZone, $fromCurrent);
    }

    private function _fillCollections($interval, $toCurrent, &$words, &$values, &$alternatives)
    {
        $numeral = new Numeral();

        $days = $interval->days;
        $words[] = $numeral->getPlural($days, self::$_DAY_VARIANTS);
        $values[] = $days;

        $hours = $interval->h;
        if ($hours > 0) {
        	$words[] = $numeral->getPlural($hours, self::$_HOUR_VARIANTS);
        	$values[] = $hours;
		}

        if ($days == 0 && $hours == 1 && $toCurrent)
            $alternatives[] = 'час';

        $minutes = $interval->i;
        $words[] = $numeral->getPlural($minutes, self::$_MINUTE_VARIANTS);
        $values[] = $minutes;

        if ($days == 0 && $hours == 0 && $minutes == 1 && $toCurrent)
            $alternatives[] = 'минуту';
    }

    private function _trimArrays(array &$arr, array &$arr2=null)
    {
        $i = sizeof($arr) - 1;
        while ($i >= 0 && $arr[$i] == 0) {
            array_pop($arr);
            if ($arr2)
                array_pop($arr2);
            --$i;
        }

        while (sizeof($arr) && $arr[0] == 0) {
            array_shift($arr);
            if ($arr2)
                array_shift($arr2);
        }
    }

    /**
     * Calculates age
     * @param string|int|\DateTime $birthDate Date of birth
     * @throws \InvalidArgumentException
     * @return int Full years age
     */
    public function getAge($birthDate)
    {
        $birthDate = $this->_processDateTime($birthDate);
        $interval = $birthDate->diff(new \DateTime());
        if ($interval->invert)
            throw new \InvalidArgumentException('Birth date is in future');
        return $interval->y;
    }
}
