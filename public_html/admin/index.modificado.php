<?php

require_once('../../inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$seccion = array_key_exists(10, $_SESSION["permisos"]["admin_seccion"]) ? 10 : key($_SESSION["permisos"]["admin_seccion"]);
header("Location: listar?seccion=".$seccion);

?>
