<?php
namespace php_rutils\doc\examples;

$content = ob_get_clean();
if (CLI && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    $content = iconv('UTF-8', 'cp866//IGNORE', $content);
echo $content, PHP_EOL;
