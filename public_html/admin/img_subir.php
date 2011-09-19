<?php

/*******************************************************
***                                                 ****
**     Este archivo forma parte del sistema de        **
**    administración desarrollado por:                **
**    El toro de Picasso                              **
**    http://eltorodepicasso.com                      **
**                                                    **
**    Última modificación: 2007-04-11T14:29:49-0300   **
***                                                  ***
*******************************************************/

require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');
include('../../inc/class_imagen.php');

/*
$carpeta_imagenes = "../img/".$_REQUEST['carpeta']."/";
$max_ancho = 296;
$max_alto = 242;
$errorno = "false";
$n_imagen_archivo = "false";
$foto_id = "false";
$atributo = $_POST['carpeta']."_img";

if($HTTP_POST_FILES[$atributo]['error'] == 0)
 {
  if(!empty($_POST['borrar'])) @unlink($carpeta_imagenes.$_POST['borrar']);
  $archivo = $HTTP_POST_FILES[$atributo]['tmp_name'];
  $archivo_nombre = strtr(strtolower($HTTP_POST_FILES[$atributo]['name']), $enthtml);
  $imagen = new Imagen($archivo);
  if(!$imagen->valor('error'))
   {
    $errorno = "false";
    $imagen->escalarTope($max_ancho, $max_alto);
    $n_imagen_ruta = $imagen->guardar($carpeta_imagenes, $archivo_nombre);
    $n_imagen_archivo = "'".basename($n_imagen_ruta)."'";
    chmod($n_imagen_ruta, 0666);
   }
  else
   {
    $errorno = $imagen->valor('errorno');
   }
 }
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Imágenes</title>
</head>
<?php

/*
echo "<body onload=\"parent.imgCargada( ".$errorno.", ".$n_imagen_archivo.", '".$_POST['carpeta']."'";
if($_REQUEST['frame']) echo ", '${_REQUEST['frame']}'";
echo ");\">";
*/
echo "<body>";

echo "<pre>";
print_r($_POST);
print_r($_GET);
print_r($HTTP_POST_FILES);
echo "</pre>";

?>

</body>
</html>