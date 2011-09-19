<?php

/**
 *
 * @author pablo
 */

class Vista {

	public $idioma, $seccion;
	private $_error, $archivosCSS, $archivosJS, $_head;
	public function error_status($status) {
		$this->_error = $status;
		$this->mostrar();
		exit;
		//include(RUTA_CARPETA.'plantilla/'.$status.'.php');
	}

	public function mostrar() {
		global $idioma, $seccion;
		global $idiomas, $secciones, $secciones_sup;

		if(empty($idioma))
			$idioma = Publicacion_Idiomas::predeterminado();
		$incluir = $this->_error ? 's'.$this->_error : $seccion->id;
		//$incluir .= '.php';

		ob_start();
		if(!@include('../plantillas/'.$incluir.'.php')) {
			if($this->_error != 500)
				$this->error_status(500);
			else {
				echo "Error realmente FEO.";
			}
		}

		$contenido = ob_get_contents();
		ob_end_clean();

		$archivosExternos = '';
		if(is_array($this->archivosCSS) && count($this->archivosCSS)) {
			foreach($this->archivosCSS AS $k => $v) {
				$archivosExternos .= "<link href=\"/css/".$v.".css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			}
		}
		if(is_array($this->archivosJS) && count($this->archivosJS)) {
			foreach($this->archivosJS AS $k => $v) {
				$archivosExternos .= "<script type=\"text/javascript\" src=\"/js/".$v.".js\"></script>\n";
			}
		}
		$archivosExternos .= $this->_head;

		include('../plantillas/plantilla.php');
	}
}


/*
 *
 * ob_start();
if(!$error_status && $seccion->id) {
	include('../plantillas/'.$seccion->id.'.php');
}
elseif($error_status) {
	include('../plantillas/s'.$error_status.'.php');
}
else {
	include('../plantillas/s500.php');
}

$contenido = ob_get_contents();
ob_end_clean();

$archivosExternos = '';
if(count($archivosCSS)) {
	foreach($archivosCSS AS $k => $v) {
		$archivosExternos .= "<link href=\"/css/".$v.".css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
}
if(count($archivosJS)) {
	foreach($archivosJS AS $k => $v) {
		$archivosExternos .= "<script type=\"text/javascript\" src=\"/js/".$v.".js\"></script>\n";
	}
}

include('../plantillas/plantilla.php');
 */
?>