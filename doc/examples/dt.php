<?php
namespace php_rutils\doc\examples;

use php_rutils\Dt;
use php_rutils\RUtils;
use php_rutils\struct\TimeParams;

require '_begin.php';

//Now date, params as TimeParams class instance
$params = new TimeParams();
$params->date = null; //this is default value
$params->format = 'сегодня d F Y года';
$params->monthInflected = true;
echo RUtils::dt()->ruStrFTime($params), PHP_EOL;
//Result: сегодня 22 октября 2013 года


//Historical date, params as array (fields same as in the TimeParams object),
//date as string (Unix timestamp and \DateTime instance also available)
$params = array(
    'date' => '09-05-1945',
    'format' => 'l d F Y была одержана победа над немецко-фашистскими захватчиками',
    'monthInflected' => true,
    'preposition' => true,
);
echo RUtils::dt()->ruStrFTime($params), PHP_EOL;
//Result: в среду 9 мая 1945 была одержана победа над немецко-фашистскими захватчиками


//Time distance from now to fixed date in past
$toTime = new \DateTime('05-06-1945'); //Unix timestamp and string also available
echo RUtils::dt()->distanceOfTimeInWords($toTime), PHP_EOL;
//Result: 68 лет назад

$toTime = strtotime('05-06-1945');
$fromTime = null; //now
$accuracy = RUtils::ACCURACY_MINUTE; //years, months, days, hours, minutes
echo RUtils::dt()->distanceOfTimeInWords($toTime, $fromTime, $accuracy), PHP_EOL;
//Result: 68 лет, 4 месяца, 21 день, 19 часов, 12 минут назад


//Time distance from fixed date in past to date in future
$fromTime = '1988-01-01 11:40';
$toTime = '2088-01-01 12:35';
$accuracy = RUtils::ACCURACY_MINUTE; //years, months, days, hours, minutes
echo RUtils::dt()->distanceOfTimeInWords($toTime, $fromTime, $accuracy), PHP_EOL;
//Result: через 100 лет, 55 минут


//Get age by date
$birthDate = strtotime('today - 25 years');
echo RUtils::dt()->getAge($birthDate), PHP_EOL;
//Result: 25


//Custom prefix in
Dt::$PREFIX_IN = 'опосля';
$fromTime = '1988-01-01 11:40';
$toTime = '2088-01-01 12:35';
$accuracy = RUtils::ACCURACY_MINUTE; //years, months, days, hours, minutes
echo RUtils::dt()->distanceOfTimeInWords($toTime, $fromTime, $accuracy), PHP_EOL;
//Result: опосля 100 лет, 55 минут


require '_end.php';
