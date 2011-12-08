<?php

/**
 * Description of Sesion
 *
 * @author pablo
 */
class Session {

    private static $_instancia;
	public static $sessionName = "sesion";

    private function __construct() {
		session_name(self::$sessionName);
		session_start();
		//$this->_sesion_id = session_id();
		if(!isset($_SESSION['user']['id']) && isset($_COOKIE['pase']) && isset($_COOKIE['usuario']))
			$this->_recuperar();
	}

    
	/**
	 * Singleton
	 *
	 * @return Sesion
	 */
	public static function getCurrentSession() {
		if (!isset(self::$_instancia)) {
			//$className = __CLASS__;
			self::$_instancia = new self;//$className
		}
		return self::$_instancia;
	}




//	private $_modelo;
//	//private $_sesion_id;
//
//	//private $iniciada = false;
//	//private $_usuario_id, $_usuario, $_su, $_leng_id;

//	public function __destruct() {
//		session_write_close();
//	}
//
//

//
//
//
//	/**
//	*
//	* @param Usuario $usuario
//	* @return <type>
//	function nueva(Usuario $usuario) {
//		if(self::$inst != null)
//			throw new Exception("Ya existe una sesión activa.");
//		self::$inst = new self;
//		self::$inst->usuario_id = $usuario->id;
//		self::$inst->usuario = $usuario->usuario;
//		self::$inst->su = $usuario->su;
//		self::$inst->leng_id = $usuario->leng_id;
//		return self::$inst;
//	}
//	*/

	/**
	 *
	 * @param DTO_User $usuario
	 * @param bool $recordarme
	 * @return <type>
	 */
	public static function start(DTO_User $usuario, $recordarme = false) {
		//if(isset(self::$_instancia))
		//	return false;
		$sesion = self::getCurrentSession();

		$_SESSION['_sesion']['recordarme'] = (bool) $recordarme;
		$_SESSION['user'] = $usuario->sessionData();

		$pase = self::_generarPase();

		$expira = $recordarme ? time()+2592000 : 0;//60*60*24*30 / 30 días
		setcookie("usuario", $usuario->username, $expira, "/");//, ".".DOMINIO);
		setcookie("pase", $pase, $expira, "/", false, false, true);//, ".".DOMINIO);
		$sesion->_getModelo()->alta($usuario->id, $pase, $recordarme);
		return true;

		//if(self::$inst == null)
		//	throw new Exception("No se encontraron datos para ser cargados en la sesión.");
	}

	private function _getModelo() {
		if(!isset($this->_modelo))
			$this->_modelo = new DAO_UsersRemember();
		return $this->_modelo;
	}

	private function _generarPase() {
		return $pase = md5(rand().time());
	}

	private function _recuperar() {
		$dao = $this->_getModelo();//->modificacion($_COOKIE['usuario'], array($_COOKIE['pase'] => $this->_generarPase()));
		$usuarios = new DAO_Users();
		$usuario = $usuarios->getByUsername($_COOKIE['usuario']);
		if($registro = $dao->consulta($usuario->id, $_COOKIE['pase'])) {
			session_regenerate_id(true);
			$nuevo_pase = $this->_generarPase();
			$dao->modificacion($usuario->id, array($_COOKIE['pase'] => $nuevo_pase));
			$recordarme = $registro['recuperar'];
			$_SESSION['_sesion']['recordarme'] = (bool) $recordarme;
            $_SESSION['user'] = $usuario->sessionData();
			$expira = $recordarme ? time()+2592000 : 0;//60*60*24*30 / 30 días
			setcookie("usuario", $usuario->usuario, $expira, "/");//, ".".DOMINIO);
			setcookie("pase", $nuevo_pase, $expira, "/", false, false, true);//, ".".DOMINIO);
		}
	}

//	public function __set($clave, $valor) {
//		return ($_SESSION[$clave] = $valor);
//	}

	public function __get($clave) {
		return $_SESSION[$clave];
	}

//	public function __isset($clave) {
//		return isset($_SESSION[$clave]);
//	}
//
//	public function __unset($clave) {
//		unset($_SESSION[$clave]);
//	}
//
//
//	/*
//		if(!isset(self::$inst)) {
//			session_start();
//			if($_SESSION['usuario_id']) {
//				self::$inst = new self;
//				self::$inst->iniciada = true;
//				return self::$inst;
//			}
//			elseif($_COOKIE['pase'] && $_COOKIE['usuario']) {
//				try {
//					$usuario = Usuario::obtenerPorIdentificador($_COOKIE['usuario'], array('pase' => $_COOKIE['pase']));
//					self::$inst = self::nueva($usuario);
//					self::$inst->iniciar($usuarios->recordarme == 1);
//					return self::$inst;
//				}
//				catch (Exception $e) {
//					return false;
//				}
//			}
//			return false;
//			//throw new Exception("No hay sesión registrada");
//		}
//		return self::$inst;
//	}
//	*/

	function destroy() {

		if(!$_SESSION['user'])
			return false;
		$this->_getModelo()->bajaPase($_SESSION['user']['id'], $_COOKIE['pase']);
		/*foreach ($_SESSION as $var => $val) {
			$_SESSION[$var] = null;
		}*/
		session_unset();
		if(!$_SESSION['_sesion']['recordarme'])
			setcookie("pase", "", 0, '/');
		setcookie("sesion", "", 0, '/');
		if(session_destroy())
			return true;
	}

//	/**
//	 * Método de mantenimiento
//	 * Las cookies necesarias para recuperar una sesión expiran a los 30 días.
//	 */
//	public function eliminarExpiradas() {
//		$this->_getModelo()->bajaExpirado(30);
//	}
//
//	public function __clone() {
//		trigger_error('¿Clonado de '.__CLASS__.'?', E_USER_ERROR);
//	}
//
//	public function printArray() {
//		print_r($_SESSION);
//	}
}

