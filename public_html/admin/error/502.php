<?php

require('../../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../../inc/ad_sesiones.php');
/*
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
 { header('Content-type: application/xhtml+xml; charset=utf-8'); }
else
 { header('Content-type: text/html; charset=utf-8'); }
*/
$titulo = "&iexcl;Puerta de enlace err&oacute;nea!";
$seccion = false;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('../iaencab.php');

?>

<form action="<?php echo APU; ?>" method="post">
 <fieldset style="line-height:160%;">
  <legend><?php echo $titulo; ?></legend>
<p id="requerimiento">
	El servidor origen recibi&oacute; informaci&oacute;n 
	inv&aacute;lida por parte del servidor destino.

  <!--#if expr="$REDIRECT_ERROR_NOTES" -->
    <hr />
    <!--#echo encoding="none" var="REDIRECT_ERROR_NOTES" -->
  <!--#endif -->
	<br />
    <input type="button" value="Regresar" onclick="history.go(-1)" />&nbsp;&nbsp;<input type="submit" value="P&aacute;gina de inicio" /></p>
 </fieldset>
</form>

<?php

include('../iapie.php');

?>