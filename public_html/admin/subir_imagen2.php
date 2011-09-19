<?php

$ventananovisible = true;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');
//include('../../inc/class_imagen.php');

/*********************************
header("Content-type: text/plain");
*********************************/


$mysqli = BaseDatos::Conectar();
if(!$consulta = $mysqli->query("SELECT extra FROM items_atributos i WHERE id = {$_POST['atributo']}")) die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_row())
 {
  eval('$extra = '.$fila[0].';');
/*
print_r($extra);
print_r($_POST);
print_r($_GET);
print_r($_FILES);
exit;
*/

$carpeta_imagenes_raiz = "../img/";
$carpeta_imagenes = array("imagenes", "imagenesChicas");
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
  $atributo = "archivo";
  $funcionJS = "imgCargada";
 }

if($_FILES[$atributo]['error'] == 0)
 {
  //if(!empty($_POST['borrar'])) @unlink($carpeta_imagenes.$_POST['borrar']);
  $archivo = $_FILES[$atributo]['tmp_name'];
  //$archivo_nombre = strtr(strtolower($HTTP_POST_FILES[$atributo]['name']), $enthtml);
  $archivo_nombre = $_FILES[$atributo]['name'];
  $imagen = new Imagen($archivo);
  if(!$imagen->dato('errorno'))
   {
    foreach($extra AS $i => $metodos)
     {
      $errorno = "false";
      $imagen->$metodos[0]($metodos[1], $metodos[2], $metodos[4], $metodos[5]);
      $n_imagen_ruta = $imagen->guardar($carpeta_imagenes_raiz.$carpeta_imagenes[$i], $archivo_nombre);
      $archivo_nombre = basename($n_imagen_ruta);
      $n_imagen_archivo = "'${archivo_nombre}'";
      //if(!$ancho)
      // {
		$ancho[] = $imagen->dato('ancho');
		$alto[] = $imagen->dato('alto');
		$peso[] = filesize($n_imagen_ruta);
		if(!$formato)
		 {
		  $formato = $imagen->dato('mime');
		  $md5 = md5_file($n_imagen_ruta);
		 }

      // }
      chmod($n_imagen_ruta, 0666);
     }

    $mysqli = BaseDatos::Conectar();
	if(!$mysqli->query("INSERT INTO `imagenes` (`archivo`, `nombre`, `fecha`, `ancho`, `alto`, `peso`, `formato`, `hash`, `ancho_m`, `alto_m`, `peso_m`) VALUES ('${archivo_nombre}', '${archivo_nombre}', now(), '{$ancho[0]}', '{$alto[0]}', '{$peso[0]}', '${formato}', '${md5}', '{$ancho[1]}', '{$alto[1]}', '{$peso[1]}')")) die("\n".__LINE__." mySql: ".$mysqli->error);
	$imagen_id = $mysqli->insert_id;

	// reordenar
	//$consulta = $mysqli->query("SELECT `imagen_id`, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` WHERE galeria_id = '".$_REQUEST['id']."' ORDER BY ordennull ASC, `imagen_orden` ASC");
	//if ($fila = $consulta->fetch_row())
	// {
	//  $reor_num = 0;
	//  do
	//   {
	//    $reor_num++;
	//    $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '$reor_num' WHERE `imagen_id` = '".$fila[0]."'");
	//   } while($fila = $consulta->fetch_row());
	//  $consulta->close();
	// }
   }
  else $errorno = $imagen->dato('errorno');
 }
else $errorno = $_FILES[$atributo]['error'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imágenes</title>
</head>
<?php

//$imagen_id = $n_imagen_archivo;
echo "<body onload=\"parent.${funcionJS}( ${errorno}, ${imagen_id}, ${n_imagen_archivo}, '${_REQUEST['frame']}', {$_POST['indice']});\">";
//echo "<body>";

echo "&lt;body onload=\"parent.${funcionJS}( ${errorno}, ${imagen_id}, ${n_imagen_archivo}, '${_REQUEST['frame']}', {$_POST['indice']});\"&gt;";
echo "
<pre>";
print_r($_POST);
print_r($_GET);
print_r($_FILES);
echo "</pre>";

?>

</body>
</html>

<?php

 }
else
 {
  echo "Debe ser indicado un atributo válido";
 }

?>