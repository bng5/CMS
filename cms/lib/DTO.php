<?php

/**
 * Data transfer object
 *
 * @author pablo
 */

abstract class DTO {

	const ESTADO_NUEVO = 1;
	const ESTADO_GUARDADO = 2;
	const ESTADO_MODIFICADO = 3;
	const ESTADO_BORRADO = 4;
	const DTO_ESTADO_NUEVO = 1;
	const DTO_ESTADO_GUARDADO = 2;
	const DTO_ESTADO_MODIFICADO = 3;
	const DTO_ESTADO_BORRADO = 4;

	//private $_validar = false;
	protected $_id;
	protected $_created_date;
	protected $_estado = self::ESTADO_GUARDADO;
	protected $_inicializado = false;
	//protected $_valores = array();

	public function __construct() {
		if(!isset($this->_id)) {
			$this->_estado = self::ESTADO_NUEVO;
            //$args = func_get_args();
            //call_user_func_array(array($this, '_nuevo'), $args);
            $this->_created_date = new DateTime();
		}
		else {
			$this->_inicializado = true;
		}
	}

	//abstract function _nuevo();
	
	/**
	 * Setter/getter para estado del DTO
	 *
	 * @param int $nuevo_estado
	 * @return int
	 */
	public function estado($nuevo_estado = null) {
		if(isset($nuevo_estado)) {
			$this->_estado = $nuevo_estado;
			if(self::ESTADO_BORRADO == $nuevo_estado)
				$this->_id = null;
		}
		return $this->_estado;
	}

	public function __get($attr) {
		$metodo = "get".ucfirst($attr);
		if(method_exists($this, $metodo))
			return $this->{$metodo}();
		return $this->_valores[$attr];
	}

	public function __set($attr, $valor) {
		$metodo = "set".ucfirst($attr);
		if(method_exists($this, $metodo)) {
			$this->$metodo($valor);
			if(self::ESTADO_GUARDADO == $this->_estado)
				$this->_estado = self::ESTADO_MODIFICADO;
		}
		elseif(array_key_exists($attr, $this->_valores)) {
			$this->_valores[$attr] = $valor;
		}
		//else
		//	throw new Exception('No existe el método '.$metodo.' para '.get_class($this));
		//return $this;
	}

	final public function setId($id) {
		if(!isset($this->_id))
			$this->_id = (int) $id;
        return $this;
	}

	final public function getId() {
		return $this->_id;
	}

    public function setCreated_date($value) {
        if(!is_a($value, 'DateTime')) {
            // This is a pretty nasty hack for PHP5 < 5.3.0
            $value = new DateTime(date(DATE_ATOM, $value));
        }
        $this->_created_date = $value;
		return $this;
    }

    public function getCreated_date() {
		return $this->_created_date;
    }
}
