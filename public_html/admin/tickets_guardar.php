<?php

header("Content-type: text/html");

$ventananovisible = true;
$seccion_id = 8;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

if($_SESSION['usuario'] == "etdp")
 {
  echo "<pre>";
  echo "\n\nprint_r\n";
  print_r($_POST);
  echo "\n\nvar_export\n";
  var_export($_POST);
  echo "\n\nvar_dump\n";
  var_dump($_POST);
  echo "</pre>";
 }



/*

*    [tipo] => 1
    [reproducibilidad] => 4
*    [severidad] => 5
*    [resumen] => Ayuda
    [descripcion] => Dónde encuentro ayuda?
    [pasos_reproducirlo] =>
    [info_adicional] =>
    [cabeceras] => Array
        (
            [Accept] => text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,* / *;q=0.5
            [Accept_charset] => ISO-8859-1,utf-8;q=0.7,*;q=0.7
            [Accept_encoding] => gzip,deflate
            [Accept_language] => es-uy,es-ar;q=0.8,es;q=0.6,en-us;q=0.4,en;q=0.2
            [Cache_control] => max-age=0
            [Connection] => keep-alive
            [Cookie] => usuario=etdp; sesion=527dc674fef5986442f361cfdbeff7c3; pase=4c1497b2fab3bce3c2aa3695eac1dc4d
            [Host] => admin.lucalorenzini.com
            [Keep_alive] => 300
            [Referer] => http://admin.lucalorenzini.com/estadisticas?cont=OS&periodo=112008
            [User_agent] => Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.18) Gecko/20081113 Ubuntu/7.10 (gutsy) Firefox/2.0.0.18
        )

    [javascript] => Array
        (
            [habilitado] => Si
            [platform] => Linux i686
            [userAgent] => Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.18) Gecko/20081113 Ubuntu/7.10 (gutsy) Firefox/2.0.0.18
            [appCodeName] => Mozilla
            [appName] => Netscape
            [appVersion] => 5.0 (X11; en-US)
            [language] => en-US
            [oscpu] => Linux i686
            [vendor] => Ubuntu
            [vendorSub] => 7.10
            [product] => Gecko
            [productSub] => 20081113
            [plugins] => Shockwave Flash 9.0 r124
            [securityPolicy] =>
            [cookieEnabled] => Si
            [onLine] => Si
            [javaEnabled] => Si
            [buildID] => 2008111317
        )

*    [visibilidad] => 1

*/


$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  $mysqli = BaseDatos::Conectar();

  if(empty($_POST['id']))
   //{$_SESSION['usuario_id']
    //if(!
    $mysqli->query("INSERT INTO `tickets` (`publico`, `categoria_id`, `severidad_id`, `resumen`, `arch_adjunto`, `usuario_id`, `abierto`) VALUES ({$_POST['visibilidad']}, {$_POST['tipo']}, {$_POST['severidad']}, '{$_POST['resumen']}', NULL, {$_SESSION['usuario_id']}, now())");//) die ("\n".__LINE__." mySql: ".$mysqli->error);
/*
`id`, `cod`, `estado_id`, `publico`, `categoria_id`, `severidad_id`, `resumen`, `arch_adjunto`
*/
    if($id = $mysqli->insert_id) $modif++;
echo $id;
exit;

   }
  else
   {
    $id = $_POST['id'];
	$mysqli->query("DELETE FROM `items_a_categorias` WHERE `item_id` = ${id}");
   }

  if(is_array($_POST['cats']))
   {
   	function mas_id(&$item, $clave, $id)
	 {
	  $item .= ", ".$id.", ".($_POST['cats_orden'][$item] ? $_POST['cats_orden'][$item] : 'NULL');
	 }
	array_walk($_POST['cats'], 'mas_id', $id);
   	$mysqli->query("INSERT INTO `items_a_categorias` (`categoria_id`, `item_id`, `orden`) VALUES (".implode("), (", $_POST['cats']).")");
   }

	if(is_array($_POST['borrar']['sup'])) $mysqli->query("DELETE FROM items_valores WHERE id = ".implode(" OR id = ", $_POST['borrar']['sup']));
	if(is_array($_POST['borrar']['sub'])) $mysqli->query("DELETE FROM subitems_valores WHERE id = ".implode(" OR id = ", $_POST['borrar']['sub']));

	if(is_array($_POST['dato']['m']))
	 {
	  foreach($_POST['dato']['m'] AS $mod_atributo_id => $mod_atributo_arr)
	   {
	   	foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor)
		 {
		  if(empty($mod_valor))
		   {
			if($atributos[$mod_atributo_id]['sugerido'] == 2)
			 {
			  // error de campo obligatorio
			 }
			else
			 {
			  $mysqli->query("DELETE FROM items_valores WHERE id = ${mod_valor_id}");

			 }
		   }
		  else
		   {
			if($atributos[$mod_atributo_id]['tipo'] == 'int' && $atributos[$mod_atributo_id]['subtipo'] == 4)
			 {
			  $galeria_attr = $mod_atributo_id;
			  $galeria = $mod_valor;
			  $mysqli->query("DELETE FROM galerias_imagenes WHERE galeria_id = ${mod_valor}");
			  $in = 1;
			  if(is_array($_POST['img']))
			   {
				foreach($_POST['img'] AS $imagenes)
				 {
				  $mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${mod_valor}, ${imagenes}, ${in})");
				  $in++;
				 }
			   }
			 }
			else
			 {
		   	  //if($atributos[$mod_atributo_id]['en_listado'] == 1 && !$listado[$mod_atributo_id])
			  if($atributos[$mod_atributo_id]['tipo'] == "text" && $atributos[$mod_atributo_id]['subtipo'] == 1) $mod_valor .= "', `int` = '".$_POST['prot'][$mod_atributo_id];
			  $mysqli->query("UPDATE items_valores SET `{$atributos[$mod_atributo_id]['tipo']}` = '${mod_valor}' WHERE id = ${mod_valor_id}");
			  $modif += $mysqli->affected_rows;
			 }
		   }
		 }
	   }
	 }

	if(is_array($_POST['dato']['n']))
	 {
	  foreach($_POST['dato']['n'] AS $ins_atributo_id => $ins_atributo_arr)
	   {
	   	foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng)
		 {
		  if($atributos[$ins_atributo_id]['tipo'] == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 4)
		   {
			$mysqli->query("INSERT INTO galerias (`creada`) VALUES (now())");
			$ins_leng = $mysqli->insert_id;
			$galeria_attr = $ins_atributo_id;
			$galeria = $ins_leng;
			$in = 1;
			if(is_array($_POST['img']))
			 {
			  foreach($_POST['img'] AS $imagenes)
			   {
if($_SESSION['usuario'] == 'etdp') echo "\nINSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${ins_leng}, ${imagenes}, ${in})\n";
				$mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${ins_leng}, ${imagenes}, ${in})");
				$in++;
			   }
			 }
		   }

		  if(empty($ins_leng)) continue;
		  if(is_array($ins_leng))
		   {
			foreach($ins_leng AS $ins_valor)
			 {
			  if(empty($ins_valor)) continue;
			  $mysqli->query("INSERT INTO items_valores (`atributo_id`, `item_id`, `leng_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, ${ins_leng_id}, '${ins_valor}')");
			  $modif += $mysqli->affected_rows;
			 }
		   }
		  else
		   {
			if($atributos[$ins_atributo_id]['tipo'] == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 6)
			 {
			  $rango = str_replace(" ", "", $ins_leng);
			  $rango = explode(",", $rango);
			  $pares = $_POST['extra'][$ins_atributo_id];
			  foreach($rango AS $numeros)
			   {
				$numeros = explode("-", $numeros);
				for($i = $numeros[0]; $i <= $numeros[1]; $i++)
				 {
				  if($pares == 1 && ($i%2) != 1) continue;
				  elseif($pares == 2 && ($i%2) == 1) continue;
				  $mysqli->query("INSERT INTO subitems (`item_id`, `atributo_id`, `codigo`) VALUES (${id}, ${ins_atributo_id}, '${i}')");
				  //echo $i." (".($i%2).")\n";
				 }
			   }
			  $mysqli->query("INSERT INTO items_valores (`atributo_id`, `item_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_atributo_id}')");
			 }
			else
			 {
			  if($atributos[$ins_atributo_id]['tipo'] == "text" && $atributos[$ins_atributo_id]['subtipo'] == 1)
			   {
				$atributos[$ins_atributo_id]['tipo'] .= "`, `int";
				$ins_leng .= "', '".$_POST['prot'][$ins_atributo_id];
			   }
			  $mysqli->query("INSERT INTO items_valores (`atributo_id`, `item_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_leng}')");
			 }
			$modif += $mysqli->affected_rows;
		   }
		 }
	   }
	 }


	if(is_array($_POST['subdato']))
	 {
	  foreach($_POST['subdato'] AS $subdato_k => $subdato_v)
	   {
		$subatributos = array();
		if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, at.tipo, at.subtipo FROM items_atributos ia JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id, atributos_tipos at WHERE ia.tipo_id = at.id AND isaa.sup_atributo_id = ${subdato_k} ORDER BY isaa.orden")) echo __LINE__." - ".$mysqli->error;
		if($fila_at = $atributos_tipos->fetch_assoc())
		 {
		  do
		   {
			$fila_id = array_shift($fila_at);
			$subatributos[$fila_id] = $fila_at;
		   }while($fila_at = $atributos_tipos->fetch_assoc());
		  $atributos_tipos->close();
		 }

		if(is_array($subdato_v['m']))
		 {
		  foreach($subdato_v['m'] AS $mod_atributo_id => $mod_atributo_arr)
		   {
			foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor)
			 {
			  if(empty($mod_valor))
			   {
				if($subatributos[$mod_atributo_id]['sugerido'] == 2)
				 {
				  // error de campo obligatorio
				 }
				else
				 {
				  $mysqli->query("DELETE FROM subitems_valores WHERE id = ${mod_valor_id}");
				 }
			   }
			  else
			   {
				$mysqli->query("UPDATE subitems_valores SET `{$subatributos[$mod_atributo_id]['tipo']}` = '${mod_valor}' WHERE id = ${mod_valor_id}");
				$modif += $mysqli->affected_rows;
			   }
			 }
		   }
		 }

		if(is_array($subdato_v['n']))
		 {
		  foreach($subdato_v['n'] AS $ins_atributo_id => $ins_atributo_arr)
		   {
			foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng)
			 {
			  if(empty($ins_leng)) continue;
			  if(is_array($ins_leng))
			   {
				foreach($ins_leng AS $ins_valor)
				 {
				  if(empty($ins_valor)) continue;
				  $mysqli->query("INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES (${subdato_k}, ${ins_atributo_id}, ${id}, ${ins_leng_id}, '${ins_valor}')");
				  $modif += $mysqli->affected_rows;
				 }
			   }
			  else
			   {
				$mysqli->query("INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES (${subdato_k}, ${ins_atributo_id}, ${id}, '${ins_leng}')");
				$modif += $mysqli->affected_rows;
			   }
			 }
		   }
		 }
	   }
	 }



	if(is_array($_POST['galimgdato']))
	 {
	  $galatributos = array();
	  if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, at.tipo, at.subtipo FROM items_atributos ia JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id, atributos_tipos at WHERE ia.tipo_id = at.id AND isaa.sup_atributo_id = ${galeria_attr} ORDER BY isaa.orden")) echo __LINE__." - ".$mysqli->error;
	  if($fila_at = $atributos_tipos->fetch_assoc())
	   {
		do
		 {
		  $fila_id = array_shift($fila_at);
		  $galatributos[$fila_id] = $fila_at;
		 }while($fila_at = $atributos_tipos->fetch_assoc());
		$atributos_tipos->close();
	   }
	  foreach($_POST['galimgdato'] AS $galimg_k => $galimg_v)
	   {
		if(is_array($galimg_v['m']))
		 {
		  foreach($galimg_v['m'] AS $mod_atributo_id => $mod_atributo_arr)
		   {
			foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor)
			 {
			  if(empty($mod_valor))
			   {
				if($galatributos[$mod_atributo_id]['sugerido'] == 2)
				 {
				  // error de campo obligatorio
				 }
				else
				 {
				  $mysqli->query("DELETE FROM galerias_imagenes_valores WHERE id = ${mod_valor_id}");
				 }
			   }
			  else
			   {
				$mysqli->query("UPDATE galerias_imagenes_valores SET `{$galatributos[$mod_atributo_id]['tipo']}` = '${mod_valor}' WHERE id = ${mod_valor_id}");
				$modif += $mysqli->affected_rows;
			   }
			 }
		   }
		 }

		if(is_array($galimg_v['n']))
		 {
		  foreach($galimg_v['n'] AS $ins_atributo_id => $ins_atributo_arr)
		   {
			foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng)
			 {
			  if(empty($ins_leng)) continue;
			  if(is_array($ins_leng))
			   {
				foreach($ins_leng AS $ins_valor)
				 {
				  if(empty($ins_valor)) continue;
				  $mysqli->query("INSERT INTO galerias_imagenes_valores (`atributo_id`, `galeria_id`, `imagen_id`, `leng_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${galeria}, ${galimg_k}, ${ins_leng_id}, '${ins_valor}')");

				  $modif += $mysqli->affected_rows;
				 }
			   }
			  else
			   {
				$mysqli->query("INSERT INTO galerias_imagenes_valores (`area_id`, `atributo_id`, `item_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES (${galdato_k}, ${ins_atributo_id}, ${id}, '${ins_leng}')");
				$modif += $mysqli->affected_rows;
			   }
			 }
		   }
		 }
	   }

	 }



	//if($modif)

/*	if($_POST['dato'])
	 {
	  foreach($_POST['dato'] AS $attri => $attra)
	   {
		foreach($attra AS $attrv)
		 {
		  if(empty($attrv)) continue;
echo "INSERT INTO `items_valores` (`atributo_id`, `item_id`, `leng_id`, `{$atributos[$attri]['tipo']}`) VALUES ('${attri}', '${id}', '${leng_v}', '${attrv}')";
		  $mysqli->query("INSERT INTO `items_valores` (`atributo_id`, `item_id`, `leng_id`, `{$atributos[$attri]['tipo']}`) VALUES ('${attri}', '${id}', '${leng_v}', '${attrv}')");
		  if($mysqli->errno == 1062) $mysqli->query("UPDATE `items_valores` SET `{$atributos[$attri]['tipo']}` = '${attrv}' WHERE `atributo_id` = '${attri}' AND `leng_id` = '${leng_v}' AND `item_id` = '${id}' LIMIT 1");
		  if($mysqli->affected_rows >= 1) $modif += $mysqli->affected_rows;
		 }
	   }
	 }*/

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


//   }

$ycat = $_GET['cat'] ? "&cat=".$_GET['cat'] : "";
if(headers_sent()) echo "<a href=\"editar?seccion=${seccion_id}&id=${id}${ycat}\">editar?seccion=${seccion_id}&id=${id}${ycat}</a>";
else header("Location: editar?seccion=${seccion_id}&id=${id}${ycat}");
exit;

?>