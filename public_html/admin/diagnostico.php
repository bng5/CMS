<?php

//header("Content-Type: text/plain; charset=UTF-8");

if(!@include('inc/iniciar.php'))
 {
  header("Content-Type: text/plain; charset=UTF-8");
  echo "No fue posible incluír el archivo de inicio.
	Ubicación: ".dirname(__FILE__)."
	include_path: ".get_include_path();
  exit;
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
 <title>Diagnostico</title>

</head>
<body>
<div>

<?php


function carpetas_escritura($array, $nivel = array())
 {
  echo "<ul>";
  foreach($array AS $k => $carpeta)
   {
	echo "<li>";
	if(is_array($carpeta))
	 {
	  echo $k;
	  array_push($nivel, $k);
	  carpetas_escritura($carpeta, $nivel);
	  array_pop($nivel);
	 }
	else
	 {
	  echo $carpeta.":	";
	  if(count($nivel))
		$carpeta = implode("/", $nivel)."/".$carpeta;
	  echo is_writable(RUTA_CARPETA.$carpeta) ? 'ok' : 'X';
     }
	echo "</li>\n";
   }
  echo "</ul>";
 }

$carpetas_w = array('iacache', 'img', 'public_html' => array('img' => array('0', '1', '2', '3', '4', '5'), 'archivos', 'menuXml', 'inc_xhtml', 'item', 'seccion'));
echo "
<h3>Permisos de carpetas</h3>
";
carpetas_escritura($carpetas_w);
//print_r($carpetas_w);


?>
</div>
</body>
</html>