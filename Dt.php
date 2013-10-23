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
        array('пн', 'понедельник', 'понедельник', "в\xc2\xa0"),
        array('вт', 'вторник', 'вторник', "во\xc2\xa0"),
        array('ср', 'среда', 'среду', "в\xc2\xa0"),
        array('чт', 'четверг', 'четверг', "в\xc2\xa0"),
        array('пт', 'пятница', 'пятницу', "в\xc2\xa0"),
        array('сб', 'суббота', 'субботу', "в\xc2\xa0"),
        array('вск', 'воскресенье', 'воскресенье', "в\xc2\xa0")
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

    private static $_DAY_ALTERNATIVES = array(
        1 => array("вчера", "завтра"),
        2 => array("позавчера", "послезавтра"),
    ); //Forms (1, 2, 5) for noun 'hour'

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

        if ($params->date === null) {
            $params->date = new \DateTime();
        }
        elseif (is_numeric($params->date)) {
            $timestamp = $params->date;
            $params->date = new \DateTime();
            $params->date->setTimestamp($timestamp);
        }
        elseif (is_string($params->date)) {
            $params->date = new \DateTime($params->date);
        }

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
        /* @var $toCurrent bool */
        list($toTime, $fromTime, $toCurrent) = $this->_createFunctionParams($toTime, $fromTime, $timeZone);
        $interval = $toTime->diff($fromTime);

        $words = array();
        $values = array();
        $alternatives = array();
        $this->_fillCollections($interval, $toCurrent, $words, $values, $alternatives);
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
        $days = $interval->d;
        if ($toCurrent && $limit == 1 && ($days == 1 || $days == 2)) {
            $altData = self::$_DAY_ALTERNATIVES[$days];
            $altDay = ($interval->invert) ? $altData[1] : $altData[0];
            return $altDay;
        }

        //general case
        $realStr = implode(', ', $realWords);
        if ($limit == 1 && $toCurrent && $alternatives)
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
        if (is_numeric($toTime)) {
            $timestamp = $toTime;
            $toTime = new \DateTime();
            $toTime->setTimestamp($timestamp);
        }
        else if (is_string($toTime)) {
            $toTime = new \DateTime($toTime);
        }

        $toCurrent = false;
        if ($fromTime === null) {
            $fromTime = new \DateTime();
            $toCurrent = true;
        }
        else if (is_numeric($fromTime)) {
            $timestamp = $fromTime;
            $fromTime = new \DateTime();
            $fromTime->setTimestamp($timestamp);
        }
        else if (is_string($fromTime)) {
            $fromTime = new \DateTime($fromTime);
        }

        if (is_string($timeZone))
            $timeZone = new \DateTimeZone($timeZone);

        if ($timeZone) {
            $toTime->setTimezone($timeZone);
            $fromTime->setTimezone($timeZone);
        }

        return array($toTime, $fromTime, $toCurrent);
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
}
