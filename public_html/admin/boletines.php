<?php

$seccion_id = 9;
require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$titulo = "Boletines";
$seccion = "boletines";

$mysqli = BaseDatos::Conectar();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
<?php

include('inc/iaencab.php');

?>

<p>aa</p>

<?php

include('inc/iapie.php');

?>