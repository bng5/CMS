<?php

// $db = BaseDatos::Conectar();
class BaseDatos extends mysqli
 {
  private static $instancia = null;
  private function __construct()
   {
	parent::__construct("localhost", MYSQL_USUARIO, MYSQL_CLAVE, MYSQL_DB);
	$this->set_charset("utf8");
   }

  public static function Conectar()
   {
	if(self::$instancia == null)
	 {
	  //$instancia = new mysqli("localhost", MYSQL_USUARIO, MYSQL_CLAVE, MYSQL_DB);
	  self::$instancia = new self;
	 }
    return self::$instancia;
   }
  function real_escape_array(&$item, $key)
   {
	if(is_string($item))
	  $item = $this->real_escape_string($item);
   }
 }

?>