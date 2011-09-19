<?php

if(empty($_SESSION['usuario']))
 {
  if($_COOKIE['sesion'] && $_COOKIE['pase'] && $_COOKIE['usuario'])
   {
	$login = new Login("recuperar");
	$suceso = $login->sucesoId();
	if($suceso == 3)
	 {
	  $_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
	 }

   }
  header("HTTP/1.1 401 Authorization Required", true, 401);//include(RUTA_CARPETA.'inc/admin_login.php');
  exit;
  //echo "header(\"Location: ".APU."login?id=".$ssid."&ref=".$ref."&usuario=".$ad_username."\",TRUE,307);";//include(RUTA_CARPETA.'inc/admin_login.php');
 }

?>
