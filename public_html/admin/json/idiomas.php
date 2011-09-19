<?php
$seccion_id = 1;
require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');
header('Content-type: application/json; charset=UTF-8');
sleep(1);

$respuesta = array();//new stdClass();//Respuesta();
if($_SERVER["REQUEST_METHOD"] == 'POST')
 {
  $path = explode("/", trim($_SERVER['PATH_INFO'], "/ "));
  $accion = $path[0];
  //$respuesta->id = $_GET["id"];
  //$respuesta->accion = $_GET["accion"];
  $mysqli = BaseDatos::Conectar();


  $n_estado['habilitar']	 = array(0 => 2, 5 => 1);
  $n_estado['deshabilitar']	 = array(1 => 5, 0, 5, 0);
  $n_estado['publicacion']	 = array(1 => 3, 4, 1, 2);

  if($n_estado[$accion])
   {
	//if(is_array($_POST['habilitar']))
	//  $_POST['habilitar'] = implode("' OR codigo = '", $_POST['habilitar']);
	$resultado = $mysqli->query("SELECT `estado` FROM `lenguajes` WHERE `codigo` = '{$_POST['leng']}' LIMIT 1");
	if($fila = $resultado->fetch_row())
	 {
	  $respuesta['estado'] = $fila[0];
	  if($n_estado[$accion][$fila[0]] !== false)
	   {
		$mysqli->query("UPDATE `lenguajes` SET `estado` = {$n_estado[$accion][$fila[0]]} WHERE `codigo` = '{$_POST['leng']}'");
		if($mysqli->affected_rows)
	     {
		  $respuesta['exito'] = true;
		  $respuesta['estado'] = $n_estado[$accion][$fila[0]];
	     }
	    else
	      $respuesta['exito'] = false;
	   }
	  else
	   {
	    $respuesta['exito'] = false;
		//$respuesta['error'] = "!\$n_estado[${accion}][{$fila[0]}]";
	   }
	 }

   }


/*
  if($_POST['deshabilitar'])
   {
	//if(is_array($_POST['deshabilitar']))
	//  $_POST['deshabilitar'] = implode("' OR codigo = '", $_POST['deshabilitar']);
	$resultado = $mysqli->query("SELECT `estado` FROM `lenguajes` WHERE `codigo` = '{$_POST['deshabilitar']}' LIMIT 1");
	if($fila = $resultado->fetch_row())
	 {
	  $respuesta['estado'] = $fila[0];
	  $n_estado = array(1 => 5, 0, 5, 0);
	  $mysqli->query("UPDATE `lenguajes` SET `estado` = {$n_estado[$fila[0]]} WHERE `codigo` = '{$_POST['deshabilitar']}'");
	  if($mysqli->affected_rows)
	   {
		$respuesta['exito'] = true;
		$respuesta['estado'] = $n_estado[$fila[0]];
	   }
	  else
	    $respuesta['exito'] = false;
	 }
	else
	  $respuesta['exito'] = false;
//	$campo = "habilitado";
//	$estado = ($_POST['accion'] == 'habilitar') ? '2' : '0';
   }
*/


  if($accion == "predeterminado")
   {
	$respuesta['accion'] = 'predeterminado';



	$n_pred = 2;
	$resultado = $mysqli->query("SELECT `codigo`, `leng_poromision` FROM `lenguajes` WHERE `leng_poromision` IS NOT NULL");
	if($fila = $resultado->fetch_row())
	 {
	  do
	   {
		if($fila[1] == 1 && $fila[0] == $_POST['leng'])
		  $mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = 2 AND `codigo` != '{$_POST["leng"]}' LIMIT 1");
	    $respuesta['predeterminado'][$fila[1]] = $fila[0];
		if($fila[1] == 3 && $fila[0] == $_POST['leng'])
		  $n_pred = 1;
	   }while($fila = $resultado->fetch_row());

	 }

	//$arr_pred = array();

// AND (leng_poromision IS NULL OR leng_poromision != 1)
	  $mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = ${n_pred} WHERE `codigo` = '{$_POST["leng"]}' AND (estado = 1 OR estado = 2) LIMIT 1");
	  if($mysqli->affected_rows)
	   {
		$respuesta['exito'] = true;
		$respuesta['predeterminado'][$n_pred] = $_POST["leng"];
		if($respuesta['predeterminado'][3] == $_POST["leng"])
		 {
		  $respuesta['predeterminado'][1] = $respuesta['predeterminado'][3];
		  unset($respuesta['predeterminado'][3]);
		 }
		else
		  $mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = 3 WHERE `leng_poromision` = 1 LIMIT 1");

		if($respuesta['predeterminado'][2] != $_POST["leng"])
		  unset($respuesta['predeterminado'][2]);
		$mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = 2 AND `codigo` != '{$_POST["leng"]}' LIMIT 1");
	   }
	  else
	    $respuesta['exito'] = false;
	  

	$resultado = $mysqli->query("SELECT `codigo`, `leng_poromision` FROM `lenguajes` WHERE `leng_poromision` IS NOT NULL");
	if($fila = $resultado->fetch_row())
	 {
	  do
	   {
		if($fila[1] == 1 && $fila[0] == $_POST['leng'])
		  $mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = 2 AND `codigo` != '{$_POST["leng"]}' LIMIT 1");
	    $respuesta['predeterminado'][$fila[1]] = $fila[0];
	   }while($fila = $resultado->fetch_row());
	 }
	//$mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = '1' AND `codigo` != '{$_POST["leng"]}'");
	//$campo = "poromision";
	//$estado = '1';
   }

  //if(!$mysqli->query("UPDATE `lenguajes` SET `leng_${campo}` = '${estado}' WHERE `id` = '{$_GET["id"]}' LIMIT 1")) die (header("HTTP/1.1 204 No Content"));
  //$respuesta->exito = $mysqli->affected_rows ? true : false;
  if(isset($_POST['publicar']))
   {
	$mysqli->query("UPDATE `lenguajes` SET `estado` = 1 WHERE `estado` = 2");
	$mysqli->query("UPDATE `lenguajes` SET `estado` = 4 WHERE `estado` = 3");
	$mysqli->query("UPDATE `lenguajes` SET `estado` = 0 WHERE `estado` = 5");
	$mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = 1 WHERE `leng_poromision` = 2");
	$mysqli->query("UPDATE `lenguajes` SET `leng_poromision` = NULL WHERE `leng_poromision` = 3");


    include('../idiomas_const.php');
	$respuesta['estados'] = $idiomas_hab;
	$respuesta['predeterminado'] = $poromision;
   }
 }
//else
//  $respuesta->exito = false;

echo json_encode($respuesta);

?>