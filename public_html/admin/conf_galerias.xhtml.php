<?php

//header("Content-type: application/xhtml+xml; charset=ISO-8859-1");

$titulo = "Galerías";
$seccion = "galerias";
$seccion_min_perm = 5;

require('../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../inc/ad_sesiones.php');

/*echo "<?xml version=\"1.0\"?>\n";*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('iaencab.php');




/* por omision */
if(!$no_poromision)
 {

	
 }

include('iapie.php');

?>