<?php

require('inc/iniciar.php');
require('inc/ad_sesiones.php');
header("Content-Type: application/x-javascript; charset=utf-8");

//$enthtml = array ("á" => "a", "à" => "a", "ä" => "a", "é" => "e", "è" => "e", "ë" => "e", "í" => "i", "ì" => "i", "ï" => "i", "ó" => "o", "ò" => "o", "ö" => "o", "ú" => "u", "ù" => "u", "ü" => "u", "ñ" => "n", "Ñ" => "n", " " => "", "@" => "a", " " => "_" );
$carpeta = RUTA_CARPETA."public_html/archivos/";
$errorno = "false";
$prod_id = "false";
$funcionJS = "docCargado";
/*
function tam_archivo($size)
 {
  $unidades = array("bytes", "Kb", "Mb", "Gb");
  for ($i = 0; $size > 1024; $i++)
   { $size /= 1024; }
  return round($size, 2)." ".$unidades[$i];
 }
*/

$mysqli = BaseDatos::Conectar();
$cons_extra = $mysqli->query("SELECT extra, unico FROM items_atributos WHERE id = '{$_POST['atributo']}' LIMIT 1");//) echo __LINE__." - ".$mysqli->error;
if($fila_extra = $cons_extra->fetch_row())
 {
  if(!empty($fila_extra[0]))
	$extra = unserialize($fila_extra[0]);
  if($fila_extra[1] == 0) $funcionJS = "docNCargado";
  $cons_extra->close();
 }

if($_FILES['archivo']['error'] == 0)
 {
  $enthtml = array (" " => "_", "?" => "", "#" => "", "\\" => "", "/" => "");
  $archivo_nombre = strtr($_FILES['archivo']['name'], $enthtml);

  $nombre_array = explode(".", $archivo_nombre);
  if(count($nombre_array) > 1)
   {
	$extension = strtolower(array_pop($nombre_array));
	$p_ext = ".".$extension;
	if($extension == "php")
	  $p_ext .= "s";
   }

  if(is_array($extra['negados']) && count($extra['negados']) && in_array($extension, $extra['negados']))
   {
   	$errorno = 5;
   }
  else
   {
	if(is_array($extra['permitidos']) && count($extra['permitidos']) && !in_array($extension, $extra['permitidos']))
	 {
	  $errorno = 5;
	 }
	else
	 {
	  $archivo = implode(".", $nombre_array);

	  if(mb_strlen($archivo) > 28)
	    $archivo = mb_substr($archivo, 0, 28); // si el nombre del archivo sin la extensión es mas largo de 28 caracteres se corta
	  if(file_exists($carpeta.$archivo.$p_ext)) // comprueba que no exista un archivo con el mismo nombre en la carpeta destino
	   {
	    if(mb_strlen($archivo) > 22)
		  $archivo = substr($archivo, 0, 22); // si ya existe se corta a 22 caracteres...
	    $archivo = basename(tempnam($carpeta, $archivo)); // y se le agregan caracteres para que tenga un nombre único
	    $tmpname = true; // bandera para indicar que se creó un archivo con nombre único
	   }

	  // comprueba que el archivo haya sido cargado a través del mecanismo de carga HTTP POST de PHP y lo mueve a $carpeta
	  if(move_uploaded_file($_FILES['archivo']['tmp_name'], $carpeta.$archivo.$p_ext))
	   {
	    if($tmpname && $extension)
          unlink($carpeta.$archivo);
	    $formato = trim(shell_exec("file -bi '".$carpeta.$archivo.$p_ext."'"));// $_FILES['archivo']['type']; //el tipo mime lo podés comprobar con funciones como 'mime_content_type' o 'FileInfo' para más certeza
	    $peso = $_FILES['archivo']['size'];
	    $archivo .= $p_ext;
	    $md5 = md5_file($carpeta.$archivo); // un hash

	    if(!$mysqli->query("INSERT INTO `archivos` (`archivo`, `nombre`, `formato`, `peso`, `fecha`, `hash`) VALUES ('".addslashes($archivo)."', '".addslashes($archivo_nombre)."', '${formato}', ${peso}, now(), '${md5}')")) echo __LINE__.": ".$mysqli->error;
	    $prod_id = $mysqli->insert_id; // 'insert_id' devuelve el valor del campo auto increment de la tabla
	   }
	 }
   }

  /*else
   {
    // no se pudo mover el archivo a la carpeta
    // esto se puede deber a que el archivo no haya sido cargado apropiadamente o que no tenga permisos para para escribir en la carpeta destino
   }
  */


  //if(!$mysqli->query("INSERT INTO `archivos_a_categorias` VALUES ( ".$prod_id.", '".$_POST['cat']."', '".$_POST['obra']."')")) echo __LINE__.": ".$mysqli->error;
 }
else
 { $errorno = $_FILES['archivo']['error']; }
$_POST['indice'] = (int) $_POST['indice'];
echo "{funcion : parent.{$funcionJS}, errorno : ${errorno}, archivoId : ${prod_id}, archivo : '".addslashes($archivo)."', archivo_nombre : '".addslashes($archivo_nombre)."', indice : {$_POST['indice']}}";
//echo "<body onload=\"parent.{$funcionJS}(${errorno}, ${prod_id}, '".addslashes($archivo)."', '".addslashes($archivo_nombre)."', {$_POST['indice']});\">";

//echo "<body>";
/*
echo "<pre>";
//echo $errorno."\n".$archivo_nombre."\n".$ext."\n";
print_r($_POST);
print_r($_GET);
print_r($_FILES);
print_r($extra);
echo "</pre>";
*/

?>
