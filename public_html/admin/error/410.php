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
$titulo = "&iexcl;Recurso no disponible!";
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
	Los recursos solicitados ya no est&aacute;n disponibles en
	este servidor y no existe una direcci&oacute;n alternativa.
<?php

//if(expr="$HTTP_REFERER")
// {
  echo "
	Le solicitamos que comunique al autor de la 
	<a href=\"".$_SERVER['HTTP_REFERER']."\">p&aacute;gina referente</a> que el enlace est&aacute; obsoleto.\n";
// }
//else
// {
  echo "<hr />";
  echo "
	Si usted sigui&oacute; el enlace desde una p&aacute;gina externa, 
	por favor contacte con el autor de esa p&aacute;gina.";
// }

?>
	<br />
    <input type="button" value="Regresar" onclick="history.go(-1)" />&nbsp;&nbsp;<input type="submit" value="P&aacute;gina de inicio" /></p>
 </fieldset>
</form>

<?php

include('../iapie.php');

?>