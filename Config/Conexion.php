<?php
namespace Config;

use PDO;

class Conexion {

	public static function Conectar(){
		// ESTEBLECE LA CONEXIÓN A LA BASE DE DATOS		
		$BD = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
		$BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// ESTABLECE LA ZONA HORARIA EN LA CONEXIÓN DE LA BBDD
		$tz = (new \DateTime('now', new \DateTimeZone(ZONA_HORARIA)))->format('P');
		$BD->exec("SET time_zone = '$tz'");
		$BD->exec("SET lc_time_names = 'es_ES'");

		return $BD;
	}
}