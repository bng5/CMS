<?php

$seccion_id = 1;
$seccion = "idiomas";

require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  $mysqli = BaseDatos::Conectar();
  if(empty($_POST['id']))
   {
    $mysqli->query("INSERT INTO `lenguajes` (`leng_cod`, `iso_639_3`, `xml_lang`, `dir`, `leng_habilitado`, `nombre_nativo`) VALUES ('".$_POST['iso_639_1']."', '".$_POST['iso_639_3']."', '".$_POST['xml_lang']."', '".$_POST['dir']."', '".$_POST['estado']."', '".$_POST['nombre_nativo']."')");
    if($id = $mysqli->insert_id)
     {
      $mysqli->query("INSERT INTO `lenguajes_nombres` (`id`, `leng_id_nombre`, `nombre`) VALUES ('".$id."', '".$id."', '".$_POST['nombre_nativo']."')");
      foreach($_POST['leng'] as $indice => $valor) $mysqli->query("INSERT INTO `lenguajes_nombres` (`id`, `leng_id_nombre`, `nombre`) VALUES ('".$id."', '".$indice."', '".$valor."')");
      $modif++;
     }
   }
  else
   {
    $id = $_POST['id'];
    if(!$mysqli->query("UPDATE `lenguajes` SET `leng_cod` = '".$_POST['iso_639_1']."', `iso_639_3` = '".$_POST['iso_639_3']."', `xml_lang` = '".$_POST['xml_lang']."', `dir` = '".$_POST['dir']."', `leng_habilitado` = '".$_POST['estado']."', `nombre_nativo` = '".$_POST['nombre_nativo']."' WHERE `id` = '".$_POST['id']."'")) echo "<br />\n".__LINE__." - ".$mysqli->error;
    $modif += $mysqli->affected_rows;
    $mysqli->query("UPDATE `lenguajes_nombres` SET `nombre` = '".$_POST['nombre_nativo']."' WHERE `id` = '".$id."' AND `leng_id_nombre` = '${id}'");
	foreach($_POST['leng'] as $indice => $valor)
     {
      $mysqli->query("INSERT INTO `lenguajes_nombres` (`id`, `leng_id_nombre`, `nombre`) VALUES ('".$id."', '".$indice."', '".$valor."')");
      if($mysqli->errno) $mysqli->query("UPDATE `lenguajes_nombres` SET `nombre` = '".$valor."' WHERE `id` = '".$id."' AND `leng_id_nombre` = '".$indice."'");
      $modif++;
     }
   }
  if($modif > 0) include('./idiomas_const.php');
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Idiomas</title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";
/*
echo "<body>";
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

?>
</body>
</html>