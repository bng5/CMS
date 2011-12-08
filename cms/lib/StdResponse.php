<?php

/**
 * Genera un objeto stdClass
 * para ser devuelto en otros formatos
 *
 * Ej.:
 *	 $r = new Respuesta;
 *	 $r->campoError('nombre', Respuesta::CAMPO_ERR_REQUERIDO);
 *	 $r->agregarIgnorados('das');
 *	 echo json_encode($r->obtenerRespuesta())
 * 
 */
class StdResponse {

	const OK = 1;
	const OK_CREADO = 2;
	const OK_ACEPTADO = 3;
	const SIN_CAMBIOS = 4;
	const MALA_PETICION = 5;
	const PETICION_INCORRECTA = 5;
	const FALTAN_DATOS = 6;
	const DATOS_REQUERIDOS = 7;
	const CAMPOS_TIPO_NO_VALIDO = 8;
	const CAMPOS_VALORES_INCORRECTOS = 9;
	const NO_AUTORIZADO = 10;
	const NO_ENCONTRADO = 11;
	const METODO_NO_ACEPTADO = 12;
	const CONFLICTO = 13;
	const YA_NO_EXISTE = 14;
	const LARGO_REQUERIDO = 15;
	const ENTIDAD_MUY_LARGO = 16;
	const TIPO_NO_SOPORTADO = 17;
	const ERR_INTERNO = 18;

	const FIELD_REQUIRED_ERROR = 1;     const CAMPO_ERR_REQUERIDO = 1;
    const FIELD_DATATYPE_ERROR = 2; 	const CAMPO_ERR_TIPO_DATO = 2;
    const FIELD_VALUE_ERROR = 3;        const CAMPO_ERR_VALOR_INCORRECTO = 3;
    const FIELD_MINLEN_ERROR = 4;   	const CAMPO_ERR_LARGO_MINIMO = 4;
	const FIELD_MAXLEN_ERROR = 5;       const CAMPO_ERR_LARGO_MAXIMO = 5;

	private $mensajes, $excepcion, $errores_asos_mensajes;
	private $_obj;
	private $_erroresSimilares = array(1 => 0, 0, 0, 0, 0);
  
	public function __construct($exitoResp = 200, $mensajesTxt = array()) {
		
		$this->_obj = new stdClass();
		$this->mensajesTxt = $mensajesTxt + $this->MensajesTxt();
		$this->exito = false;
		$this->errores = array();
		$this->mensajes = array();
		$this->ignorados = array();
		$this->excepcion = false;

		$this->errores_asos_mensajes = array(
			1 => 7,// self::DATOS_REQUERIDOS,
			2 => 8,// self::CAMPOS_TIPO_NO_VALIDO,
			3 => 9,// self::CAMPOS_VALORES_INCORRECTOS,
			4 => 8,// self::CAMPOS_TIPO_NO_VALIDO,
			5 => 8,// self::CAMPOS_TIPO_NO_VALIDO
		);
	}


    public function __get($attr) {
        switch($attr) {
            case 'success':
                $metodo = 'getSuccess';
                break;
        }
        return $this->$metodo();
    }

    public function getSuccess() {
        return ((count($this->errores) == 0 && !$this->excepcion));
    }

    public function getResponse() {
		$obj->success = false;
		if($this->excepcion) {
			if(count($this->mensajes))
				$obj->messages = array_values($this->mensajes);
		}
		if(count($this->mensajes))
			$obj->messages = (object) array_values($this->mensajes);
		if(count($this->errores) == 0) {
			if(!$this->excepcion)
				$obj->success = true;
		}
		else {
			$obj->errors = $this->errores;
		}
		if(count($this->ignorados))
			$obj->ignored = $this->ignorados;//implode(", ", $this->ignorados);
		return $obj;
    }

    public function __toString() {
		/* TODO */
		return "Éxito: ".($this->exito ? 'si' : 'no')."<br />\n";
	}

	public function MensajesTxt() {
		return array(
			'errores' => array(
				1 => "Campo requerido", // El campo no puede estar vacio || bad request
				2 => "Tipo no válido", // Se esperaba otro tipo de dato || bad request
				3 => "Valor incorrecto", // Se esperaba otro valor || bad request
				4 => "El campo no alcanza la cantidad de caracteres mínima",// || bad request
				5 => "El campo excede la cantidad de caracteres máxima",// || bad request
				//6 => "Campo ignorado", // No se esperaba recibir este campo
			   ),
			'mensajes' => array(//mensaje, status
				self::OK => array("Petición aceptada.", 200),
				self::OK_CREADO => array("Creado.", 201),
				self::OK_ACEPTADO => array("Petición aceptada.", 202),
				self::SIN_CAMBIOS => array("Sin cambios", 304),
				self::MALA_PETICION => array("La petición no pudo ser interpretada.", 400),
				self::FALTAN_DATOS => array("Faltan datos.", 400),
				self::DATOS_REQUERIDOS => array("Debe completar los campos requeridos.", 400),
				self::CAMPOS_TIPO_NO_VALIDO => array("Existen datos con tipo o formato incorrecto.", 400),
				self::CAMPOS_VALORES_INCORRECTOS => array("Existen datos incorrectos.", 400),
				self::NO_AUTORIZADO => array("Usted no tiene permisos para acceder a este documento.", 401),
				self::NO_ENCONTRADO => array("No se encontró el recurso", 404),
				self::METODO_NO_ACEPTADO => array("Método HTTP no aceptado", 405),
				self::CONFLICTO => array("",409),
				self::YA_NO_EXISTE => array("",410),
				self::LARGO_REQUERIDO => array("",411),
				self::ENTIDAD_MUY_LARGO => array("",413),
				self::TIPO_NO_SOPORTADO => array("",415),
				self::ERR_INTERNO => array("Error interno del servidor", 500),
			   )
			);//$mensajes;
	}

	/*public function erroresSimilaresTotal($codigo) {
		return $this->_erroresSimilares[$codigo];
	}
	*/

    /**
     *
     * @param string $campo
     * @param int $codigo
     * @param string $descripcion
     * @return StdResponse
     */
	public function addFieldError($campo, $codigo, $descripcion = false) {
		$this->_erroresSimilares[$codigo]++;
		if(!$descripcion)
			$descripcion = $this->mensajesTxt['errores'][$codigo];
		$this->errores[$campo] = (object) array('cod' => $codigo, 'description' => $descripcion);
		if($this->errores_asos_mensajes[$codigo])
			$this->mensajes[$codigo] = (object) array('cod' => $this->errores_asos_mensajes[$codigo], 'description' => $this->mensajesTxt['mensajes'][$this->errores_asos_mensajes[$codigo]][0]);
        return $this;
	}

    /**
     *
     * @param int $codigo
     * @param string $mensaje
     */
	public function setException($codigo, $mensaje = false) {
		$this->excepcion = $this->mensajesTxt['mensajes'][$codigo][1];
		$this->mensajes[$codigo] = (object) array('cod' => $codigo, 'description' => ($mensaje ? $mensaje : $this->mensajesTxt['mensajes'][$codigo][0]));
   }

    /**
     *
     * 
     */
    public function addIgnored() {
        $i = 0;
		foreach(func_get_args() AS $k) {
            $i++;
			if(is_array($k)) {
				$this->ignorados = array_merge($this->ignorados, $k);
            }
			else
				array_push($this->ignorados, $k);
		}
	}

    /**
     *
     * @deprecated
	 * sin uso todavia
     */
	public function setHeader($status) {
		header("X-Error: true", true, $status);
	}

}

?>