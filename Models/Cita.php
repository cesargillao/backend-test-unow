<?php
namespace Models;

class Cita {

	private $DB;

	public function __construct($DB){
		$this->DB = $DB;
	}

	/**
	 * @return \PDOStatement|false
	 */
	public function get($filtro)
	{
		$Q = "SELECT *
					FROM citas
					WHERE TRUE
					{$filtro->fecha}";
		$Q = $this->DB->prepare($Q);
		$Q->execute();
		return $Q;
	}

	/**
	 * @return \PDOStatement|false
	 */
	public function find($idCita)
	{
		$Q = "SELECT * FROM citas WHERE id = :id";
		$Q = $this->DB->prepare($Q);
		$Q->bindParam(':id', $idCita);
		$Q->execute();
		return $Q;
	}

	/**
	 * @return \PDOStatement|false
	 */
	public function save($cita)
	{
		$Q = "INSERT INTO citas SET
					id_doctor = :id_doctor,
					id_patient = :id_patient,
					appointment_date = :appointment_date,
					created_at = NOW(),
					status = 'pendiente'";
		$Q = $this->DB->prepare($Q);
		$Q->bindParam('id_doctor', $cita->id_doctor);
		$Q->bindParam('id_patient', $cita->id_patient);
		$Q->bindParam('appointment_date', $cita->appointment_date);
		$Q->execute();
		return $Q;
	}

	/**
	 * @return \PDOStatement|false
	 */
	public function update($cita)
	{
		$Q = "UPDATE citas SET
					id_doctor = :id_doctor,
					id_patient = :id_patient,
					appointment_date = :appointment_date,
					status = :status
					WHERE id = :id";
		$Q = $this->DB->prepare($Q);
		$Q->bindParam('id_doctor', $cita->id_doctor);
		$Q->bindParam('id_patient', $cita->id_patient);
		$Q->bindParam('appointment_date', $cita->appointment_date);
		$Q->bindParam('status', $cita->status);
		$Q->bindParam('id', $cita->id);
		$Q->execute();
		return $Q;
	}
}