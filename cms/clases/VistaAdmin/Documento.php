<?php

/**
 * 
 *
 * @author pablo
 */

class VistaAdmin_Documento extends VistaAdmin {

	private $_menu = true, $_seccion, $_head = '';

	public function __construct(Seccion &$seccion = null) {
		if($seccion) {
			$this->_seccion = $seccion;
		}
	}

	public function mostrar() {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>'.$this->_seccion->titulo.' - '.SITIO_TITULO.'</title>
'.$this->_head.'
';
		$this->incluirCSS();
		$this->incluirJS();

		$seccion_id = $this->_seccion->id;
		include('./vistas/iaencab.php');
		//echo $this->_mostrarMenu();
		if(count($this->_children)) {
			foreach($this->_children AS $child)
				echo $child->mostrar();
		}
		//else {
		//	echo '<tr><td>No existe ningún campo. <a href="/configuracion?seccion=${seccion_id}">Configuración de items</a></td></tr>';
		//}
		echo '
<!-- pie -->
  <div class="separador"></div>
 </div>
</div>
</body>
</html>';
	}

	private function _mostrarMenu() {
		if($this->_menu == false)
			return false;
		$retorno = '
  <div id="menu"';
		$incluir = "iacache/menu".md5($_SESSION['admin_secciones']).".php";
		if(!file_exists($incluir))
			$incluir = "templates/iamenu.php";
		ob_start();
		$seccion_id = $this->_seccion->id;
		include($incluir);
		$retorno .= ob_get_contents();
		ob_end_clean();
		return $retorno.'
  ></div>';
	}


	public function head($html) {
		$this->_head += $html;
	}

}

?>