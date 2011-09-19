<?php

class Listado implements IteratorAggregate {
	//const RPP = 25;
	public static $rpp = 25;
	
	//private $_fila_actual, $_posicion;
	private $_campos = array();
	private $_stmt;
	
	function __construct($total = 0, Traversable $stmt = null, $pagina = 1, $rpp = null) {
		$this->_stmt = $stmt;
		$this->_campos['total'] = $total;
		$this->_campos['rpp'] = $rpp && is_int($rpp) ? $rpp : self::$rpp;
		$this->_campos['pagina'] = $pagina;
		$this->_campos['paginas'] = ceil($total / $this->_campos['rpp']);
		
		// $this->rpp = $rpp;
		// $this->pagina = $pagina;
	}
	
	function __get($k) {
		//if(in_array($k, $this->campos))
		//  return $this->$k;
		return $this->_campos[$k];
	}
	
	function getIterator() {
		return $this->_stmt;
	}

}
