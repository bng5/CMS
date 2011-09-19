<?php

/**
 * Clase concreta de Autenticación para CMS
 *
 * @author pablo
 */

class CMSAutenticacion extends Autenticacion {
	
	public function  __construct() {
		$this->agregarMetodo(new Autenticacion_Sesion());
	}
}

?>