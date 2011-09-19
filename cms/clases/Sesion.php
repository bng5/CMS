<?php

// implements ArrayAccess// implements Serializable
class Sesion {

	private static $_instancia;
	//private $_sesion_id;

	//private $iniciada = false;
	private $usuario_id, $usuario, $su, $leng_id;

	private function __construct() {
		session_name("sesion");
		session_start();
		//$this->_sesion_id = session_id();
	}

	public function __destruct() {
		session_write_close();
	}


	public static function instancia() {
		if (!isset(self::$_instancia)) {
			//$className = __CLASS__;
			self::$_instancia = new self;//$className
			if(!isset($_SESSION['usuario_id']) && isset($_COOKIE['pase']) && isset($_COOKIE['usuario']))
				$this->_recuperar();
		}
		return self::$_instancia;
	}



	/**
	*
	* @param Usuario $usuario
	* @return <type>
	function nueva(Usuario $usuario) {
		if(self::$inst != null)
			throw new Exception("Ya existe una sesión activa.");
		self::$inst = new self;
		self::$inst->usuario_id = $usuario->id;
		self::$inst->usuario = $usuario->usuario;
		self::$inst->su = $usuario->su;
		self::$inst->leng_id = $usuario->leng_id;
		return self::$inst;
	}
	*/
	
	/**
	 *
	 * @param Usuario $usuario
	 * @param boolean $recordarme 
	 */
	function iniciar(Usuario $usuario, $recordarme = false) {

		if(isset(self::$_instancia))
			return false;
		self::$_instancia = new self;
		$_SESSION['recordarme'] = $recordarme;

		$_SESSION['usuario'] = $usuario->usuario;
		$_SESSION['usuario_id'] = $usuario->id;
		$_SESSION['su'] = $usuario->su;
		$_SESSION['leng_id'] = $usuario->leng_id;
		
		$pase = md5(rand().time());

		$expira = $recordarme ? time()+2592000 : 0;//60*60*24*30
		setcookie("usuario", $usuario->usuario, $expira, "/");//, ".".DOMINIO);
		setcookie("pase", $pase, $expira, "/", false, false, true);//, ".".DOMINIO);
		
		$db = DB::instancia();
		$db->exec("INSERT INTO usuarios_pases (usuario_id, pase, recordarme) VALUES ({$usuario->id}, '{$pase}', ".intval($recordarme).")");
		return true;
		
		//if(self::$inst == null)
		//	throw new Exception("No se encontraron datos para ser cargados en la sesión.");
	}

	
	/*function __set($clave, $valor) {
		if($this->iniciada)
			$_SESSION[$clave] = $valor;
		else
			$this->$clave = $valor;
	}

	function __get($clave) {
		return $this->iniciada ? $_SESSION[$clave] : $this->$clave;
	}*/

	public function __set($clave, $valor) {
		return ($_SESSION[$clave] = $valor);
	}

	public function __get($clave) {
		return $_SESSION[$clave];
	}

	public function __unset($clave) {
		unset($_SESSION[$clave]);
	}


	/*
		if(!isset(self::$inst)) {
			session_start();
			if($_SESSION['usuario_id']) {
				self::$inst = new self;
				self::$inst->iniciada = true;
				return self::$inst;
			}
			elseif($_COOKIE['pase'] && $_COOKIE['usuario']) {
				try {
					$usuario = Usuario::obtenerPorIdentificador($_COOKIE['usuario'], array('pase' => $_COOKIE['pase']));
					self::$inst = self::nueva($usuario);
					self::$inst->iniciar($usuarios->recordarme == 1);
					return self::$inst;
				}
				catch (Exception $e) {
					return false;
				}
			}
			return false;
			//throw new Exception("No hay sesión registrada");
		}
		return self::$inst;
	}
	*/

	function finalizar() {
		if(!$_SESSION['usuario_id'])
			return false;
		$db = DB::instancia();
		$db->exec("DELETE FROM usuarios_pases WHERE `pase` = '{$_COOKIE['pase']}' AND usuario_id = {$_SESSION['usuario_id']}");
		/*foreach ($_SESSION as $var => $val) {
			$_SESSION[$var] = null;
		}*/
		session_unset();
		if(!$_SESSION['recordarme'])
			setcookie("pase", "", 0, '/');
		setcookie("sesion", "", 0, '/');
		if(session_destroy())
			return true;
	}

	public function __clone() {
		trigger_error('Clone is not allowed for '.__CLASS__,E_USER_ERROR);
	}

}

?>