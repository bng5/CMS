<?php

class Html_Paginado {// implements Vista_Admin_iComponente

    public static $anteriorLabel, $siguienteLabel;
    public $ruta, $parametros, $getParam;
    private $_params, $_path;
	private $enlaces = array();

    /**
     *
     * @param int $paginas Cantidad de páginas
     * @param string $getParam Nombre del parámetro GET utilizado para indicar la página solicitada
     * @param int $max_paginas_mostrar
     */
    function __construct($paginas, $getParam = 'pagina', $max_paginas_mostrar = null) {//$ruta, $pagina, $paginas, $max_paginas_mostrar = false, $textos = array()) {

		$this->ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->parametros = $_GET;
		$this->pagina = isset($this->parametros[$getParam]) ? $this->parametros[$getParam] : 1;
		$this->paginas = $paginas;
		$this->getParam = $getParam;
		$this->max_paginas_mostrar = $max_paginas_mostrar ? $max_paginas_mostrar : $paginas;
	}

	private function _enlace($pagina, $etiqueta = false) {
		if(!$etiqueta)
			$etiqueta = $pagina;
        $this->parametros[$this->getParam] = $pagina;
		return '<a href="'.$this->ruta.'?'.http_build_query($this->parametros, '', '&amp;').'">'.$etiqueta.'</a>';
	}
	/*
	function dependenciasJs() {
		return array('otro');
	}

	function dependenciasCss() {
		return array('algo');
	}
	*/

	public function __toString() { //mostrar() {
		$ant = $this->pagina - 1;
		$pos = $this->pagina + 1;
		$this->enlaces[] = '<em>'.$this->pagina.'</em>';
		//$this->enlaces[] = '<a href="'.$this->ruta.'?'.http_build_query($this->parametros, '', '&amp;').'" class="actual">'.$this->pagina.'</a>';
		$c = 1;
		for($i = $this->max_paginas_mostrar; $i > 0; $ant--, $pos++) {
			if($ant <= 0 && $pos > $this->paginas)
				break;
			if($ant > 0) {
				array_unshift($this->enlaces, $this->_enlace($ant));
				$i--;
				$c++;
			}
			if($pos <= $this->paginas) {
				array_push($this->enlaces, $this->_enlace($pos));
				$i--;
				$c++;
			}
		}
		$anterior = '&lt; '.self::$anteriorLabel;
		$siguiente = self::$siguienteLabel.' &gt;';
		//array_unshift($this->enlaces, '<span class="antsig">'.(($this->pagina > 1) ? $this->_enlace(($this->pagina -1), $anterior) : $anterior).'</span>');
		//array_push($this->enlaces, '<span class="antsig">'.(($this->pagina < $this->paginas) ? $this->_enlace(($this->pagina + 1), $siguiente) : " ".$siguiente).'</span>');
		return '<div class="paginado"><span class="anterior'.(($this->pagina > 1) ? '">'.$this->_enlace(($this->pagina -1), $anterior) : ' inactivo">'.$anterior).'</span> '.implode(" ", $this->enlaces).' <span class="siguiente'.(($this->pagina < $this->paginas) ? '">'.$this->_enlace(($this->pagina + 1), $siguiente) : ' inactivo">'.$siguiente).'</span></div>';
		//return $retorno;
	}
}
