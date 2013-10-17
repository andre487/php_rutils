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
    private static $_DAY_NAMES = array(
        array('пн', 'понедельник', 'понедельник', "в\xa0"),
        array('вт', 'вторник', 'вторник', "во\xa0"),
        array('ср', 'среда', 'среду', "в\xa0"),
        array('чт', 'четверг', 'четверг', "в\xa0"),
        array('пт', 'пятница', 'пятницу', "в\xa0"),
        array('сб', 'суббота', 'субботу', "в\xa0"),
        array('вск', 'воскресенье', 'воскресенье', "в\xa0")
    ); //Day names (abbreviated, full, inflected, preposition)

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
    ); //Month names (abbreviated, full, inflected)

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

        if (is_string($params->timezone))
            $params->timezone = new \DateTimeZone($params->timezone);

        if ($params->date === null)
            $params->date = new \DateTime('now', $params->timezone);
        else if (is_string($params->date))
            $params->date = new \DateTime($params->date, $params->timezone);

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
}
