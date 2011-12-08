<?php

$ventananovisible = true;
$seccion_id = $_POST['seccion'];
//$cat_id = $_POST['cat'];
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar") {
    $mysqli = BaseDatos::Conectar();
    $atributos = array();
    $listado = array();
    if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.tipo_id, s.identificador AS seccion FROM items_atributos ia LEFT JOIN secciones_a_atributos isaa ON ia.id = isaa.atributo_id, secciones s WHERE isaa.seccion_id = ".$seccion_id." AND isaa.seccion_id = s.id"))
        echo __LINE__." - ".$mysqli->error;
    if($fila_at = $atributos_tipos->fetch_assoc()) {
        $seccion = $fila_at['seccion'];
        do {
            $fila_id = array_shift($fila_at);
            $atributos[$fila_id] = $fila_at;
            if($fila_at['en_listado'] == 1)
                $listado[] = $fila_id;
        }while($fila_at = $atributos_tipos->fetch_assoc());
        $atributos_tipos->close();
    }

    //$miniatura = $_POST['miniatura'] ? "'".$_POST['miniatura']."'" : "NULL";
    $cat = $_POST['cat'] ? "'".$_POST['cat']."'" : "NULL";
    if(empty($seccion_id)) {
        //echo "INSERT INTO `galerias` (`miniatura`, `creada`) VALUES ({$miniatura}, NOW())\n";
        //if(!$mysqli->query("INSERT INTO `items` (`seccion_id`, `f_creado`) VALUES ({$seccion_id}, now())")) die ("\n".__LINE__." mySql: ".$mysqli->error);
        if($id = $mysqli->insert_id)
            $modif++;
    }
    else {
        $id = $seccion_id;
        //$modif += $mysqli->affected_rows;
    }

//  foreach($_POST['leng'] AS $leng_k => $leng_v)
//   {
//	if(empty($leng_v)) continue;
	//echo "SELECT COUNT(*) FROM `galerias_textos` WHERE `galeria_id` = '{$id}' AND `leng_id` = '{$leng_v}' LIMIT 1\n";
	/*
	if(!$consulta = $mysqli->query("SELECT COUNT(*) FROM `galerias_textos` WHERE `galeria_id` = '{$id}' AND `leng_id` = '{$leng_v}' LIMIT 1")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	$comprobacion = $consulta->fetch_row();
	if($comprobacion[0] >= 1)
	 {
	  //echo "UPDATE `galerias_textos` SET `titulo` = '".$_POST['nombre'][$leng_k]."', `texto` = '".$_POST['descripcion'][$leng_k]."' WHERE `galeria_id` = '{$id}' AND `leng_id` = '{$leng_v}'";
	  if(!$mysqli->query("UPDATE `galerias_textos` SET `titulo` = '".$_POST['nombre'][$leng_k]."', `texto` = '".$_POST['descripcion'][$leng_k]."' WHERE `galeria_id` = '{$id}' AND `leng_id` = '{$leng_v}'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	 }
	else
	 {
	  //echo "INSERT INTO `galerias_textos` (`galeria_id`, `leng_id`, `titulo`, `texto`) VALUES ('{$id}', '{$leng_v}', '".$_POST['nombre'][$leng_k]."', '".$_POST['descripcion'][$leng_k]."')\n";
	  if(!$mysqli->query("INSERT INTO `galerias_textos` (`galeria_id`, `leng_id`, `titulo`, `texto`) VALUES ('{$id}', '{$leng_v}', '".$_POST['nombre'][$leng_k]."', '".$_POST['descripcion'][$leng_k]."')")) die ("\n".__LINE__." mySql: ".$mysqli->error);
	 }
	$modif += $mysqli->affected_rows;
	*/



/*********************************************/

	$almacen = new Valores($id, Valores::TIPO_SECCION);
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
                                //$mysqli->query("DELETE FROM galerias_imagenes_valores WHERE id = {$mod_valor_id}");
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

    if($_POST['btPublicar']) {
        //$mysqli->query("UPDATE `items` SET `estado_id` = '1', `f_modificado` = now() WHERE `id` = '{$id}' LIMIT 1");
        //$xml_modificar = $mysqli->affected_rows;
        //$publicar = (constant('T_PUBLICACION') == 'xhtml') ? new Seccion_publicarxhtml($seccion) : new Seccion_publicar($seccion);
        //$publicar = new Seccion_publicar($seccion);
        $publicar = new Seccion_publicar($seccion);
        $publicar->Item($id);
    }
    //elseif($modif) $mysqli->query("UPDATE `items` SET `estado_id` = '2', `f_modificado` = now() WHERE `id` = '{$id}' AND `estado` = '1' LIMIT 1");

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
if(headers_sent())
    echo "<a href=\"info?seccion={$seccion_id}&cat={$cat_id}&id={$id}\">editar?seccion={$seccion_id}&cat={$cat_id}&id={$id}</a>";
else
    header("Location: info?seccion={$seccion_id}");

?>