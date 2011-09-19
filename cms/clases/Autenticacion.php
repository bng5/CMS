<?php

/**
 * Description of Autenticacion
 *
 * @author pablo
 */

class Autenticacion {

	const REALM = "Acceso de usuarios";
	private $_metodos = array();

	/**
	 *
	 * @param iAutenticacion $instancia 
	 */
	public function agregarMetodo(iAutenticacion $instancia) {
		$this->_metodos[$instancia->clase()] = $instancia;
	}

	/**
	 * Este método recorre los diferentes _metodos agregados
	public function autenticar() {
		$autenticado = false;
		foreach($this->_metodos AS $m) {
			if($autenticado = $m->autenticar())
				break;
		}
		if(!$autenticado) {
			header('HTTP/1.1 401 Unauthorized');
			foreach($this->_metodos AS $m) {
				$m->solicitar_autenticacion();
			}
		}
	}
	*/



	/**
	 *
	 * @param Usuario $usuario
	 * @param string $clave
	 * @return boolean
	 */
	public function comprobarClave(Usuario $usuario, $clave) {
		return ($usuario->clave == sha1($clave));
		//return ($usuario->clave == $usuario->usuario.':'.self::REALM.':'.$clave);
	}

}

?>