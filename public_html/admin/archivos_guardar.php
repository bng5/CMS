<?php

$ventananovisible = true;
$seccion = "archivos";
require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificarcat")
 {
  if(empty($_POST['id']))
   {
    if(!$mysqli->query("INSERT INTO `archivos_categorias` (`superior`) VALUES ('".$_POST['ubicacion']."')")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    if($id = $mysqli->insert_id)
     {
      $modif++;
	 }
   }
  else
   {
    $id = $_POST['id'];
    if(!$mysqli->query("UPDATE `archivos_categorias` SET `superior` = '".$_POST['ubicacion']."' WHERE `id` = '".$_POST['id']."'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    $modif += $mysqli->affected_rows;
   }

  for($i = 0; $i < count($_POST['leng']); $i++)
   {
    if(empty($_POST['leng'][$i])) continue;
    if(!$consulta = $mysqli->query("SELECT id FROM `archivos_categorias_textos` WHERE `id` = '{$id}' AND `leng_id` = '".$_POST['leng'][$i]."' LIMIT 1")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    if($consulta->num_rows == 1)
     { if(!$mysqli->query("UPDATE `archivos_categorias_textos` SET `titulo` = '".$_POST['nombre'][$i]."', `texto` = '".$_POST['descripcion'][$i]."' WHERE `id` = '{$id}' AND `leng_id` = '".$_POST['leng'][$i]."'")) die ("\n".__LINE__." mySql: ".$mysqli->error); }
    else
     { if(!$mysqli->query("INSERT INTO `archivos_categorias_textos` (`id`, `leng_id`, `titulo`, `texto`) VALUES ('{$id}', '".$_POST['leng'][$i]."', '".$_POST['nombre'][$i]."', '".$_POST['descripcion'][$i]."')")) die ("\n".__LINE__." mySql: ".$mysqli->error); }
    $modif += $mysqli->affected_rows;
   }

  if($_POST['publicar'])
   {
    $mysqli->query("UPDATE `archivos_categorias` SET `estado` = '1' WHERE `id` = '{$id}' LIMIT 1");
    $xml_modificar = $mysqli->affected_rows;
	$publicar = new Item_publicar($seccion);
    $publicar->Item($id);
   }
  elseif($modif) $mysqli->query("UPDATE `archivos_categorias` SET `estado` = '2' WHERE `id` = '{$id}' AND `estado` = '1' LIMIT 1");
 }


echo "<?xml version=\"1.0\"?>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $seccion; ?></title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";
//echo "<body>";

echo "<pre>";
print_r($_POST);
echo "</pre>";

?>

</body>
</html>