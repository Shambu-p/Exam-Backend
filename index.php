<?php

session_start();

use \Absoft\Line\Engines\HTTP\Engine;
use Absoft\Line\FaultHandling\FaultHandler;

$_main_location = str_replace("\\", "/", __DIR__);
//date_default_timezone_set("Africa/Asmara");

include_once $_main_location."/System/autoLoad.php";

$start = new Engine($_main_location);
$start->start();

?>
