<?php
namespace php_rutils\doc\examples;

use php_rutils\RUtils;

require '_begin.php';

//Translify
echo RUtils::translit()->translify('Муха — это маленькая птичка'), PHP_EOL;
//Result: Muha - eto malen'kaya ptichka

//Detranslify
echo RUtils::translit()->detranslify("SCHuka"), PHP_EOL;
//Result: Щука

//Prepare to use in URLs or file/dir name
echo RUtils::translit()->slugify('Муха — это маленькая птичка'), PHP_EOL;
//Result: muha---eto-malenkaya-ptichka

require '_end.php';
