<?php

class Seccion_Items
 {
  function Atributos($seccion_id)
   {
	$mysqli = BaseDatos::Conectar();
	$array = array();
	$consulta = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.tipo_id, ia.extra, ia.formato, isaa.seccion_id, isaa.orden, isaa.por_omision, isaa.en_listado, isaa.salida, isaa.superior FROM items_atributos ia, items_secciones_a_atributos isaa WHERE ia.id = isaa.atributo_id AND isaa.seccion_id = ".$seccion_id." ORDER BY isaa.orden, ia.id");
	if($fila = $consulta->fetch_object('Atributo'))
	 {
	  do
	   {
		$array[$fila->id] = $fila;
	   }while($fila = $consulta->fetch_object('Atributo'));
	  $consulta->close();
	  $consulta = $mysqli->query("SELECT ian.id, ian.leng_id, ian.atributo FROM items_atributos_n ian, items_secciones_a_atributos isaa WHERE ian.id = isaa.atributo_id AND isaa.seccion_id = ".$seccion_id);
	  if($fila = $consulta->fetch_row())
	   {
	    do
	     {
		  $array[$fila[0]]->etiquetas[$fila[1]] = $fila[2];
	     }while($fila = $consulta->fetch_row());
	   }
	  $consulta->close();
	  return $array;
	 }
	return false;
   }
 }

?>