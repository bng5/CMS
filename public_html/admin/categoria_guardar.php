<?php
header("Content-type: text/html");

$ventananovisible = true;
$seccion_id = $_POST['seccion'];
$cat_id = $_POST['cat'];
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

function reordenar_cat($saltear_id = false, $saltear_pos = false, $superior = 0) {
    global $mysqli, $seccion_id;
    $consulta = $mysqli->query("SELECT `id`, `orden` IS NULL AS ordennull FROM `items_categorias` WHERE seccion_id = {$seccion_id} AND superior = {$superior} ORDER BY ordennull, `orden`");
    if ($fila = $consulta->fetch_row()) {
        $reor_num = 0;
        do {
            if ($fila[0] == $saltear_id)
                continue;
            $reor_num++;
            if ($reor_num == $saltear_pos)
                $reor_num++;
            $mysqli->query("UPDATE `items_categorias` SET `orden` = '{$reor_num}' WHERE `id` = '" . $fila[0] . "'");
            //$sqlite->queryExec("UPDATE galerias SET orden = '{$reor_num}' WHERE id = '".$fila[0]."'");
        } while ($fila = $consulta->fetch_row());
        $consulta->close();
    }
}

$id = "false";
$modif = 0;
if ($_POST["ia"] == "modificar") {
    $mysqli = BaseDatos::Conectar();
    $atributos = array();
    $listado = array();
    if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.tipo_id, asec.identificador AS seccion, asec.categorias_prof FROM items_atributos ia LEFT JOIN categorias_secciones_a_atributos isaa ON ia.id = isaa.atributo_id, secciones asec WHERE isaa.seccion_id = {$seccion_id} AND isaa.seccion_id = asec.id"))
        echo __LINE__ . " - " . $mysqli->error;
    if($fila_at = $atributos_tipos->fetch_assoc()) {
        $seccion = $fila_at['seccion'];
        $categorias_prof = array_pop($fila_at);
        do {
            $fila_id = array_shift($fila_at);
            $atributos[$fila_id] = $fila_at;
            if ($fila_at['en_listado'] == 1)
                $listado[] = $fila_id;
        }while ($fila_at = $atributos_tipos->fetch_assoc());
        $atributos_tipos->close();
    }
    else {
        if (!$cons_seccion = $mysqli->query("SELECT identificador, categorias_prof FROM secciones WHERE id = {$seccion_id}"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_seccion = $cons_seccion->fetch_row()) {
            $seccion = $fila_seccion[0];
            $categorias_prof = $fila_seccion[1];
            $cons_seccion->close();
        }
    }

    //$miniatura = $_POST['miniatura'] ? "'".$_POST['miniatura']."'" : "NULL";
    $cat = $_POST['cat'] ? "'" . $_POST['cat'] . "'" : 0;
    $pos = $_POST['pos'];
    $antesde = $_POST['antesde'];

    $orden = ($pos == 1) ? "NULL" : "'{$antesde}'";
    if (empty($_POST['id'])) {
        $saltear_form = true;
        //echo "INSERT INTO `galerias` (`miniatura`, `creada`) VALUES ({$miniatura}, NOW())\n";
        if (!$mysqli->query("INSERT INTO `items_categorias` (`seccion_id`, `superior`, `orden`) VALUES ({$seccion_id}, {$cat}, {$orden})"))
            die("\n" . __LINE__ . " mySql: " . $mysqli->error);
        if ($id = $mysqli->insert_id)
            $modif++;
        if ($pos == 2) {
            $saltear_id = $id;
            $saltear_pos = $antesde;
        }
        if (is_array($_POST['nombre'])) {
            foreach ($_POST['nombre'] AS $nombre_leng => $nombre)
                $mysqli->query("INSERT INTO `items_categorias_nombres` (`id`, `leng_id`, `nombre`) VALUES ({$id}, '{$nombre_leng}', '{$nombre}')");
        }
        if ($pos == 2) {
            $saltear_id = $id;
            $saltear_pos = $antesde;
        }
    } else {
        $saltear_form = true;
        $id = $_POST['id'];
        if (!empty($pos)) {
            if (!empty($pos)) {
                if ($pos == 2) {
                    $saltear_id = $id;
                    if (!empty($_POST['pos_actual']) && $_POST['pos_actual'] < $antesde) {
                        $antesde--;
                        if ($orden != "NULL")
                            $orden = "'{$antesde}'";
                    }
                    $saltear_pos = $antesde;
                }
                //$mysqli->query("UPDATE admin_secciones SET `orden` = {$orden} WHERE `id` = '{$id}'");
                $update_orden = ", `orden` = {$orden}";
            }
        }
        $mysqli->query("UPDATE `items_categorias` SET `superior` = {$cat}{$update_orden} WHERE id = {$id}");

        if (is_array($_POST['nombre'])) {
            $mysqli->query("DELETE FROM `items_categorias_nombres` WHERE `id` = {$id}");
            foreach ($_POST['nombre'] AS $nombre_leng => $nombre)
                $mysqli->query("INSERT INTO `items_categorias_nombres` (`id`, `leng_id`, `nombre`) VALUES ({$id}, '{$nombre_leng}', '{$nombre}')");
        }
        //$modif += $mysqli->affected_rows;
    }
    reordenar_cat($saltear_id, $saltear_pos, $cat);
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



    /*     * ****************************************** */

    $almacen = new Valores($id, Valores::TIPO_CATEGORIA);
    if (is_array($_POST['dato']['m'])) {
        $almacen->modificar($atributos, $_POST['dato']['m']);
    }

    if (is_array($_POST['dato']['n'])) {
        $almacen->ingresar($atributos, $_POST['dato']['n']);
    }

    if (is_array($_POST['subdato'])) {
        //if($atributos[$mod_atributo_id]['tipo'] == 'int' && $atributos[$mod_atributo_id]['subtipo'] == 7)
        // {
        foreach ($_POST['subdato'] AS $subdato_k => $subdato_v) {
            $subatributos = array();
            if (!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, at.tipo, at.subtipo FROM items_atributos ia JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id, atributos_tipos at WHERE ia.tipo_id = at.id AND isaa.sup_atributo_id = {$subdato_k} ORDER BY isaa.orden"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila_at = $atributos_tipos->fetch_assoc()) {
                do {
                    $fila_id = array_shift($fila_at);
                    $subatributos[$fila_id] = $fila_at;
                } while ($fila_at = $atributos_tipos->fetch_assoc());
                $atributos_tipos->close();
            }

            if (is_array($subdato_v['m'])) {
                foreach ($subdato_v['m'] AS $mod_atributo_id => $mod_atributo_arr) {
                    foreach ($mod_atributo_arr AS $mod_valor_id => $mod_valor) {
                        if (empty($mod_valor)) {
                            if ($subatributos[$mod_atributo_id]['sugerido'] == 2) {
                                // error de campo obligatorio
                            } else {
                                //echo __LINE__." DELETE FROM subitems_valores WHERE id = {$mod_valor_id}<br />\n";
                                $mysqli->query("DELETE FROM subitems_valores WHERE id = {$mod_valor_id}");
                            }
                        } else {
                            //if($atributos[$mod_atributo_id]['en_listado'] == 1 && !$listado[$mod_atributo_id])
                            //echo __LINE__." UPDATE subitems_valores SET `{$subatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}<br />\n";
                            $mysqli->query("UPDATE subitems_valores SET `{$subatributos[$mod_atributo_id]['tipo']}` = '{$mod_valor}' WHERE id = {$mod_valor_id}");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }

            if (is_array($subdato_v['n'])) {
                foreach ($subdato_v['n'] AS $ins_atributo_id => $ins_atributo_arr) {
                    foreach ($ins_atributo_arr AS $ins_leng_id => $ins_leng) {
                        if (empty($ins_leng))
                            continue;
                        if (is_array($ins_leng)) {
                            foreach ($ins_leng AS $ins_valor) {
                                if (empty($ins_valor))
                                    continue;
                                // echo "{$atributos[$mod_atributo_id]['subtipo']}<br />\n";
                                //echo __LINE__." INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, {$ins_leng_id}, '{$ins_valor}')<br />\n";
                                $mysqli->query("INSERT INTO subitems_valores (`area_id`, `atributo_id`, `item_id`, `leng_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$subdato_k}, {$ins_atributo_id}, {$id}, {$ins_leng_id}, '{$ins_valor}')");
                                $modif += $mysqli->affected_rows;
                            }
                        }
                        else {
                            //echo __LINE__." INSERT INTO subitems_valores (`atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$ins_atributo_id}, {$id}, '{$ins_leng}')<br />\n";
                            $mysqli->query("INSERT INTO subitems_valores (`atributo_id`, `item_id`, `{$subatributos[$ins_atributo_id]['tipo']}`) VALUES ({$ins_atributo_id}, {$id}, '{$ins_leng}')");
                            $modif += $mysqli->affected_rows;
                        }
                    }
                }
            }
            /*             * ************************************************************* */
        }
        //print_r($_POST['subdato'][$mod_valor]);
        // }
    }


    if ($_POST['publicar'] == 1) {
        $mysqli->query("UPDATE `items_categorias` SET `estado_id` = '1' WHERE `id` = '{$id}' LIMIT 1"); //, `f_modificado` = now()
        //$xml_modificar = $mysqli->affected_rows;
        $publicar = new Categoria_publicar($seccion);
        $publicar->Item($id);
    }
    else
        $mysqli->query("UPDATE `items_categorias` SET `estado_id` = '2' WHERE `id` = '{$id}' AND `estado_id` = '1' LIMIT 1"); //, `f_modificado` = now()
        //if($modif)

        /* 	if($_POST['dato'])
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
          } */

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
     * ******************************************* */


//   }




    /*     * ****************************************************************** */
    if (!$consulta_cats = $mysqli->query("SELECT ic.id, ic.superior, icn.nombre FROM items_categorias ic LEFT JOIN items_categorias_nombres icn ON ic.id = icn.id AND icn.leng_id = 1 WHERE seccion_id = {$seccion_id} ORDER BY superior, orden"))
        echo __LINE__ . " - " . $mysqli->error;
    if ($fila_cats = $consulta_cats->fetch_row()) {
        $categorias_nombres = array();
        $categorias_niveles = array();
        $categorias_niveles2 = array();
        $categorias_superiores = array();
        $rutas = array();
        $superior_prev = -1;
        do {
            $superior_id = $fila_cats[1];
            if ($superior_prev != $superior_id) {
                if ($superior_id > 0)
                    $rutas[$superior_id] = $categorias_superiores[$superior_id] ? $rutas[$categorias_superiores[$superior_id]] . " > " . $categorias_nombres[$superior_id] : $categorias_nombres[$superior_id];
                $superior_prev = $superior_id;
            }
            $categorias_nombres[$fila_cats[0]] = $fila_cats[2];
            $categorias_niveles[$fila_cats[1]][] = $fila_cats[0] . " : " . $fila_cats[0];
            $categorias_niveles2[$fila_cats[1]][$fila_cats[0]] = $fila_cats[0];
            $categorias_superiores[$fila_cats[0]] = $fila_cats[1];
        }while ($fila_cats = $consulta_cats->fetch_row());
        $consulta_cats->close();

        function crear_selector($selector = "", $sup = 0, $nivel = 0) {
            global $categorias_nombres, $categorias_niveles2, $categorias_prof;
            if ($categorias_prof && $nivel >= ($categorias_prof - 1))
                $plimite_prof = true;
            foreach ($categorias_niveles2[$sup] AS $k => $v) {
                $selector .= "<option value=\"{$k}\">" . str_repeat("&nbsp;&nbsp;", $nivel) . htmlspecialchars($categorias_nombres[$k]) . "</option>";
                if (!$plimite_prof && is_array($categorias_niveles2[$k])) {
                    $selector = crear_selector($selector, $k, ++$nivel);
                    $nivel--;
                }
            }
            return $selector;
        }

        $selector = crear_selector();

        function prueba_imprimir2(&$item, $clave) {
            global $categorias_superiores;
            $item = $clave . " : [{$categorias_superiores[$clave]}, '{$item}']";
        }

        function prueba_imprimir3(&$item, $clave) {
            global $categorias_superiores;
            $item = $clave . " : '{$item}'";
        }

        array_walk($categorias_nombres, 'prueba_imprimir2'); //_recursive
        array_walk($rutas, 'prueba_imprimir3'); //_recursive

        file_put_contents(RUTA_CARPETA . 'iacache/categorias_' . $seccion_id . '.php', "<script type=\"text/javascript\">\n//<![CDATA[\nvar categorias = {" . implode(",", $categorias_nombres) . "};\nvar rutas = {" . implode(", ", $rutas) . "};\n//]]>\n</script><select name=\"cats_sel\">{$selector}</select>");
    }
    /*     * ****************************************************************** */








    if (headers_sent ())
        echo "<a href=\"listar?seccion={$seccion_id}&cat={$_POST['cat']}\">listar?seccion={$seccion_id}&cat={$_POST['cat']}</a>";
    else
        header("Location: listar?seccion={$seccion_id}&cat={$_POST['cat']}");
    exit;




    if (is_array($_POST['remplazo'])) {
        $bsq_remp = implode("' OR imagen_id = '", $_POST['remplazo']);
        if (!$consulta_remp = $mysqli->query("SELECT imagen_id, imagen_archivo_nombre FROM galerias_imagenes g WHERE imagen_id = '{$bsq_remp}'"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_remp = $consulta_remp->fetch_row()) {
            do {
                $remplazos[$fila_remp[0]] = $fila_remp[1];
            } while ($fila_remp = $consulta_remp->fetch_row());
            $consulta_remp->close();
        }
//print_r($remplazos);
        foreach ($_POST['remplazo'] AS $remplazar_k => $remplazar_v) {
            if (!$remplazos[$remplazar_v])
                continue;
            $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `imagen_id` = '{$remplazar_v}' LIMIT 1");
            $mysqli->query("UPDATE `galerias_imagenes` SET imagen_archivo_nombre = '{$remplazos[$remplazar_v]}' WHERE `imagen_id` = '{$remplazar_k}' LIMIT 1");
            $modif += $mysqli->affected_rows;
        }
    }

    // Borra todas las imagenes con id enviado bajo 'borrarImg'
    if (is_array($_POST['borrarImg'])) {
        foreach ($_POST['borrarImg'] AS $imagen) {
            $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `imagen_id` = '{$imagen}' LIMIT 1");
            $modif += $mysqli->affected_rows;
        }
    }
    if (is_array($_POST['img'])) {
        $h = 1;
        foreach ($_POST['img'] AS $imagen) {
            $mysqli->query("UPDATE `galerias_imagenes` SET `imagen_orden` = '{$h}', `imagen_estado` = '" . $_POST['img_estado'][$imagen] . "', `galeria_id` = '{$id}' WHERE `imagen_id` = '{$imagen}' LIMIT 1");
            $imagen_titulo = $_POST['img_titulo'][$imagen] ? "'" . $_POST['img_titulo'][$imagen] . "'" : "NULL";
            $imagen_texto = $_POST['img_texto'][$imagen] ? "'" . $_POST['img_texto'][$imagen] . "'" : "NULL";
            $imagen_fecha = $_POST['img_fecha'][$imagen] ? "'" . $_POST['img_fecha'][$imagen] . "'" : "NULL";
            $mysqli->query("INSERT INTO `galerias_imagenes_textos` VALUES ({$imagen}, 1, {$imagen_titulo}, {$imagen_texto}, {$imagen_fecha})");
            if ($mysqli->errno == 1062)
                $mysqli->query("UPDATE `galerias_imagenes_textos` SET `imagen_titulo` = {$imagen_titulo}, `imagen_texto` = {$imagen_texto}, `imagen_fecha` = {$imagen_fecha} WHERE `imagen_id` = '{$imagen}' LIMIT 1");
            $modif += $mysqli->affected_rows;
            $h++;
        }
    }
    $mysqli->query("DELETE FROM `galerias_imagenes` WHERE `galeria_id` = '{$id}' AND `imagen_estado` = '0'");
    if ($_POST['publicar']) {
        $mysqli->query("UPDATE `galerias` SET `estado_id` = '1' WHERE `id` = '{$id}' LIMIT 1");
        $xml_modificar = $mysqli->affected_rows;
        $publicar = new Item_publicarBarriola($seccion);
        $publicar->Item($id);
    } elseif ($modif)
        $mysqli->query("UPDATE `galerias` SET `estado_id` = '2' WHERE `id` = '{$id}' AND `estado` = '1' LIMIT 1");
}


echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Galer&iacute;as</title>
    </head>
    <?php
    echo "<body onload=\"parent.cambiosGuardados(" . $id . ", " . $modif . ");\">";
//echo "<body>";
//echo "<pre>";
//print_r($_POST);
//print_r($atributos);
//echo "</pre>";
    ?>

</body>
</html>