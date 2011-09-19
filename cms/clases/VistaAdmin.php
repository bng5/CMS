<?php

/**
 * Description of VistaAdmin
 *
 * @author pablo
 */

abstract class VistaAdmin {

	protected $_children = array();
	protected $_incCss = array('ia' => 1);//, 'calendario' => 1, 'paleta' => 1);
	protected $_incJs = array('ia' => 1);//, 'calendar' => 1, 'calendar_es-uy' => 1, 'calendar-setup' => 1, 'paleta' => 1);
	
	public function agregarComponente(VistaAdmin &$child) {
		$child->parent = $this;
		array_push($this->_children, $child);
		return $child;
	}

	/*public function __set($attr, $valor) {
		if($attr == 'html')
			array_push($this->_children, new VistaAdmin_HTML($valor));
	}*/

	protected function incluirCSS() {
		if(count($this->_incCss)) {
			foreach($this->_incCss AS $css => $no)
				echo "\n <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/".$css.".css\" />";
		}
	}

	protected function incluirJS() {
		if(count($this->_incJs)) {
			foreach($this->_incJs AS $js => $no)
				echo "\n <script type=\"text/javascript\" src=\"/js/".$js.".js\" charset=\"utf-8\"></script>";
		}
	}

	public function agregarCSS() {
		foreach(func_get_args() AS $css) {
			$this->_incCss[$css] = 1;
		}
	}

	public function agregarJS() {
		foreach(func_get_args() AS $js) {
			$this->_incJs[$js] = 1;
		}
	}

	public function html($html) {
		array_push($this->_children, new VistaAdmin_HTML($html));
	}

	public function __toString() {
		return get_class($this);
		//return __CLASS__;
	}

	abstract function mostrar();
}

?>