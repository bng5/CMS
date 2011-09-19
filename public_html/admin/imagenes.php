<?php

$seccion = $_REQUEST['seccion'];
require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');

if($_REQUEST['id'] && $_REQUEST['seccion'])
 {
  switch ($_REQUEST['accion'])
   {
    case "borrar":
	  $archivo = $mysqli->query("SELECT imagen_archivo_nombre FROM `".$_REQUEST['seccion']."_imagenes` WHERE imagen_id = '${_REQUEST['id']}'");
	  if($fila_atts = $archivo->fetch_row())
	   {
	    $nombre = $fila_atts[0];
	    $archivo->close();
	    $mysqli->query("DELETE FROM `".$_REQUEST['seccion']."_imagenes` WHERE imagen_id = '${_REQUEST['id']}'");
	    if($mysqli->affected_rows) echo "1";
	    $mysqli->close();
	    @unlink('../img/'.$seccion.'/imagenes/'.$nombre);
	    @unlink('../img/'.$seccion.'/imagenesChicas/'.$nombre);
	   }
	  break;
    case "mover":
	  $item_id = $_REQUEST['id'];
	  $inicial = $_REQUEST['posorig'];
	  $final = $_REQUEST['pos'];
	  $padre = $_REQUEST['sup'];
	  if($inicial < $final) // ab
	   {
	    $ag_preg_pos_inc = $inicial;
	    $tbnovedades = $mysqli->query("SELECT `imagen_id`, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` WHERE `imagen_orden` > '$inicial' AND `imagen_orden` <= '$final' AND `galeria_id` = '$padre' AND `imagen_id` != '$item_id' ORDER BY ordennull ASC, `imagen_orden` ASC");
	    if ($row = $tbnovedades->fetch_row())
	     {
	      do
	       {
	        $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '$ag_preg_pos_inc' WHERE `imagen_id` = '".$row[0]."'");
	        $ag_preg_pos_inc--;
	       } while($row = $tbnovedades->fetch_row());
	      $tbnovedades->close();
	     }
	   }
	  elseif($inicial > $final) // arr
	   {
	    $ag_preg_pos_inc = ($final + 1);
	    $tbnovedades = $mysqli->query("SELECT `imagen_id`, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` WHERE `imagen_orden` >= '$final' AND `imagen_orden` < '$inicial' AND `galeria_id` = '$padre' AND `imagen_id` != '$item_id' ORDER BY ordennull ASC, `imagen_orden` ASC");
	    if ($row = $tbnovedades->fetch_row())
	     {
	      do
	       {
	        $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '$ag_preg_pos_inc' WHERE `imagen_id` = '${row[0]}'");
	        $ag_preg_pos_inc++;
	       } while($row = $tbnovedades->fetch_row());
	      $tbnovedades->close();
	     }
	   }
	  $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '".$final."' WHERE `imagen_id` = '".$item_id."'");
	  if($mysqli->affected_rows) echo "1";
      break;
   }
 }

/*
// reordenar órden
$consulta = $mysqli->query("SELECT `imagen_id`, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` WHERE galeria_id = '4' ORDER BY ordennull ASC, `imagen_orden` ASC");
if ($fila = $consulta->fetch_row())
 {
  $reor_num = 0;
  do
   {
    $reor_num++;
    $mysqli->query("UPDATE `secciones` SET `seccion_orden` = '$reor_num' WHERE `seccion_id` = '".$fila[0]."'");
   } while($fila = $consulta->fetch_row());
  $consulta->close();
 }
*/
?>