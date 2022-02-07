<?php
namespace Config;

class Rutas {
	
	public $ruta;
	public $controlador;
	public $metodo;
	public $error = false;

	function __construct($ruta){
		$this->ruta = strtolower($ruta);
		$this->verificar();
	}
	
	private function verificar(){
		$r = $this->listado();
		if (isset($r[$this->ruta])) {
			$this->controlador = $r[$this->ruta][0];
			$this->metodo = $r[$this->ruta][1];
		} else {
			$this->error = true;
		}
	}

	private function listado()
	{
		// $r['Ruta'] = ['Controlador', 'Metodo'];
		switch ($_SERVER['REQUEST_METHOD']) {
			// PETICIONES MEDIANTE EL MÉTODO GET
			case 'GET':

				// Obtener citas
				$r['cita'] =	['Cita', 'get'];

				break;

			// PETICIONES MEDIANTE EL MÉTODO POST
			case 'POST':

				// Guardar cita
				$r['cita'] = ['Cita', 'save'];
				// Confirmar cita
				$r['cita/confirmar'] = ['Cita', 'confirm'];
				// Rechazar cita
				$r['cita/rechazar'] = ['Cita', 'reject'];

				break;
			
			default:
				return false;
		}
		return $r;
	}
}