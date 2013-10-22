<?php
namespace php_rutils\doc\examples;

use php_rutils\RUtils;
use php_rutils\TypoRules;

require '_begin.php';

$text = <<<TEXT
...Когда В. И. Пупкин увидел в газете ( это была "Сермяжная правда" № 45) рубрику Weather Forecast (r),
он не поверил своим глазам - температуру обещали +-451F.
TEXT;

//Standard rules
echo RUtils::typo()->typography($text), PHP_EOL;
/**
 * Result:
 * ...Когда В. И. Пупкин увидел в газете (это была «Сермяжная правда» №45) рубрику Weather Forecast®,
 * он не поверил своим глазам — температуру обещали ±451°F.
 */


//Extended rules
echo RUtils::typo()->typography($text, TypoRules::$EXTENDED_RULES), PHP_EOL;
/**
 * Result:
 * …Когда В. И. Пупкин увидел в газете (это была «Сермяжная правда» №45) рубрику Weather Forecast®,
 * он не поверил своим глазам — температуру обещали ±451 °F.
 */

//Custom rules
echo RUtils::typo()->typography($text, array(TypoRules::DASHES, TypoRules::CLEAN_SPACES)), PHP_EOL;
/**
 * Result:
 * ...Когда В. И. Пупкин увидел в газете (это была "Сермяжная правда" № 45) рубрику Weather Forecast (r),
 * он не поверил своим глазам — температуру обещали +-451F.
 */

require '_end.php';
