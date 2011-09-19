<?php

require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');
/*
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
 { header('Content-type: application/xhtml+xml; charset=utf-8'); }
else
 { header('Content-type: text/html; charset=utf-8'); }
*/
$titulo = "404 No se encontró el documento";
$seccion = false;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('inc/iaencab.php');
$request_uri = parse_url($_SERVER['REQUEST_URI']);

?>

<form action="<?php echo APU; ?>" method="post">
 <fieldset style="line-height:160%;">
  <legend>No se encontr&oacute; el documento</legend>
<p id="requerimiento"><i><?php echo $_SERVER['HTTP_HOST'].$request_uri['path']; ?></i>
<?php

if(!empty($request_uri['query'])) echo "<br />Par&aacute;metros de consulta: <i>".htmlentities($request_uri['query'])."</i>";

?>
 <br /><input type="button" value="Regresar" onclick="document.location.href='<?php echo htmlspecialchars($_SERVER["HTTP_REFERER"]) ?>'" />&nbsp;&nbsp;<input type="submit" value="P&aacute;gina de inicio" /></p>
 </fieldset>
</form>

<?php

include('inc/iapie.php');
exit;

?>