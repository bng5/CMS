<?php

header("Content-Type: text/plain; charset=UTF-8");

require_once('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$seccion = array_key_exists(10, $_SESSION["permisos"]["admin_seccion"]) ? 10 : 11;

//print_r($_SESSION);
//exit;
header("Location: listar?seccion=".$seccion);

?>
