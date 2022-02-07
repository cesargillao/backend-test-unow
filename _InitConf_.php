<?php

// LOGS
// error_reporting(0);					#  EVITAR MOSTRAR ERRORES
error_reporting(E_ALL);					#  MUESTRA TODOS LOS TIPOS DE ERRORES
ini_set('display_errors', '1');	#  PERMITIR MOSTRAR LOS ERRORES

define('DB_HOST', 'localhost');
define('DB_USER', 'id15878681_unow_user');
define('DB_PASS', '+_0JEqWC%\dk*+0=');
define('DB_NAME', 'id15878681_unow_test');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json; charset=utf-8');

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('HTTP/1.1 200 OK');
	exit();
}

// TIEMPO
define('ZONA_HORARIA', 'America/Caracas');
date_default_timezone_set(ZONA_HORARIA);
define('HOY', date('Y-m-d H:i:s'));
setlocale(LC_TIME, 'es_ES', 'esp_esp');