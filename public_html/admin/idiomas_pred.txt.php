<?php
$seccion_id = 1;
require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');
header('Content-type: application/json; charset=UTF-8');
sleep(2);
if($_GET["id"])
 {
  //$respuesta->id = $_GET["id"];
  //$respuesta->accion = $_GET["accion"];
  $mysqli = BaseDatos::Conectar();
  if($_GET["accion"] == 'pomision')
   {
	$mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = '1' LIMIT 1");
	$campo = "poromision";
	$estado = '1';
   }
  elseif($_GET['accion'] == 'hab' || $_GET['accion'] == 'dehab')
   {
    $campo = "habilitado";
	$estado = ($_GET['accion'] == 'hab') ? '2' : '0';
   }
  elseif($_GET['accion'] == 'subesthab' || $_GET['accion'] == 'subestdeshab')
   {
    $campo = "habilitado";
	$estado = ($_GET['accion'] == 'subesthab') ? '1' : '2';
   }
  else
    exit;

  if(!$mysqli->query("UPDATE `lenguajes` SET `leng_${campo}` = '${estado}' WHERE `id` = '{$_GET["id"]}' LIMIT 1")) die (header("HTTP/1.1 204 No Content"));
  $respuesta->exito = $mysqli->affected_rows ? true : false;
  include('./idiomas_const.php');
 }
else
  $respuesta->exito = false;

echo json_encode($respuesta);


?>