<?php

// $db = BaseDatos::Conectar();
class DB extends PDO {

	private static $_instancia;
	public function __construct() {
		parent::__construct('mysql:dbname='.MYSQL_DB.';host=localhost', MYSQL_USUARIO, MYSQL_CLAVE);
		$this->exec('SET CHARACTER SET utf8');
	}

	public static function Conectar() {
		if(!isset(self::$_instancia))
			self::$_instancia = new self;
		return self::$_instancia;
	}

	/**
	* Similar a Conectar
	* en nuevas versiones se llama instancia
	*
	* @return DB
	*/
	public static function instancia() {
		if(!isset(self::$_instancia))
			self::$_instancia = new self;
		return self::$_instancia;
	}
}

?>