<?php
namespace php_rutils\struct;

class TimeParams
{
    /**
     * Date format, use PHP date() function specification:
     * http://www.php.net/manual/en/function.date.php
     * @var string
     */
    public $format = 'd.m.Y';

    /**
     * Date value, default=null translates to 'now'.
     * For string values use matched PHP rules:
     * http://www.php.net/manual/en/datetime.formats.php
     * Int value as Unix timestamp
     * @var string|int|\DateTime
     */
    public $date = null;

    /**
     * Timezone value, default=null translates to default PHP timezone.
     * For string values use matched PHP rules:
     * http://www.php.net/manual/en/timezones.php
     * @var string|\DateTimeZone|null
     */
    public $timezone = null;

    /**
     * Is month inflected (января, февраля), default false
     * @var bool
     */
    public $monthInflected = false;

    /**
     * Is day inflected (среду, пятницу) default false
     * @var bool
     */
    public $dayInflected = false;

    /**
     * Is preposition used (во вторник), default false
     * $preposition=true automatically implies $dayInflected=true
     * @var bool
     */
    public $preposition = false;

    /**
     * Create params from array or with default values
     * @param array|null $aParams
     * @return TimeParams
     */
    public static function create(array $aParams=null)
    {
        $params = new self();
        if ($aParams === null)
            return $params;

        foreach ($aParams as $name => $value)
            $params->$name = $value;

        return $params;
    }

    public function __set($name, $value)
    {
        throw new \InvalidArgumentException("Wrong parameter name: $name");
    }
}
