<?php

/**
 * Description of HTML
 *
 * @author pablo
 */
class VistaAdmin_HTML {

	public function __construct($html) {
		$this->html = $html;
	}

	public function mostrar() {
		echo $this->html;
	}

}

?>