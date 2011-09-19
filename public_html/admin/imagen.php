<?php

$archivo = trim($_SERVER['PATH_INFO'], "/");
/*header("content-type: text/plain; charset=utf-8");
echo $archivo."\n";
print_r($_GET);
exit;
*/
if($archivo)
 {
  $archivo = "../".$archivo;
  if(file_exists($archivo))
   {
    $archivo_size = @getimagesize($archivo);
    header('content-type: '.$archivo_size['mime']);
    if(empty($_GET['max']) || ($archivo_size[0] <= $_GET['max'] && $archivo_size[1] <= $_GET['max']))
     {
      readfile($archivo);
      exit;
     }
    else
     {
      $max = $_GET['max'];
      if($archivo_size[0] >= $archivo_size[1])
       {
		$div = ($archivo_size[0] / $max);
		$ancho = $max;
		$alto = ceil($archivo_size[1] / $div);
       }
      else
       {
		$div = ($archivo_size[1] / $max);
		$alto = $max;
		$ancho = ceil($archivo_size[0] / $div);
       }
      switch($archivo_size[2])
       {
		case 1:
		  $fuente = imagecreatefromgif($archivo);
		  $salida = "imagegif";
		  break;
		case 2:
		  $fuente = imagecreatefromjpeg($archivo);
		  $salida = "imagejpeg";
		  break;
		case 3:
		  $fuente = imagecreatefrompng($archivo);
		  $salida = "imagepng";
		  break;
       }
      $imagen = imagecreatetruecolor($ancho, $alto); 
      imagecopyresampled($imagen, $fuente, 0, 0, 0, 0, $ancho, $alto, $archivo_size[0], $archivo_size[1]);
      $salida($imagen);
     }
   }
 }

?>