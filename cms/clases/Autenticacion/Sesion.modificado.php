<?php

/**
 *
 * @author pablo
 */
class Autenticacion_Sesion implements iAutenticacion {

	function clase() {
		return "sesion";
	}

	/**
	 * Se asume que los datos llegan por POST
	 *		usuario
	 *		clave
	 *		recordarme
	 * 
	 * @return boolean
	 */
	public function autenticar() {
		//$usuario = $_POST['usuario'];
		//$_POST['clave'];
		$usuario = Usuarios::obtenerPorUsuario($_POST['usuario']);
var_dump($usuario);
//		return array($usuario->usuario, crypt($_POST['usuario'].':'.Autenticacion::REALM.':'.$_POST['clave']));
//		return array($usuario->usuario, crypt($value.$this->_created_date->format('U')));
	}

	function solicitar_autenticacion() {

	}

}

?>