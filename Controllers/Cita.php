<?php
namespace Controllers;

use Config\Conexion;
use Models\Cita as CitaModel;
use Models\Medico as MedicoModel;
use PDO;

class Cita extends BaseController {

	private $DB;
	private $CitaModel;
	private $MedicoModel;

	function __construct()
	{
		$this->DB = new Conexion();
		$this->DB = $this->DB::Conectar();
		$this->CitaModel = new CitaModel($this->DB);
		$this->MedicoModel = new MedicoModel($this->DB);
	}

	function get()
	{
		// Se estable el filtro por defecto
		$filtros = ['fecha' => null];

		// Validar el filtro por fecha
		if (isset($_GET['fecha']) && !empty($_GET['fecha'])) {
			// validar estructura de la fecha
			$fecha = date('Y-m-d', strtotime($_GET['fecha']));

			// Establecer el filtro correspondiente en SQL
			$filtros['fecha'] = "AND appointment_date LIKE '{$fecha}%'";
		}

		try {
			// Obtener las citas desde la BD
			$citas = $this->CitaModel->get((object) $filtros)->fetchAll(PDO::FETCH_OBJ);

			// Formatear resultado
			foreach ($citas as $c) {
				$c->id = (int) $c->id;
				$c->id_doctor = (int) $c->id_doctor;
				$c->id_patient = (int) $c->id_patient;
			}

			// Retorna el resultado
			$this->responder([
				'result' => true,
				'data' => $citas,
			]);

		} catch (\PDOException $e) {
			// Retornar el mensaje de error
			$this->responder([
				'result' => false,
				'message' => 'Ocurrió un error al intentar obtener las citas',
				'errorDatails' => $e,
			]);
		}

	}

	function save()
	{
		// Validar los datos recibidos
		$this->validar($this->request(), [
			'id_doctor' => 'required|numeric',
			'appointment_date' => 'required|min:16|max:19',
		]);

		// Una vez pasa las validaciones se obtiene el contenido de la solicitud
		$cita = $this->request();

		// Validar la fecha
		$cita->appointment_date = date('Y-m-d H:i:00', strtotime($cita->appointment_date));

			// Se establece el ID del paciente (DEBERÍA SER EN BASE A LA SESIÓN)
			$cita->id_patient = 2;

		// Validar existencia del médico
		$doctor = $this->MedicoModel->find($cita->id_doctor)->fetch(PDO::FETCH_OBJ);
		if (!$doctor) $this->responder(['result' => false, 'message' => 'No existe el médico']);

		try {

			// Guardar la cita
			$this->CitaModel->save($cita);

			// Retorna el mensaje de éxito
			$this->responder([
				'result' => true,
				'message' => 'Cita registrada con éxito',
			]);

		} catch (\PDOException $e) {
			// Retornar el mensaje de error
			$this->responder([
				'result' => false,
				'message' => 'Ocurrió un error al intentar registrar la cita',
				'errorDatails' => $e,
			]);
		}
	}

	function confirm()
	{
		// Validar si se recibió el ID
		$this->validar($this->request(), ['id' => 'required|numeric']);

		// Una vez pasa las validaciones se obtiene el contenido de la solicitud
		$req = $this->request();

		try {
			// Validar si existe la cita
			$cita = $this->CitaModel->find($req->id)->fetch(PDO::FETCH_OBJ);
			if (!$cita) $this->responder(['result' => false, 'message' => 'No existe la cita']);

			// Realizar el cambio
			$cita->status = 'confirmada';

			// Guardar los cambios
			$this->CitaModel->update($cita);

			// Retorna el mensaje de éxito
			$this->responder([
				'result' => true,
				'message' => 'Cita confirmada con éxito',
			]);

		} catch (\PDOException $e) {
			// Retornar el mensaje de error
			$this->responder([
				'result' => false,
				'message' => 'Ocurrió un error al intentar confirmar la cita',
				'errorDatails' => $e,
			]);
		}

	}

	function reject()
	{
		// Validar si se recibió el ID
		$this->validar($this->request(), ['id' => 'required|numeric']);

		// Una vez pasa las validaciones se obtiene el contenido de la solicitud
		$req = $this->request();

		try {
			// Validar si existe la cita
			$cita = $this->CitaModel->find($req->id)->fetch(PDO::FETCH_OBJ);
			if (!$cita) $this->responder(['result' => false, 'message' => 'No existe la cita']);

			// Realizar el cambio
			$cita->status = 'rechazada';

			// Guardar los cambios
			$this->CitaModel->update($cita);

			// Retorna el mensaje de éxito
			$this->responder([
				'result' => true,
				'message' => 'Cita rechazada con éxito',
			]);

		} catch (\PDOException $e) {
			// Retornar el mensaje de error
			$this->responder([
				'result' => false,
				'message' => 'Ocurrió un error al intentar confirmar la cita',
				'errorDatails' => $e,
			]);
		}

	}
}