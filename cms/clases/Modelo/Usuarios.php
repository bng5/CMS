<?php

class Modelo_Usuarios
 {
  //id 	usuario 	estado_id 	nombre_mostrar 	clave 	email 	aut 	creado 	creado_por 	pase 	admin 	su 	leng_id
  static function getPorId($id)
   {
	$DB = DB::Conectar();
	return $DB->query("SELECT * FROM usuarios WHERE id = ".$id." LIMIT 1")->fetchObject();
   }
 }

?>