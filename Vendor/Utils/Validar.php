<?php
namespace Vendor\Utils;

class Validar {

	public $request = [];
	public $subject;

	function __construct($request, $x)
	{
		$this->r = is_array($request) ? $request : json_decode(json_encode($request), true);
		// Se iteran los items a validar
		foreach ($x as $itemk => $itemv) {
			$this->s = $itemk;
			// Se separan e iteran los validadores
			$validadores = explode('|', $itemv);
			// Se verifica si es nullable
			if (in_array('nullable', $validadores)) {
				// Se verifica si el campo está vacío, si lo retá no se realizan las demás validaciones
				$validar = !$this->nullable(); // Si devuelve true (está vacío) se invierte el valor para que no se valide el campo.
				unset($validadores[array_search('nullable', $validadores)]);
			} else {
				$validar = true;
			}
			if ($validar) {
				foreach ($validadores as $fieldk => $fieldv) {
					// Se separan los parámetros de los validadores
					$validador = explode(':', $fieldv);
					if (count($validador) > 1) $this->{$validador[0]}($validador[1]);
					else $this->{$validador[0]}();
				}
			}
		}
	}

  public function fail($r)
	{
		http_response_code(422);
		echo json_encode($r, JSON_UNESCAPED_UNICODE);
		die();
	}

	/** Filled */
	public function filled()
	{
		# Subject no debe estar vacío cuando está presente.
		if (empty($this->r[$this->s])) $this->fail("$this->s: no debe estar vacío");
	}

	/** Greater Than */
	public function gt($x)
	{
		# Subject debe ser mayor que el campo dado.
		# Los dos campos deben ser del mismo tipo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la regla size.
		# gt:value

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) <= $x) $this->fail("$this->s: debe ser mayor a $x");
				break;

			case is_int($this->r[$this->s]):
				if ($this->r[$this->s] <= $x) $this->fail("$this->s: debe ser mayor a $x");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) <= $x) $this->fail("$this->s: debe ser mayor a $x");
				break;
			
			default:
				$this->fail("$this->s: gt - datatype no definido");
				break;
		}
	}

	/** Greater Than Or Equal */
	public function gte($x)
	{
		# Subject debe ser mayor o igual que el campo dado.
		# Los dos campos deben ser del mismo tipo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la sizeregla.
		# gte:value

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) < $x) $this->fail("$this->s: debe ser mayor o igual a $x");
				break;

			case is_int($this->r[$this->s]):
				if ($this->r[$this->s] < $x) $this->fail("$this->s: debe ser mayor o igual a $x");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) < $x) $this->fail("$this->s: debe ser mayor o igual a $x");
				break;
			
			default:
				$this->fail("$this->s: gte - datatype no definido");
				break;
		}
	}

	/** Integer */
	public function integer()
	{
		# Subject debe ser un número entero.
		if (!is_int($this->r[$this->s])) $this->fail("$this->s: no es un número entero");
	}
	
	/** Less Than */
	public function lt($x)
	{
		# Subject debe ser menor que el campo dado.
		# Los dos campos deben ser del mismo tipo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la regla size.
		# lt:field

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) >= $x) $this->fail("$this->s: debe ser menor a $x");
				break;

			case is_int($this->r[$this->s]):
				if ($this->r[$this->s] >= $x) $this->fail("$this->s: debe ser menor a $x");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) >= $x) $this->fail("$this->s: debe ser menor a $x");
				break;
			
			default:
				$this->fail("$this->s: lt - datatype no definido");
				break;
		}
	}
	
	/** Less Than Or Equal */
	public function lte($x)
	{
		# Subject debe ser menor o igual al campo dado.
		# Los dos campos deben ser del mismo tipo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la regla size.
		# lte:field

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) > $x) $this->fail("$this->s: debe ser menor o igual a $x");
				break;

			case is_int($this->r[$this->s]):
				if ($this->r[$this->s] > $x) $this->fail("$this->s: debe ser menor o igual a $x");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) > $x) $this->fail("$this->s: debe ser menor o igual a $x");
				break;
			
			default:
				$this->fail("$this->s: lte - datatype no definido");
				break;
		}
	}
	
	/** Max */
	public function max($x)
	{
		# Subject debe ser menor o igual a un valor máximo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la regla size.
		# max:value
		# Nota: Los caracteres especiales los cuenta x2: var_dump("Título") -> string(7) "Título"

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) > $x) $this->fail("$this->s: supera el máximo permitido");
				break;

			case is_numeric($this->r[$this->s]):
				if ($this->r[$this->s] > $x) $this->fail("$this->s: supera el máximo permitido");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) > $x) $this->fail("$this->s: supera el máximo permitido");
				break;
			
			default:
				$this->fail("$this->s: max - datatype no definido");
				break;
		}
	}
	
	/** Min */
	public function min($x)
	{
		# Subject debe tener un valor mínimo.
		# Las cadenas, números, matrices y archivos se evalúan utilizando las mismas convenciones que la regla size.
		# min:value

		switch (true) {
			case is_string($this->r[$this->s]):
				if (strlen($this->r[$this->s]) < $x) $this->fail("$this->s: no supera el mínimo permitido");
				break;

			case is_numeric($this->r[$this->s]):
				if ($this->r[$this->s] < $x) $this->fail("$this->s: no supera el mínimo permitido");
				break;

			case is_countable($this->r[$this->s]):
				if (count($this->r[$this->s]) < $x) $this->fail("$this->s: no supera el mínimo permitido");
				break;
			
			default:
				$this->fail("$this->s: min - datatype no definido");
				break;
		}
	}
	
	/** Multiple Of */
	public function multiple_of($x)
	{
		# Subject debe ser un múltiplo de X.
		# multiple_of:value
		if (($this->r[$this->s] % $x) !== 0) $this->fail("$this->s: no es múltiplo de $x");
	}

	/** Nullable */
	public function nullable()
	{
		# Subject puede ser null.
		return empty($this->r[$this->s]);
	}
	
	/** Numeric */
	public function numeric()
	{
		# Subject debe ser numérico.
		if (!is_numeric($this->r[$this->s])) $this->fail("$this->s: no es numérico");
	}

	/** Present */
	public function present()
	{
		# Subject debe estar presente en los datos de entrada pero puede estar vacío.
		if (!array_key_exists($this->s, $this->r)) $this->fail("$this->s: no xiste");
	}
	
	/** Required */
	public function required()
	{
		/*
			Subject debe estar presente en los datos de entrada y no estar vacío.
			Un campo se considera "vacío" si se cumple una de las siguientes condiciones:
			* El valor es null.
			* El valor es una cadena vacía.
			* El valor es una matriz u objeto contable vacío.
		*/
		if (!array_key_exists($this->s, $this->r)) $this->fail("$this->s: es obligatorio");
		if ($this->r[$this->s] === null) $this->fail("$this->s: es obligatorio");
		if ($this->r[$this->s] === '') $this->fail("$this->s: es obligatorio");
		if (is_countable($this->r[$this->s]) && count($this->r[$this->s]) === 0) $this->fail("$this->s: es obligatorio");
	}

	/** Same */
	public function same($x)
	{
		# El campo dado debe coincidir con el campo bajo validación.
		# same:field
		if ($this->r[$this->s] === $x) $this->fail("$this->s: no es igual a $x");
	}

	/** String */
	public function string()
	{
		# Subject debe ser una cadena. Si desea permitir que el campo también lo esté null, debe asignar la nullableregla al campo.
		if (!is_string($this->r[$this->s])) $this->fail("$this->s: no es una cadena de texto");
	}
}