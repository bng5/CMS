<?php

header("Content-type: text/html");
$ventananovisible = true;
$seccion_id = $_POST['seccion'];
//$cat_id = $_POST['cat'];
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar") {
	$mysqli = BaseDatos::Conectar();
	$atributos = Atributos::getArray('i', $seccion_id);

	if(empty($_POST['id'])) {
		/*
		 * FIXME
		 * No se si depende de la cantidad de items afectados
		 */
		$mysqli->query("UPDATE items SET orden = orden+1 WHERE 1");
		if(!$mysqli->query("INSERT INTO `items` (`seccion_id`, `orden`, `f_creado`, `propietario`) VALUES ({$seccion_id}, 1, now(), {$_SESSION['usuario_id']})"))
			die ("\n".__LINE__." mySql: ".$mysqli->error);
		if($id = $mysqli->insert_id)
			$modif++;
	}
	else {
		$id = $_POST['id'];
		$mysqli->query("DELETE FROM `items_a_categorias` WHERE `item_id` = {$id}");
	}
	
	if(is_array($_POST['cats'])) {
		function mas_id(&$item, $clave, $id) {
			$item .= ", ".$id.", ".($_POST['cats_orden'][$item] ? $_POST['cats_orden'][$item] : 'NULL');
		}
		array_walk($_POST['cats'], 'mas_id', $id);
		$mysqli->query("INSERT INTO `items_a_categorias` (`categoria_id`, `item_id`, `orden`) VALUES (".implode("), (", $_POST['cats']).")");
	}
	
	if(is_array($_POST['borrar']['sup']))
		$mysqli->query("DELETE FROM items_valores WHERE id = ".implode(" OR id = ", $_POST['borrar']['sup']));
	if(is_array($_POST['borrar']['sub']))
		$mysqli->query("DELETE FROM subitems_valores WHERE id = ".implode(" OR id = ", $_POST['borrar']['sub']));


	$almacen = new Valores($id, Valores::TIPO_ITEM);
	if(is_array($_POST['dato']['m'])) {
		$almacen->modificar($atributos, $_POST['dato']['m']);
	}

	if(is_array($_POST['dato']['n'])) {
		$almacen->ingresar($atributos, $_POST['dato']['n']);
	}

	if(is_array($_POST['subdato'])) {
        //if($atributos[$mod_atributo_id]['tipo'] == 'int' && $atributos[$mod_atributo_id]['subtipo'] == 7)
        // {
        foreach($_POST['subdato'] AS $subdato_k => $subdato_v) {
            $subatributos = array();
            if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, at.tipo, at.subtipo FROM items_atributos ia JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id, atributos_tipos at WHERE ia.tipo_id = at.id AND isaa.sup_atributo_id = {$subdato_k} ORDER BY isaa.orden"))
                echo __LINE__." - ".$mysqli->error;
            if($fila_at = $atributos_tipos->fetch_assoc()) {
                do {
                    $fila_id = array_shift($fila_at);
                    $subatributos[$fila_id] = $fila_at;
                }while($fila_at = $atributos_tipos->fetch_assoc());
                $atributos_tipos->close();
            }

            if(is_array($subdato_v['m'])) {
                foreach($subdato_v['m'] AS $mod_atributo_id => $mod_atributo_arr) {
                    foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor) {
                        if(empty($mod_valor)) {
                            if($subatributos[$mod_atributo_id]['sugerido'] == 2) {
                                // error de campo obligatorio
                            }
                            else {
                                //echo __LINE__." DELETE FROM subitems_valores WHERE id = {$mod_valor_id}<br />\n";
                                $mysqli->query("DELETE FROM subitems_valores WHERE id = {$mod_valor_id}");
                            }
                        }
                        else {
                            //if($atributos[$mod_atributo_id]['en_listado'] == 1 && !$listado[$mod_atributo_id])
                            //echo __LINE__." UPDATE subitems_valores SET `{$subatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}<br />\n";
                            $mysqli->query("UPDATE subitems_valores SET `{$subatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }

            if(is_array($subdato_v['n'])) {
                foreach($subdato_v['n'] AS $ins_atributo_id => $ins_atributo_arr) {
                    foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng) {
                        if(empty($ins_leng))
                            continue;
                        if(is_array($ins_leng)) {
                            foreach($ins_leng AS $ins_valor) {
                                if(empty($ins_valor))
                                    continue;
                                // echo "{$atributos[$mod_atributo_id]['subtipo']}<br />\n";
                                //echo __LINE__." INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, {$ins_leng_id}, '{$ins_valor}')<br />\n";
                                //echo "INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, {$ins_leng_id}, '{$ins_valor}')<br />\n";
                                $mysqli->query("INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, {$ins_leng_id}, '{$ins_valor}')");
                                $modif += $mysqli->affected_rows;
                            }
                        }
                        else {
                            //echo __LINE__." INSERT INTO subitems_valores (`atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$ins_atributo_id}, {$id}, '{$ins_leng}')<br />\n";
                            //echo "INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, '{$ins_leng}')<br />\n";
                            $mysqli->query("INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, '{$ins_leng}')");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }
        }
        //print_r($_POST['subdato'][$mod_valor]);
        // }
    }


	if(is_array($_POST['galimgdato'])) {
        $galatributos = array();
        if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, at.tipo, at.subtipo FROM items_atributos ia JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id, atributos_tipos at WHERE ia.tipo_id = at.id AND isaa.sup_atributo_id = {$galeria_attr} ORDER BY isaa.orden"))
            echo __LINE__." - ".$mysqli->error;
        if($fila_at = $atributos_tipos->fetch_assoc()) {
            do {
                $fila_id = array_shift($fila_at);
                $galatributos[$fila_id] = $fila_at;
            }while($fila_at = $atributos_tipos->fetch_assoc());
            $atributos_tipos->close();
        }
        foreach($_POST['galimgdato'] AS $galimg_k => $galimg_v) {
            if(is_array($galimg_v['m'])) {
                foreach($galimg_v['m'] AS $mod_atributo_id => $mod_atributo_arr) {
                    foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor) {
                        if(empty($mod_valor)) {
                            if($galatributos[$mod_atributo_id]['sugerido'] == 2) {
                                // error de campo obligatorio
                            }
                            else {
                                //echo __LINE__." DELETE FROM subitems_valores WHERE id = {$mod_valor_id}<br />\n";
                                $mysqli->query("DELETE FROM galerias_imagenes_valores WHERE id = {$mod_valor_id}");
                            }
                        }
                        else {
                            //echo "\nUPDATE galerias_imagenes_valores SET `{$galatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}\n";
                            $mysqli->query("UPDATE galerias_imagenes_valores SET `{$galatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }

            if(is_array($galimg_v['n'])) {
                foreach($galimg_v['n'] AS $ins_atributo_id => $ins_atributo_arr) {
                    foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng) {
                        if(empty($ins_leng))
                            continue;
                        if(is_array($ins_leng)) {
                            foreach($ins_leng AS $ins_valor) {
                                if(empty($ins_valor))
                                    continue;
                                //echo "<pre>\nINSERT INTO galerias_imagenes_valores (`atributo_id`, `galeria_id`, `imagen_id`, `leng_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES ({$ins_atributo_id}, {$galeria}, {$galimg_k}, {$ins_leng_id}, '{$ins_valor}')\n</pre>";
                                $mysqli->query("INSERT INTO galerias_imagenes_valores (`atributo_id`, `galeria_id`, `imagen_id`, `leng_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES ({$ins_atributo_id}, {$galeria}, {$galimg_k}, {$ins_leng_id}, '{$ins_valor}')");

                                $modif += $mysqli->affected_rows;
                            }
                        }
                        else {
                            //echo "\nINSERT INTO galerias_imagenes_valores (`area_id`, `atributo_id`, `item_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES ({$galdato_k}, {$ins_atributo_id}, {$id}, '{$ins_leng}')\n";
                            $mysqli->query("INSERT INTO galerias_imagenes_valores (`area_id`, `atributo_id`, `item_id`, `{$galatributos[$ins_atributo_id]['tipo']}`) VALUES ({$galdato_k}, {$ins_atributo_id}, {$id}, '{$ins_leng}')");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }
        }
        //print_r($_POST['subdato'][$mod_valor]);
        // }
    }


    if($_POST['publicar'] == 1) {
        $mysqli->query("UPDATE `items` SET `estado_id` = '1', `f_modificado` = now() WHERE `id` = '{$id}' LIMIT 1");
        //$xml_modificar = $mysqli->affected_rows;
        $publicar = new Item_publicar($seccion);
        $publicar->Item($id);
        @include(RUTA_CARPETA.'bng5/hooks/pos_publishitem'.$seccion_id.'.php');
    }
    else
        $mysqli->query("UPDATE `items` SET `estado_id` = '2', `f_modificado` = now() WHERE `id` = '{$id}' AND `estado_id` = '1' LIMIT 1");
    //if($modif)

/*	if($_POST['dato'])
	 {
	  foreach($_POST['dato'] AS $attri => $attra)
	   {
		foreach($attra AS $attrv)
		 {
		  if(empty($attrv)) continue;
echo "INSERT INTO `items_valores` (`atributo_id`, `item_id`, `leng_id`, `{$atributos[$attri]['tipo']}`) VALUES ('{$attri}', '{$id}', '{$leng_v}', '{$attrv}')";
		  $mysqli->query("INSERT INTO `items_valores` (`atributo_id`, `item_id`, `leng_id`, `{$atributos[$attri]['tipo']}`) VALUES ('{$attri}', '{$id}', '{$leng_v}', '{$attrv}')");
		  if($mysqli->errno == 1062) $mysqli->query("UPDATE `items_valores` SET `{$atributos[$attri]['tipo']}` = '{$attrv}' WHERE `atributo_id` = '{$attri}' AND `leng_id` = '{$leng_v}' AND `item_id` = '{$id}' LIMIT 1");
		  if($mysqli->affected_rows >= 1) $modif += $mysqli->affected_rows;
		 }
	   }
	 }*/

/*
	$atributos = array();
echo "SELECT atributo_id, gt.valor_id, texto FROM `galerias_info` ga JOIN `galerias_valores_t` gt ON ga.valor_id = gt.valor_id WHERE ga.galeria_id = '{$id}' AND gt.leng_id = '{$leng_v}'";
	$atributos_q = $mysqli->query("SELECT atributo_id, gt.valor_id, texto FROM `galerias_info` ga JOIN `galerias_valores_t` gt ON ga.valor_id = gt.valor_id WHERE ga.galeria_id = '{$id}' AND gt.leng_id = '{$leng_v}'");
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
		//$mysqli->query("INSERT INTO galerias_info (`galeria_id`, `atributo_id`, `valor_id`) VALUES ('{$id}', '{$att_indice}', '".$mysqli->insert_id."')");
	   }
	 }
*********************************************/


   }

$ycat = $_GET['cat'] ? "&cat=".$_GET['cat'] : "";
if(headers_sent())
    echo "<a href=\"editar?seccion={$seccion_id}&id={$id}{$ycat}\">editar?seccion={$seccion_id}&id={$id}{$ycat}</a>";
else
    header("Location: editar?seccion={$seccion_id}&id={$id}{$ycat}");
exit;

?>