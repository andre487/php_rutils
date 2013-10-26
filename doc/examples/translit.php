<?php
namespace php_rutils\doc\examples;

use php_rutils\RUtils;

require '_begin.php';

//Translify
echo RUtils::translit()->translify('Муха - это маленькая птичка'), PHP_EOL;
//Result: Muxa - e`to malen`kaya ptichka

//Detranslify
echo RUtils::translit()->detranslify("Muxa - e`to malen`kaya ptichka"), PHP_EOL;
//Result: Муха - это маленькая птичка

//Prepare to use in URLs or file/dir name
echo RUtils::translit()->slugify('Муха — это маленькая птичка'), PHP_EOL;
//Result: muha-eto-malenkaya-ptichka

require '_end.php';
