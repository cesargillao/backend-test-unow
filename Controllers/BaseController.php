<?php
namespace Controllers;

use Vendor\Utils\Validar;

class BaseController {

  public function responder($r, $code = 200)
	{
		http_response_code($code);
		die(json_encode($r, JSON_UNESCAPED_UNICODE));
	}

	public function request()
	{
		return json_decode(file_get_contents('php://input'));
	}

	public function validar($subject, $x)
	{
		$val = new Validar($subject, $x);
	}
}