<?php
namespace Models;

class Medico {

	private $DB;

	public function __construct($DB){
		$this->DB = $DB;
	}

	/**
	 * @return \PDOStatement|false
	 */
	public function find($idMedico)
	{
		$Q = "SELECT * FROM medicos WHERE id_user = :id";
		$Q = $this->DB->prepare($Q);
		$Q->bindParam(':id', $idMedico);
		$Q->execute();
		return $Q;
	}
}