<?php
namespace Config;

class Enrutador {

	public static function run(Request $request){
		if (!$request->getError()) {
			$controlador = $request->getControlador();
			$metodo = $request->getMetodo();
			$ruta_c = str_replace('\\', '/', "Controllers/$controlador.php");
			if (is_readable($ruta_c)) {
				$micont = "Controllers\\$controlador";
				$controller = new $micont;										//	Instancia la clase
				call_user_func(array($controller, $metodo));	//	Accede al método
			} else {
				Enrutador::error(404, 'El controlador no existe');
			}
		} else {
			Enrutador::error(404, 'La ruta no existe');
		}
	}

	private static function error($status, $r){
		http_response_code($status);
		die(json_encode($r, JSON_UNESCAPED_UNICODE));
	}
}