<?php

if(!empty($_REQUEST['id']))
 {
  $id = $_REQUEST['id'];
  $seccion = "galerias";
  require('../../inc/configuracion.php');
  require('../../inc/ad_sesiones.php');
  header('Content-type: text/plain');

  $borrar = new Item_borrar($seccion);
  $borrar->Item($id);
  $modificadas = $borrar->modificadas;
 }

?>