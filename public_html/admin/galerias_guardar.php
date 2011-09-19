<?php

$ventananovisible = true;
$seccion = "galerias";
require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');


$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  $atributos = array();
  if(!$atributos_tipos = $mysqli->query("SELECT ga.id, tipo FROM galerias_atributos ga LEFT JOIN galerias_atributos_n gan ON ga.id = gan.id AND leng_id = '1' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
  if($fila_at = $atributos_tipos->fetch_row())
   {
	do
	 {
	  $atributos[$fila_at[0]] = array('tipo' => $fila_at[1]);
	 }while($fila_at = $atributos_tipos->fetch_row());
	$atributos_tipos->close();
   }

  $miniatura = $_POST['miniatura'] ? "'".$_POST['miniatura']."'" : "NULL";
  $cat = $_POST['cat'] ? "'".$_POST['cat']."'" : "NULL";
  if(empty($_POST['id']))
   {
	//echo "INSERT INTO `galerias` (`miniatura`, `creada`) VALUES (${miniatura}, NOW())\n";
    if(!$mysqli->query("INSERT INTO `galerias` (`categoria_id`, `miniatura`, `creada`) VALUES (${cat}, ${miniatura}, NOW())")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    if($id = $mysqli->insert_id) $modif++;
   }
  else
   {
    $id = $_POST['id'];
	//echo "UPDATE `galerias` SET `miniatura` = ${miniatura} WHERE `id` = '${id}'\n";
    if(!$mysqli->query("UPDATE `galerias` SET `categoria_id` = ${cat}, `miniatura` = ${miniatura} WHERE `id` = '${id}'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    $modif += $mysqli->affected_rows;
   }

  foreach($_POST['leng'] AS $leng_k => $leng_v)
   {
	if(empty($leng_v)) continue;
	//echo "SELECT COUNT(*) FROM `galerias_textos` WHERE `galeria_id` = '${id}' AND `leng_id` = '${leng_v}' LIMIT 1\n";
	if(!$consulta = $mysqli->query("SELECT COUNT(*) FROM `galerias_textos` WHERE `galeria_id` = '${id}' AND `leng_id` = '${leng_v}' LIMIT 1")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	$comprobacion = $consulta->fetch_row();
	if($comprobacion[0] >= 1)
	 {
	  //echo "UPDATE `galerias_textos` SET `titulo` = '".$_POST['nombre'][$leng_k]."', `texto` = '".$_POST['descripcion'][$leng_k]."' WHERE `galeria_id` = '${id}' AND `leng_id` = '${leng_v}'";
	  if(!$mysqli->query("UPDATE `galerias_textos` SET `titulo` = '".$_POST['nombre'][$leng_k]."', `texto` = '".$_POST['descripcion'][$leng_k]."' WHERE `galeria_id` = '${id}' AND `leng_id` = '${leng_v}'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	 }
	else
	 {
	  //echo "INSERT INTO `galerias_textos` (`galeria_id`, `leng_id`, `titulo`, `texto`) VALUES ('${id}', '${leng_v}', '".$_POST['nombre'][$leng_k]."', '".$_POST['descripcion'][$leng_k]."')\n";
	  if(!$mysqli->query("INSERT INTO `galerias_textos` (`galeria_id`, `leng_id`, `titulo`, `texto`) VALUES ('${id}', '${leng_v}', '".$_POST['nombre'][$leng_k]."', '".$_POST['descripcion'][$leng_k]."')")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	 }
	$modif += $mysqli->affected_rows;



/*********************************************/
	if($_POST['dato'])
	 {
	  foreach($_POST['dato'] AS $attri => $attra)
	   {
		foreach($attra AS $attrv)
		 {
		  if(empty($attrv)) continue;
		  $mysqli->query("INSERT INTO `galerias_valores` (`atributo_id`, `leng_id`, `galeria_id`, `{$atributos[$attri]['tipo']}`) VALUES ('${attri}', '${leng_v}', '${id}', '${attrv}')");

		  if($mysqli->errno == 1062) $mysqli->query("UPDATE `galerias_valores` SET `{$atributos[$attri]['tipo']}` = '${attrv}' WHERE `atributo_id` = '${attri}' AND `leng_id` = '${leng_v}' AND `galeria_id` = '${id}' LIMIT 1");
		  if($mysqli->affected_rows >= 1) $modif += $mysqli->affected_rows;
		 }
	   }
	 }
	 
/*	 
	$atributos = array();
echo "SELECT atributo_id, gt.valor_id, texto FROM `galerias_info` ga JOIN `galerias_valores_t` gt ON ga.valor_id = gt.valor_id WHERE ga.galeria_id = '${id}' AND gt.leng_id = '${leng_v}'";
	$atributos_q = $mysqli->query("SELECT atributo_id, gt.valor_id, texto FROM `galerias_info` ga JOIN `galerias_valores_t` gt ON ga.valor_id = gt.valor_id WHERE ga.galeria_id = '${id}' AND gt.leng_id = '${leng_v}'");
	if($fila_atts = $atributos_q->fetch_row())
	 {
	  do
	   {
		$atributos[$fila_atts[0]] = array($fila_atts[1], $fila_atts[2]);
	   }while($fila_atts = $atributos_q->fetch_row());
	 }

	foreach($_POST['atributo'] AS $att_indice => $att_valor)
	 {
	  if($atributos[$att_indice])
	  {
		if($atributos[$att_indice][0] == $att_valor[$_POST['leng'][$i]]) continue;
		$mysqli->query("UPDATE `galerias_valores_t` SET `texto` = '".$att_valor[$i]."' WHERE `valor_id` = '".$atributos[$att_indice][0]."' AND `leng_id` = '".$_POST['leng'][$i]."' LIMIT 1");
		$modif += $mysqli->affected_rows;
	   }
	  else
	   {
		$mysqli->query("INSERT INTO galerias_valores (`leng_id`, `texto`) VALUES ('".$_POST['leng'][$i]."', '".$att_valor[$i]."')");
		//$mysqli->query("INSERT INTO galerias_info (`galeria_id`, `atributo_id`, `valor_id`) VALUES ('${id}', '${att_indice}', '".$mysqli->insert_id."')");
	   }
	 }
*********************************************/


   }

  if(is_array($_POST['remplazo']))
   {
	$bsq_remp = implode("' OR imagen_id = '", $_POST['remplazo']);
	if(!$consulta_remp = $mysqli->query("SELECT imagen_id, imagen_archivo_nombre FROM galerias_imagenes g WHERE imagen_id = '${bsq_remp}'")) echo __LINE__." - ".$mysqli->error;
	if($fila_remp = $consulta_remp->fetch_row())
	 {
	  do
	   {
		$remplazos[$fila_remp[0]] = $fila_remp[1];
	   }while($fila_remp = $consulta_remp->fetch_row());
	  $consulta_remp->close();
	 }
//print_r($remplazos);
	foreach($_POST['remplazo'] AS $remplazar_k => $remplazar_v)
     {
	  if(!$remplazos[$remplazar_v]) continue;
	  $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `imagen_id` = '${remplazar_v}' LIMIT 1");
	  $mysqli->query("UPDATE `galerias_imagenes` SET imagen_archivo_nombre = '{$remplazos[$remplazar_v]}' WHERE `imagen_id` = '${remplazar_k}' LIMIT 1");
	  $modif += $mysqli->affected_rows;
     }

   }          

  // Borra todas las imagenes con id enviado bajo 'borrarImg'
  if(is_array($_POST['borrarImg']))
   {
	foreach($_POST['borrarImg'] AS $imagen)
     {
	  $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `imagen_id` = '${imagen}' LIMIT 1");
	  $modif += $mysqli->affected_rows;
     }
   }
  if(is_array($_POST['img']))
   {
    $h = 1;
    foreach($_POST['img'] AS $imagen)
     {
	  $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '${h}', `imagen_estado` = '".$_POST['img_estado'][$imagen]."', `galeria_id` = '${id}' WHERE `imagen_id` = '${imagen}' LIMIT 1");
	  $imagen_titulo = $_POST['img_titulo'][$imagen] ? "'".$_POST['img_titulo'][$imagen]."'" : "NULL";
	  $imagen_texto = $_POST['img_texto'][$imagen] ? "'".$_POST['img_texto'][$imagen]."'" : "NULL";
	  $imagen_fecha = $_POST['img_fecha'][$imagen] ? "'".$_POST['img_fecha'][$imagen]."'" : "NULL";
	  $mysqli->query("INSERT INTO `galerias_imagenes_textos` VALUES (${imagen}, 1, ${imagen_titulo}, ${imagen_texto}, ${imagen_fecha})");
	  if($mysqli->errno == 1062) $mysqli->query("UPDATE `galerias_imagenes_textos` SET `imagen_titulo` = ${imagen_titulo}, `imagen_texto` = ${imagen_texto}, `imagen_fecha` = ${imagen_fecha} WHERE `imagen_id` = '${imagen}' LIMIT 1");
	  $modif += $mysqli->affected_rows;
	  $h++;
     }
   }
  $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `galeria_id` = '${id}' AND `imagen_estado` = '0'");
  if($_POST['publicar'])
   {
    $mysqli->query("UPDATE `galerias` SET `estado_id` = '1' WHERE `id` = '${id}' LIMIT 1");
    $xml_modificar = $mysqli->affected_rows;
	$publicar = new Item_publicarBarriola($seccion);
    $publicar->Item($id);
   }
  elseif($modif) $mysqli->query("UPDATE `galerias` SET `estado_id` = '2' WHERE `id` = '${id}' AND `estado` = '1' LIMIT 1");

 }


echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Galer&iacute;as</title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";
//echo "<body>";

echo "<pre>";
print_r($_POST);
//print_r($atributos);
echo "</pre>";

?>

</body>
</html>