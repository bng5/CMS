<?php

$ventananovisible = true;
require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');
//include('../../inc/class_imagen.php');

$carpeta_imagenes = array("../img/galerias/imagenes", "../img/galerias/imagenesChicas");
$max_ancho = array(false, 20);
$max_alto = array(225, 20);
$metodos = array("escalarTope", "escalarEstricto");
$errorno = "false";
$n_imagen_archivo = "false";
$imagen_id = "false";
if($_GET['remplazo'])
 {
  $atributo = "remplazar";
  $funcionJS = "remplazoImg";
 }
else
 {
  $atributo = "img_en";
  $funcionJS = "imgCargada";
 }

if($HTTP_POST_FILES[$atributo]['error'] == 0)
 {
  //if(!empty($_POST['borrar'])) @unlink($carpeta_imagenes.$_POST['borrar']);
  $archivo = $HTTP_POST_FILES[$atributo]['tmp_name'];
  $archivo_nombre = strtr(strtolower($HTTP_POST_FILES[$atributo]['name']), $enthtml);
  $imagen = new Imagen($archivo);
  if(!$imagen->valor('error'))
   {
    for($i = 0; $i < count($carpeta_imagenes); $i++)
     {
      $errorno = "false";
      $imagen->$metodos[$i]($max_ancho[$i], $max_alto[$i]);
      $n_imagen_ruta = $imagen->guardar($carpeta_imagenes[$i], $archivo_nombre);
      $archivo_nombre = basename($n_imagen_ruta);
      $n_imagen_archivo = "'${archivo_nombre}'";
      if(!$ancho)
       {
		$ancho = $imagen->valor('ancho');
		$alto = $imagen->valor('alto');
       }
      chmod($n_imagen_ruta, 0666);
     }
	if(!$mysqli->query("INSERT INTO `galerias_imagenes` (`galeria_id`, `imagen_archivo_nombre`, `ancho`, `alto`) VALUES ('".$_REQUEST['id']."', ${n_imagen_archivo}, '${ancho}', '${alto}')")) die("\n".__LINE__." mySql: ".$mysqli->error);
	$imagen_id = $mysqli->insert_id;

	// reordenar
	/*
	$consulta = $mysqli->query("SELECT `imagen_id`, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` WHERE galeria_id = '".$_REQUEST['id']."' ORDER BY ordennull ASC, `imagen_orden` ASC");
	if ($fila = $consulta->fetch_row())
	 {
	  $reor_num = 0;
	  do
	   {
	    $reor_num++;
	    $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '$reor_num' WHERE `imagen_id` = '".$fila[0]."'");
	   } while($fila = $consulta->fetch_row());
	  $consulta->close();
	 }
	*/
   }
  else $errorno = $imagen->valor('errorno');
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imágenes</title>
</head>
<?php

//$imagen_id = $n_imagen_archivo;
echo "<body onload=\"parent.${funcionJS}( ${errorno}, ${imagen_id}, ${n_imagen_archivo}, '${_REQUEST['frame']}', 'galerias/imagenesChicas');\">";
//echo "<body>";

echo "
<pre>";
print_r($_POST);
print_r($_GET);
print_r($HTTP_POST_FILES);
echo "</pre>";

?>

</body>
</html>