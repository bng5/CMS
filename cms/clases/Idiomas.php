<?php

/**
 *
 * 23/04/10 05:35 PM
 * 
 *
 */


class Idiomas
 {

  static function getPorCodigo($leng_cod)
   {
	$DB = DB::Conectar();
	$cons = $DB->query("SELECT `id`, `codigo`, dir, nombre_nativo FROM lenguajes WHERE codigo = '".$leng_cod."' OR `leng_poromision` = 1 ORDER BY `leng_poromision` ASC LIMIT 2");
	$cons->setFetchMode(DB::FETCH_OBJ);
	return $cons->fetch();
   }
   
  static function id_desde_codigo(&$leng_cod)
   {
	$mysqli = BaseDatos::Conectar();
	$cons = $mysqli->query("SELECT `id`, `codigo` FROM lenguajes WHERE codigo = '".$leng_cod."' OR `leng_poromision` = 1 ORDER BY `leng_poromision` ASC LIMIT 2");
	$fila = $cons->fetch_row();
	$leng_cod = $fila[1];
	return $fila[0];
   }

  function Listado($leng = null, $params = array()) { //'superior_id' => 0
	  if(!$leng_id = intval($leng)) {
		  $leng = "(SELECT id FROM lenguajes WHERE codigo = '".$leng."')";
	  }
	  if(count($params)) {
		  $bsq = array();
		  foreach($params AS $k => $v)
			  $bsq[] = "`${k}` = ${v}";
		  $bsq_sql = 'WHERE '.implode(" AND ", $bsq);
	  }
	  $db = DB::instancia();
	  $consulta = $db->query("SELECT l.id, l.codigo, l.superior, l.dir, l.leng_poromision AS poromision, l.estado, l.nombre_nativo, ln.leng_id, ln.nombre FROM `lenguajes` l LEFT JOIN lenguajes_nombres ln ON l.id = ln.id AND ln.leng_id = ".$leng_id." ".$bsq_sql." ORDER BY leng_poromision DESC, codigo");// ORDER BY ".$orden);
	  $consulta->setFetchMode(DB::FETCH_CLASS, __CLASS__);
	  return new Listado($total, $consulta, $pagina, $rpp);
	  //return Listado::InstanciaSQL();
   }

   function getArray($params = array()) { //'superior_id' => 0
	  if(count($params)) {
		  $bsq = array();
		  foreach($params AS $k => $v)
			  $bsq[] = "`${k}` = ${v}";
		  $bsq_sql = 'WHERE '.implode(" AND ", $bsq);
	  }
	  $db = DB::instancia();
	  return $consulta = $db->query("SELECT * FROM `lenguajes` ".$bsq_sql." ORDER BY leng_poromision DESC, codigo")->fetchAll(PDO::FETCH_ASSOC);// ORDER BY ".$orden);
	  //return $consulta->setFetchMode(DB::FETCH_CLASS, __CLASS__)->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
	  //return new Listado($total, $consulta, $pagina, $rpp);
	  //return Listado::InstanciaSQL();
   }
 }

?>