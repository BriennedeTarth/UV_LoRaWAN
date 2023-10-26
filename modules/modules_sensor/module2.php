<?php
require_once "./././modules/module-format.php";
$formato = new module_format();
$metrica = "humidity";
//$metrica = "temperature";
$place = "EPN";
$formato->format($metrica, $place);
?>