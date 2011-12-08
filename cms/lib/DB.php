<?php

class DB extends PDO {

	private static $_instancia;
	private function __construct() {
        $connect_info = CMS::getInstance()->config->DB;
		parent::__construct('mysql:dbname='.$connect_info['DB'].';host='.$connect_info['HOST'], $connect_info['USER'], $connect_info['PASS']);
		$this->exec('SET CHARACTER SET utf8');
	}

	/**
	* Singleton
	*
	* @return DB
	*/
	public static function instance() {
		if(!isset(self::$_instancia))
			self::$_instancia = new self();
		return self::$_instancia;
	}
}
