<?php


class Validacion {

	const REMITENTE = 1;
	const ASUNTO = 2;

	public static function esEmail($str) {
		return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/', $str);
	}
}

?>