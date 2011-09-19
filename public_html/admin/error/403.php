<?php

/*
require_once('iniciar.php');
$secciones = new adminsecciones();
require_once('ad_sesiones.php');
*/
header("HTTP/1.0 403 Forbidden");
/*
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
 { header('Content-type: application/xhtml+xml; charset=utf-8'); }
else
 { header('Content-type: text/html; charset=utf-8'); }
*/
$titulo = "&iexcl;Acceso prohibido!";
$seccion = false;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('inc/iaencab.php');

?>

<form action="<?php echo APU; ?>" method="post">
 <fieldset style="line-height:160%;">
  <legend><?php echo $titulo; ?></legend>
<p id="requerimiento">
<?php

//if(expr="$REDIRECT_URL = /\/$/")
// {
  echo "
    Usted no tiene permiso para accesar a la direcci&oacute;n
    solicitada.<br />Existe la posibilidad de que el directorio
    este protegido contra lectura o que no exista la
    documentaci&oacute;n requerida.\n";
// }
//else
// {
/*
  echo "<hr />\n";
  echo "
    Usted no tiene permiso de accesar al objeto solicitado.
    Existe la posibilidad de que este protegido contra
    lectura o que no haya podido ser leido por el servidor.\n";
*/
// }

?>
    <br />
    <input type="button" value="Regresar" onclick="history.go(-1)" />&nbsp;&nbsp;<input type="submit" value="P&aacute;gina de inicio" /></p>
 </fieldset>
</form>

<?php

include('inc/iapie.php');

?>