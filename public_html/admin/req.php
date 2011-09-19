<?php

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $_GET['error']; ?> requerido</title>
</head>
<body>

<?php

switch ($_GET['error'])
 {
  case "Firefox":
	echo "<h2>Firefox requerido</h2>
<p>Esta versi&oacute;n del administrador se ha desarrollado para ser usada a trav&eacute;s del navegador <a href=\"http://www.mozilla-europe.org/es/products/firefox/\">Firefox</a>.</p>";
	break;
  default:
	echo "<h2>Error</h2>
<p>Error no especificado.</p>";
 }

?>

</body>
</html>