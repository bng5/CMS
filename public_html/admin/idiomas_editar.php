<?php

$seccion_id = 1;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

if($_POST['cod'])
 {
  $mysqli = BaseDatos::Conectar();
  $result = $mysqli->query("SELECT l.id, l.codigo, ln.nombre, l.nombre_nativo, l.dir FROM lenguajes l JOIN lenguajes_nombres ln ON l.id = ln.id AND ln.leng_id = {$_POST['nombre_leng_id']} WHERE l.`codigo` = '{$_POST['cod']}' LIMIT 1");
  if($fila = $result->fetch_assoc())
   {
    if($_POST['nombre_nativo'] || $_POST['dir'] || $_POST['nombre'])
	 {
	  $modif = array();
	  if($_POST['nombre_nativo'])
	   {
		$modif[] = "nombre_nativo = '{$_POST['nombre_nativo']}'";
		$fila['nombre_nativo'] = $_POST['nombre_nativo'];
	   }
	  if($_POST['dir'])
	   {
		$modif[] = "dir = '{$_POST['dir']}'";
		$fila['dir'] = $_POST['dir'];
	   }
	  $f_afectadas = 0;
	  if(count($modif))
	   {
		$mysqli->query("UPDATE `lenguajes` SET ".implode(", ", $modif)." WHERE `codigo` = '{$_POST['cod']}' LIMIT 1");
		$f_afectadas += $mysqli->affected_rows;
	   }
	  if($_POST['nombre'] && $_POST['nombre_leng_id'])
	   {
		$fila['nombre'] = $_POST['nombre'];
		$mysqli->query("UPDATE `lenguajes_nombres` SET nombre = '{$_POST['nombre']}' WHERE `id` = '{$fila['id']}' AND leng_id = {$_POST['nombre_leng_id']} LIMIT 1");
		$f_afectadas += $mysqli->affected_rows;
		unset($fila['id']);
	   }
	 }
	else
	  $errores[2] = "No se recibieron valores para modificar.";
   }
  else
   $errores[1] = "No se encontró el idioma indicado por el Id.";
 }
else
  $errores[3] = "No se ha indicado el Id del idioma.";

if(!$f_afectadas)
  $avisos[] = "No se han realizado modificaciones.";

header("Content-type: application/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>
<respuesta>';
if($errores)
 {
  echo '<errores>';
  foreach($errores AS $k => $v)
   {
    echo "<error cod=\"${k}\">${v}</error>";
   }
  echo '</errores>';
 }
elseif($fila)
 {
  echo '<item>';
  foreach($fila AS $k => $v)
   {
    echo "<${k}>${v}</${k}>";
   }
  echo '</item>';
 }
if($avisos)
 {
  echo '<avisos>';
  foreach($avisos AS $v)
   {
    echo "<aviso>${v}</aviso>";
   }
  echo '</avisos>';
 }
echo '</respuesta>';

?>