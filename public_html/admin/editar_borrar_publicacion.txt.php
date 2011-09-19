<?php

if(!empty($_REQUEST['id']))
 {
  $id = $_REQUEST['id'];
  $seccion = $_REQUEST['seccion'];
  $seccion_id = $_REQUEST['seccion_id'];
  require('inc/iniciar.php');
  require('inc/ad_sesiones.php');
  header('Content-type: text/plain');
  $mysqli = BaseDatos::Conectar();
  $borrar = new Item_borrar($seccion);
  $borrar->Item($id);
  echo $borrar->modificadas;
 }

?>