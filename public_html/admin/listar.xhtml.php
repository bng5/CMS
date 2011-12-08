<?php

//header("Content-type: application/xhtml+xml; charset=utf-8");

$mod = "listar";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();

//if($seccionObj = Secciones::obtenerPorId((int) $_REQUEST['seccion'])) {
if($seccion = DB_Secciones::obtenerSeccion(1, array('id' => (int) $_REQUEST['seccion']))) {
	$seccionObj = $seccion;
	if(!$seccionObj->items)
		header("Location: info?seccion={$_REQUEST['seccion']}");
	$titulo = $seccionObj->getNombre($_SESSION['leng_id']);
	$seccion_id = $seccionObj->id;
	$cs_info = $seccionObj->info;
	$cs_items = $seccionObj->items;
	$cs_categorias = $seccionObj->categorias;
	$cs_prof_categorias = $seccionObj->categorias_prof;

	require('inc/ad_sesiones.php');
	$vista = new VistaAdmin_Documento($seccion);
	ob_start();
    /*
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

    <?php
    */
    // modificar
	if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item']) {
		$modificar = $_POST['lista_item'];
		$modificadas = 0;

		// borrar
		if($_POST['mult_submit'] == "Eliminar completamente") {
            $modificacion_tipo_accion = "eliminadas";
            $borrar = new Item_borrar($seccion);
            for($i = 0; $i < count($modificar); $i++)
                $borrar->Item($modificar[$i], true);
            $modificadas = $borrar->modificadas;
        }

        // habilitar
        elseif($_POST['mult_submit'] == "Publicar") {
            $modificacion_tipo_accion = "publicados";
            $publicar = new Item_publicar($seccion);
            for($i = 0; $i < count($modificar); $i++) {
                $publicar->Item($modificar[$i]);
                $mysqli->query("UPDATE `items` SET `estado_id` = '1' WHERE `id` = '{$modificar[$i]}' LIMIT 1");
            }
            $modificadas = $publicar->modificadas;
        }
        // deshabilitar
        elseif($_POST['mult_submit'] == "Eliminar publicación") {
            $modificacion_tipo_accion = "eliminadas de la publicaci&oacute;n";
            $borrar = new Item_borrar($seccion);
            for($i = 0; $i < count($modificar); $i++)
                $borrar->Item($modificar[$i]);
            $modificadas = $borrar->modificadas;
        }
        if($modificadas > 0) {
            $div_mensaje = "Items ".$modificacion_tipo_accion.": ".$modificadas;
            //tabla_informacion("Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas);
        }
    }
    // fin modificar


    //$cat_superior = current($secciones->actual_superior);
    //include('inc/iaencab.php');

    if(!empty($_GET['eliminarcat'])) {
        if($_REQUEST['conf'] == 1) {
            $mysqli->query("DELETE FROM items_categorias WHERE id = '{$_GET['eliminarcat']}' AND seccion_id = {$seccion_id} LIMIT 1");
            if($mysqli->affected_rows == 1) {
                if($_POST['contenidos']['c'] == 'm') {
                    $mysqli->query("UPDATE items_categorias SET superior = ".($_POST['catdestino'] ? $_POST['catdestino'] : 0)." WHERE superior = {$_GET['eliminarcat']}");
                }
                elseif($_POST['contenidos']['c'] == 'e') {
                    function destruir_arbol_cat($superior) {
                        global $mysqli;
                        if(!$tbsubcat = $mysqli->query("SELECT id FROM items_categorias WHERE superior = {$superior}"))
                            die(__LINE__." - ".$mysqli->error);
                        if($row_subcat = $tbsubcat->fetch_row()) {
                            do {
                                $mysqli->query("DELETE FROM items_categorias WHERE id = '{$row_subcat[0]}'");
                                $mysqli->query("DELETE FROM items_categorias_nombres WHERE id = '{$row_subcat[0]}'");
                                $mysqli->query("DELETE FROM items_a_categorias WHERE categoria_id = '{$row_subcat[0]}'");
                                $mysqli->query("DELETE FROM categorias_valores WHERE categoria_id = '{$row_subcat[0]}'");
                                $mysqli->query("DELETE FROM pubcats__{$seccion_id} WHERE id = '{$row_subcat[0]}'");
                                destruir_arbol_cat($row_subcat[0]);
                            }while($row_subcat = $tbsubcat->fetch_row());
                        }
                    }
                    destruir_arbol_cat($_GET['eliminarcat']);
                }
                $mysqli->query("DELETE FROM items_categorias_nombres WHERE id = '{$_GET['eliminarcat']}'");
                $mysqli->query("DELETE FROM items_a_categorias WHERE categoria_id = '{$_GET['eliminarcat']}'");
                $mysqli->query("DELETE FROM categorias_valores WHERE categoria_id = '{$_GET['eliminarcat']}'");
                $mysqli->query("DELETE FROM pubcats__{$seccion_id} WHERE id = '{$_GET['eliminarcat']}'");
                echo("<div class=\"div_alerta\">La categoría ha sido eliminada.</div>");
            }
        }
        else {
            function subcategoriaSS($subcat, $n, $seleccionado, $exclusion) {
                global $mysqli, $seccion_id, $js_categorias, $js_categorias_nombres;
                if($exclusion) {
                    $excluir = "c.`id` != '{$exclusion}' AND";
                }

        		if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE {$excluir} `superior` = '{$subcat}' AND seccion_id = {$seccion_id} ORDER BY c.`orden`"))
                    die(__LINE__." - ".$mysqli->error);
                if($row_subcat = $tbsubcat->fetch_row()) {
                    $js_categorias .= "CATEGORIAS[{$row_subcat[1]}] = {};\n";
                    $i = 0;
                    do {
                        $js_categorias .= "CATEGORIAS[{$row_subcat[1]}][{$row_subcat[0]}] = {$row_subcat[3]};\n";
                        $js_categorias_nombres .= "CATEGORIAS_N[{$row_subcat[0]}] = '{$row_subcat[2]}';\n";
                        $i++;
                    }while($row_subcat = $tbsubcat->fetch_row());
                }
            }

            function subcategoria($subcat, $n, $seleccionado, $exclusion) {
                global $mysqli, $seccion_id, $js_categorias, $js_categorias_nombres, $limite;
                if(isset($limite) && $n >= $limite) {
                    subcategoriaSS($subcat, $n, $seleccionado, $exclusion);
                    return;
                }
                $separador_niv = "&nbsp;&nbsp;&nbsp;&nbsp;";
                if($exclusion) {
                    $excluir = "c.`id` != '{$exclusion}' AND";
                }

                if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE {$excluir} `superior` = '{$subcat}' AND seccion_id = {$seccion_id} ORDER BY cn.`nombre`"))
                    die(__LINE__." - ".$mysqli->error);
            	if($row_subcat = $tbsubcat->fetch_row()) {
                    //$limite++;
                    $js_categorias .= "CATEGORIAS[{$row_subcat[1]}] = {};\n";
                    $i = 0;
                    do {
                        $js_categorias .= "CATEGORIAS[{$row_subcat[1]}][{$row_subcat[0]}] = {$row_subcat[3]};\n";
                        $js_categorias_nombres .= "CATEGORIAS_N[{$row_subcat[0]}] = '{$row_subcat[2]}';\n";
                        $i++;
                        echo("<option value=\"".$row_subcat[0]."\"");
                        if ($seleccionado == $row_subcat[0]) {
                            echo(" selected=\"selected\"");
                        }
                        echo(">".str_repeat($separador_niv, $n).$row_subcat[2]."</option>\n");

                        $subcat = $row_subcat[0];
                        subcategoria($subcat, ++$n , $seleccionado, $exclusion);
                        $n--;
                    } while($row_subcat = $tbsubcat->fetch_row());
                }
            }
            
            if(!$result = $mysqli->query("SELECT icn.nombre, ic.seccion_id FROM items_categorias ic LEFT JOIN items_categorias_nombres icn ON ic.id = icn.id AND leng_id = 1 WHERE ic.id = {$_GET['eliminarcat']} LIMIT 1"))
                die(__LINE__." - ".$mysqli->error);
            if($fila = $result->fetch_row()) {
                echo("<form action=\"listar?".htmlspecialchars($_SERVER['QUERY_STRING'])."\" method=\"post\"><input type=\"hidden\" name=\"conf\" value=\"1\" /><fieldset id=\"confirmacion\"><legend>Borrar categoría <b>{$fila[0]}</b></legend>");
                //if(!$resultcat = $mysqli->query("(SELECT 'i', COUNT(*) FROM items_a_categorias iac WHERE iac.categoria_id = {$_GET['eliminarcat']}) UNION (SELECT 'c', COUNT(*) FROM items_categorias ic WHERE ic.superior = {$_GET['eliminarcat']})")) die(__LINE__." - ".$mysqli->error);
                if(!$resultcat = $mysqli->query("SELECT COUNT(*) FROM items_categorias ic WHERE ic.superior = {$_GET['eliminarcat']}"))
                    die(__LINE__." - ".$mysqli->error);
                if($row_cat = $resultcat->fetch_row()) {
                    echo("<p>Esta categoría contiene otra".(($row_cat[0] == 1) ? " categoría" : "s {$row_cat[0]} categorías").".</p><ul><li><input type=\"radio\" name=\"contenidos[c]\" value=\"m\" id=\"contenidos_cm\" checked=\"checked\" /><label for=\"contenidos_cm\"> Moverla/s a</label> <select name=\"catdestino\"><option value=\"0\">(Inicio)</option>\n");
                    $js_categorias = '';
                    $js_categorias_nombres = '';
                    if($prof_categorias >= 1)
                        $limite = ($prof_categorias - 1);
                    subcategoria(0, 0, false, $_GET['eliminarcat']);
                    echo("</select></li><li><input type=\"radio\" name=\"contenidos[c]\" value=\"e\" id=\"contenidos_ce\" /><label for=\"contenidos_ce\"> Eliminarla/s</label></li></ul>");
                }
                echo("<div class=\"botones\"><input type=\"button\" value=\"Cancelar\" onclick=\"history.back()\" /> <input type=\"submit\" value=\"Aceptar\" /></div></fieldset></form>");
                $vista->html(ob_get_contents());
                ob_end_clean();
                $vista->mostrar();
                //include('inc/iapie.php');
                exit;
            }
            $div_mensaje = "No se encontró la categoría requerida.";
        }
    }
    
    if($cs_categorias) {
        $cat_cuenta = 0;
        $cat = $_REQUEST['cat'] ? $_REQUEST['cat'] : 0;
        if(!$resultcat = $mysqli->query("SELECT c.id, c.superior, cn.nombre, COUNT(`item_id`) AS `Items` FROM items_categorias c, items_categorias_nombres cn LEFT JOIN `items_a_categorias` iac ON cn.id = iac.categoria_id WHERE c.id = cn.id AND c.id = '{$cat}' AND seccion_id = {$seccion_id} GROUP BY c.`id` LIMIT 1"))
            die(__LINE__." - ".$mysqli->error);
        if($row_cat0 = $resultcat->fetch_row()) {

            function nav_cat($p_id, $nav_cat) {
                global $mysqli, $seccion_id, $cat_cuenta;
                $cat_cuenta++;
                if(!$resultcat = $mysqli->query("SELECT c.id, c.superior, cn.nombre FROM items_categorias c LEFT JOIN items_categorias_nombres cn ON c.id = cn.id AND cn.leng_id = 1 WHERE c.id = '{$p_id}' LIMIT 1"))
                    die(__LINE__." - ".$mysqli->error);
                if($row_cat = $resultcat->fetch_row()) {
                    $nombre = $row_cat[2] ? $row_cat[2] : "id: {$row_cat[0]}";
                    $nav_cat = nav_cat($row_cat[1], $nav_cat)."<a href=\"listar?seccion={$seccion_id}&amp;cat={$row_cat[0]}\">".htmlspecialchars($nombre)."</a>&nbsp;&gt;&nbsp;";
                }
                return $nav_cat;
            }
            
            $cat = $row_cat0[0];

            echo("<div><a href=\"listar?seccion={$seccion_id}&amp;cat=0\">Inicio</a>&nbsp;&gt;&nbsp;".nav_cat($row_cat0[1], NULL)."<b>".htmlspecialchars($row_cat0[2])."</b></div>");
            if($cs_prof_categorias && $cat_cuenta >= $cs_prof_categorias)
                $lim_prof = true;
            $items_link = "&amp;cat={$cat}";
        }
        else {
            if($cat != "0") {
                echo("<b>Error</b><br />No se encontr&oacute; la categor&iacute;a seleccionada.");
                $vista->html(ob_get_contents());
                ob_end_clean();
                $vista->mostrar();
                //include('inc/iapie.php');
                exit;
            }
        }
    }

    if($cs_info)
        echo("  <div><a href=\"".APU."info?seccion={$_REQUEST['seccion']}\">Editar sección</a></div>");
    echo("  <div><a href=\"".APU."editar?seccion={$_REQUEST['seccion']}&amp;cat={$_REQUEST['cat']}\">Agregar item</a>");
    if($cs_categorias && !$lim_prof && $_SESSION['permisos']['admin_seccion_c'][$seccion_id] > 1)
        echo(" - <a href=\"".APU."categoria?seccion={$_REQUEST['seccion']}&amp;cat={$cat}\">Agregar categoría</a>");
    echo("</div>");
    if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 5) {
        echo("<div><a href=\"".APU."configuracion?seccion={$seccion_id}\">Configuraci&oacute;n de items</a>");
        if($cs_categorias)
            echo(" - <a href=\"".APU."configuracion_c?seccion={$seccion_id}\">Configuraci&oacute;n de categorías</a>");
        echo("</div>\n");
    }
    //echo "<fieldset><legend>Aviso</legend>Todas las modificaciones que se realizen en este listado afectan directamente a la publicaci&oacute;n.</fieldset>";
    //echo "<form action=\"#\" name=\"orden\"><fieldset><legend>Orden de publicaci&oacute;n</legend><label for=\"orden_criterio\">Ordenar seg&uacute;n</label><select name=\"orden_criterio\" id=\"orden_criterio\"><option value=\"5\">T&iacute;tulo</option><option value=\"3\">Posici&oacute;n</option><option value=\"4\">Fecha de creaci&oacute;n</option></select>&nbsp;<input type=\"checkbox\" name=\"orden_dir\" id=\"orden_dir\" value=\"1\" /><label for=\"orden_dir\">inverso</label><input type=\"button\" value=\"Aplicar\" onclick=\"ordenPublicacion('{$seccion}', this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].value, this.form['orden_dir'].checked, this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].text);\" /></fieldset></form>

    //$consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, xml_lang, dir $campos FROM `lenguajes` le LEFT JOIN secciones_textos st ON le.leng_id = st.leng_id $condicion LEFT JOIN `secciones` se ON se.seccion_id = st.seccion_id WHERE $lenguajes ORDER BY $orden";


    //$mysqli = BaseDatos::Conectar();
/*********************************************************************/


    $estado_arr = array("Deshabilitado", "Habilitado");
    $clase_estado = array("inactivo", "", "enproceso");

    if($cs_categorias && !$lim_prof) {
       	if($cat_cuenta == ($cs_prof_categorias - 1))
            $plim_prof = true;
        if(!$result = $mysqli->query("SELECT c.id, c.superior, cn.nombre, COUNT(`item_id`), estado_id, bloqueado FROM items_categorias c JOIN items_categorias_nombres cn ON c.id = cn.id AND cn.leng_id = 1 LEFT JOIN `items_a_categorias` iac ON c.id = iac.categoria_id WHERE c.superior = '{$cat}' AND seccion_id = {$seccion_id} GROUP BY c.`id` ORDER BY c.`orden` ASC"))
            die(__LINE__." - ".$mysqli->error);
        if($row_cat = $result->fetch_row()) {
            //if($cat != 0) echo "<div><a href=\"listar?seccion={$seccion_id}&amp;cat=0\">Inicio</a>&nbsp;&gt;&nbsp;".nav_cat($row_cat0[1], NULL)."<b>{$row_cat0[2]}</b></div>";
            echo("
	<table class=\"tabla\">
	 <thead>
	  <tr>");
            // <td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_cats[]');\" /></td>
            echo("
	   <td width=\"480\">Categorías</td>");
            if(!$plim_prof)
                echo("\n <td width=\"30\">Sub&nbsp;cats.</td>");
            echo("\n <td width=\"30\">Items</td>");
            if($_SESSION['permisos']['admin_seccion_c'][$seccion_id] > 1)
                echo("\n <td colspan=\"2\" width=\"40\">Opciones</td>");
            echo("</tr>
	 </thead>
	 <tbody>");
            do {
                $categoria_id = $row_cat[0];
                if(!$resultsc = $mysqli->query("SELECT COUNT(id) AS `Filas` FROM `items_categorias` WHERE `superior` = '{$categoria_id}' AND seccion_id = {$seccion_id} GROUP BY `superior` LIMIT 1"))
                    die(__LINE__." - ".$mysqli->error);
                if($row_sc = $resultsc->fetch_row())
                    $sc = $row_sc[0];
                else
                    $sc = "0";
                echo("
	 <tr class=\"{$clase_estado[$row_cat[4]]}\">");
                // <td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_cats[]\" value=\"{$row_cat[4]}\" onclick=\"selFila(this, '{$clase_estado[$row_cat[4]]}');\" /></td>
                echo("
	  <td><a href=\"listar?seccion={$seccion_id}&amp;cat={$categoria_id}\"><b>[".htmlspecialchars($row_cat[2])."]</b></a></td>");
                if(!$plim_prof)
                    echo("\n	  <td>{$sc}</td>");
                echo("
	  <td>{$row_cat[3]}</td>");
                if($_SESSION['permisos']['admin_seccion_c'][$seccion_id] > 1) {
                    echo("
	  <td style=\"text-align:center;width:20px;\">");
                    if($row_cat[5] != 4)
                        echo("<a href=\"categoria?seccion={$_REQUEST['seccion']}&amp;id={$row_cat[0]}\"><img src=\"/img/b_edit.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Editar\" /></a>");
                    echo("</td>
	  <td style=\"text-align:center;width:20px;\">");
                    if(!$row_cat[5])
                        echo("<a onclick=\"return confBorradoCat('".htmlspecialchars($row_cat[2])."', {$sc}, this);\" href=\"listar?seccion={$_REQUEST['seccion']}&amp;cat={$cat}&amp;eliminarcat={$row_cat[0]}\"><img src=\"./img/b_drop.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Eliminar\" /></a>");
                    else
                        echo("<img src=\"./img/b_dropn.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Eliminar\" />");
                    echo("</td>");
                }
                echo("</tr>");
            }while($row_cat = $result->fetch_row());
            echo("
     </tbody>
	</table>
<br />");
        }
        /*else
         {
          if($cat == "0")
           { echo "<a href=\"listar?ia=agregarcat&amp;p_id={$row_cat[0]}\">Agregar categor&iacute;a</a>"; }
          else
           { echo "<b>Error</b><br />No se encontr&oacute; la categor&iacute;a seleccionada."; }

         }*/
    }

/*********************************************************************/

    $orden = empty($_REQUEST["orden"]) ? 1 : $_REQUEST["orden"];
    $flechas_par = "fld2d7dd";
    $db_criterios_orden = array("i.`orden`", "il.`atributo2`", "il.`atributo3`", "il.`atributo4`");
    include('inc/funciones/ordenar_lista.php');
    extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));
    $bsq_cat = $cat ? "= {$cat}" : "IS NULL";
    $orden_prov = $cat ? "iac" : "i";
    $orden_prov_bool = ($orden_prov == "i");

	if($_GET['n_orden']) {
		$m_orden = explode(",", $_GET['n_orden']);
		if($m_orden[1] < $m_orden[2]) {// se mueve hacia abajo
			if($orden_prov_bool) {
				if(!$mysqli->query("UPDATE items SET orden = (orden - 1) WHERE orden > {$m_orden[1]} AND orden <= {$m_orden[2]} AND seccion_id = {$seccion_id}"))
					echo "\n".__LINE__.": ".$mysqli-error;
			}
			else {
				if(!$mysqli->query("UPDATE items_a_categorias SET orden = (orden - 1) WHERE orden > {$m_orden[1]} AND orden <= {$m_orden[2]} AND categoria_id = {$cat}"))
					echo "\n".__LINE__.": ".$mysqli-error;}
			}
			elseif($m_orden[1] > $m_orden[2]) {// se mueve hacia arriba
				if($orden_prov_bool) {
					if(!$mysqli->query("UPDATE items SET orden = (orden + 1) WHERE orden < {$m_orden[1]} AND orden >= {$m_orden[2]} AND seccion_id = {$seccion_id}"))
						echo "\n".__LINE__.": ".$mysqli-error;
				}
			else {
				if(!$mysqli->query("UPDATE items_a_categorias SET orden = (orden + 1) WHERE orden < {$m_orden[1]} AND orden >= {$m_orden[2]} AND categoria_id = {$cat}"))
					echo "\n".__LINE__.": ".$mysqli-error;
			}
		}
		if($m_orden[1] != $m_orden[2]) {
			if($orden_prov_bool) {
				if(!$mysqli->query("UPDATE items SET orden = {$m_orden[2]} WHERE id = {$m_orden[0]}"))
					echo "\n".__LINE__.": ".$mysqli-error;
			}
			else {
				if(!$mysqli->query("UPDATE items_a_categorias SET orden = {$m_orden[2]} WHERE item_id = {$m_orden[0]} AND categoria_id = {$cat}"))
					echo "\n".__LINE__.": ".$mysqli-error;
			}
		}
	}

	if(!$cons_total = $mysqli->query("SELECT id FROM items i LEFT JOIN items_a_categorias iac ON i.id = iac.item_id WHERE seccion_id = {$_REQUEST['seccion']} AND iac.categoria_id {$bsq_cat}"))
		die (__LINE__.": ".$mysqli->error);
	if($total = $cons_total->num_rows) {
		$a = 20;
		$paginas = ceil($total / $a);
		$pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
		if($pagina > $paginas)
			$pagina = $paginas;
		$desde = ($pagina - 1) * $a;

        /*SELECT iv.item_id, ia.identificador, at.tipo, at.subtipo, iv.string, iv.`date`, iv.`text`, iv.`int`, iv.num FROM items i, (items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1) LEFT JOIN items_valores iv ON ia.id = iv.atributo_id AND iv.leng_id = 1, items_secciones_a_atributos isaa, atributos_tipos at WHERE i.id = iv.item_id AND ia.id = isaa.atributo_id AND isaa.seccion_id = 5 AND isaa.en_listado = 1 AND ia.tipo_id = at.id ORDER BY iv.item_id, isaa.orden*/
        //SELECT * FROM items_atributos ia LEFT JOIN items_secciones_a_atributos isaa ON ia.id = isaa.atributo_id WHERE isaa.seccion_id = 5 AND isaa.en_listado = 1
        //echo $total."<br />SELECT id, estado_id, orden, f_creado, DATE_FORMAT(f_creado, '%e--%Y %H:%i hs.'), DATE_FORMAT(f_creado, '%c'), `orden` IS NULL AS ordennull FROM items WHERE categoria_id = '{$_REQUEST['seccion']}' ORDER BY {$db_orden} LIMIT {$desde}, {$a}";
        //echo "SELECT i.id, i.estado_id, il.imagen, il.atributo1, il.atributo2, il.atributo3, il.atributo4, `orden` IS NULL AS ordennull FROM items i LEFT JOIN items_lista il ON i.id = il.id WHERE i.seccion_id = {$_REQUEST['seccion']} ORDER BY {$db_orden} LIMIT {$desde}, {$a}";
        //SELECT id, estado_id, orden, f_creado, DATE_FORMAT(f_creado, '%e--%Y %H:%i hs.'), DATE_FORMAT(f_creado, '%c'), `orden` IS NULL AS ordennull FROM items WHERE seccion_id = '{$_REQUEST['seccion']}' ORDER BY {$db_orden} LIMIT {$desde}, {$a}

        $attrs_lista = array();
        if(!$consulta_attrs = $mysqli->query("SELECT isaa.atributo_id, ia.tipo_id, ian.atributo FROM items_secciones_a_atributos isaa, items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1 WHERE isaa.atributo_id = ia.id AND isaa.seccion_id = '{$_REQUEST['seccion']}' AND (ia.tipo_id = 1 OR ia.tipo_id = 2) ORDER BY orden")) //8 OR ia.tipo_id = 21
            die(__LINE__."<br />\n".$mysqli->error);
        if($fila_attrs = $consulta_attrs->fetch_row()) {
            $cons_campos = '';
            $abre_parts = '';
            $i = 1;
            do {
                $tipo = $fila_attrs[1];
                if($attrs_lista[$tipo])
                    continue;
                $attrs_lista[$tipo] = array($fila_attrs[0], $fila_attrs[2]);
                if($tipo == 1) {// || $tipo == 21)
                    $cons_campos .= ", iv{$i}.`string`";
                    $abre_parts .= "(";
                    $cons_tablas .= " LEFT JOIN items_valores iv{$i} ON i.id = iv{$i}.item_id AND iv{$i}.atributo_id = {$fila_attrs[0]})";
                }
                else {
                    $cons_campos .= ", im.archivo";
                    $abre_parts .= "((";
                    $cons_tablas .= " LEFT JOIN items_valores iv{$i} ON i.id = iv{$i}.item_id AND iv{$i}.atributo_id = {$fila_attrs[0]}) LEFT JOIN imagenes_orig im ON iv{$i}.`int` = im.id)";
                }
                $i++;
                if(count($attrs_lista) == 2)
                    break;
            }while($fila_attrs = $consulta_attrs->fetch_row());
        }

        $rpp = @include(RUTA_CARPETA.'bng5/datos/rpp'.$seccion_id.'.php');

?>


<script type="text/javascript">
// <![CDATA[
function fijarRPP(formulario) {
	//enviarXHR('/fijarRpp', hand, datos, contenidoTipo, params)
	var span = document.getElementById('fijarRpp_aviso');
	span.removeChild(span.firstChild);
	var imagen = new Image();
	imagen.src = '/img/silk/ajax-loader.gif';
	formulario.appendChild(imagen);
	formulario.elements.namedItem('enviar').disabled = true;
	enviarXHR('/fijarRpp', fijarRPPRespuesta, 'seccion='+formulario.elements.namedItem('seccion').value+'&rpp='+formulario.elements.namedItem('pub_rpp').value, null, {img: imagen, aviso: span, form: formulario});
	return false;
}
function fijarRPPRespuesta(xhr, params) {
	//xhr.sta
	//var document.createTextNode('');
	var mensaje, clase;
	if(xhr.status == 200) {
		mensaje = 'Cambios guardados';
		clase = 'ok';
	}
	else {
		mensaje = 'Error';
		clase = 'error';
	}
	params.aviso.appendChild(document.createTextNode(mensaje));
	params.aviso.className = clase;
	params.img.parentNode.removeChild(params.img);
	params.form.elements.namedItem('enviar').disabled = false;
}
// ]]>
</script>
<?php
/* FIXME Corregir rpp
		 <!-- div>
			 <form method="post" onsubmit="return fijarRPP(this)">
				 <input type="hidden" name="seccion" value="<?php echo $seccion_id ?>" />
				 <label for="pub_rpp">Resultados por página (publicación)</label> <input type="text" size="2" id="pub_rpp" name="pub_rpp" value="<?php echo $rpp ?>" /> <input type="submit" name="enviar" value="Fijar" /> <span id="fijarRpp_aviso"> </span>
			 </form>
		 </div -->
*/

//SELECT isaa.atributo_id, ia.tipo_id FROM items_secciones_a_atributos isaa JOIN items_atributos ia ON isaa.atributo_id = ia.id WHERE isaa.seccion_id = 8 AND (ia.tipo_id = 1 OR ia.tipo_id = 8 OR ia.tipo_id = 21) ORDER BY orden
//echo "SELECT i.id, i.estado_id, im.archivo AS imagen, iv2.`string` AS titulo, i.f_creado, i.f_modificado, i.orden FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = {$bsq_img}) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = ".current($attrs_lista_k)." AND iv2.leng_id = 1) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = {$_REQUEST['seccion']} AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY {$db_orden} LIMIT {$desde}, {$a}";

/*
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden{$cons_campos} FROM {$abre_parts}items i{$cons_tablas} LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- imagen y string
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, im.archivo, iv2.`string` FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 6) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = 7) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- sin atributos
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden FROM items i LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = 5 AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- string no leng
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string` FROM (items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- string
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string` FROM (items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7 AND iv1.leng_id = 1) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- imagen
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, im.archivo FROM ((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 6) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
-- string e imagen
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string`, im.archivo FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = 6) LEFT JOIN imagenes im ON iv2.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT {$desde}, {$a}
*/



        //if(!$consulta = $mysqli->query("SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, {$orden_prov}.orden{$cons_campos}, {$orden_prov}.orden IS NULL AS orden_null, UNIX_TIMESTAMP(i.tiempoedicion), i.usuarioedicion FROM {$abre_parts}items i{$cons_tablas} LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY `orden_null`, {$orden_prov}.`orden` ASC LIMIT {$desde}, {$a}")) die(__LINE__.": ".$mysqli->error);
        if(!$consulta = $mysqli->query("SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, {$orden_prov}.orden{$cons_campos}, {$orden_prov}.orden IS NULL AS orden_null FROM {$abre_parts}items i{$cons_tablas} LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id {$bsq_cat} GROUP BY i.id ORDER BY {$orden_prov}.`orden` ASC LIMIT {$desde}, {$a}"))//ORDER BY `orden_null`,
            die(__LINE__.": ".$mysqli->error);
        if($fila = $consulta->fetch_row()) {
            $tiempo = time();
            echo("		<!-- style=\"\" display:none; -->
		<form action=\"listar?seccion={$_REQUEST['seccion']}&amp;cat={$cat}&amp;pagina={$pagina}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\" style=\"width:auto;\"");// style=\"display:none;\"
	  //		 ><caption><select name=\"\"><option value=\"\"></option></select></caption
	  //		 ><caption><img src=\"/img/pregunta_inactivo\" onmouseover=\"this.src='/img/pregunta_activo';document.getElementById('tablaReferencia').style.display='block'\" onmouseout=\"this.src='/img/pregunta_inactivo';document.getElementById('tablaReferencia').style.display='none'\" alt=\"Referencia\" /></caption
           echo("
		 ><thead
		  ><tr class=\"orden\"
		   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td");
           if(!count($attrs_lista)) {
               echo("
		   ><td>Id</td");
            }
            foreach($attrs_lista AS $attr_nombre) {
                echo("
		   ><td>{$attr_nombre[1]}</td");
            }
            echo("
		   ><td>Creado</td
		   ><td>Modificado</td
		   ><td>Orden</td
		  ></tr
		 ></thead
		 ><tbody");
            //if($fila_attrs = $consulta_attrs->fetch_row())
            $f_orden = $desde;
            $f_consulta = ($orden_prov == "i") ? "UPDATE items SET orden = %d WHERE id = %d" : "UPDATE items_a_categorias SET orden = %d WHERE item_id = %d AND categoria_id = %d";
            do {
                $f_orden++;

                if($f_orden != $fila[4]) {
                    //	{$orden_prov}
                    /*echo "><tr><td colspan=\"6\">";
                    //UPDATE items SET orden = {$f_orden} WHERE id = {$fila[0]}
                    printf($f_consulta, $f_orden, $fila[0], $cat);
                    echo "</td></tr";
                    */
                    $mysqli->query(sprintf($f_consulta, $f_orden, $fila[0], $cat));
                }
                if($tiempo < $fila[6])
                    $fila[1] = 2;
                echo("
		  ><tr class=\"{$clase_estado[$fila[1]]}\"
		   ><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\" onclick=\"selFila(this, '{$clase_estado[$fila[1]]}');\" /></td");
                if(!count($attrs_lista)) {
                    echo("
		   ><td><a href=\"editar?seccion={$seccion_id}&amp;id={$fila[0]}{$items_link}\">{$fila[0]}</a></td");
                }
                else {
                    $n = 5;
                    $linkeado = false;
                    foreach($attrs_lista AS $attrs_lista_k => $attrs_lista_v) {
                        echo("><td>");
                        if($attrs_lista_k == 2) {
                            if($attrs_lista[1])// || $attrs_lista[21])
                                echo("<img src=\"icono/2/{$fila[$n]}\" alt=\"\" />");
                            else {
                                echo("<a href=\"editar?seccion={$seccion_id}&amp;id={$fila[0]}{$items_link}\"><img src=\"icono/2/{$fila[$n]}\" alt=\"\" /></a>");
                                $linkeado = true;
                            }
                        }
                        else {
                            $txt = $fila[$n] ? htmlspecialchars($fila[$n]) : "id: {$fila[0]}";
                            if(!$linkeado) {
                                echo("<a href=\"editar?seccion={$seccion_id}&amp;id={$fila[0]}{$items_link}\">{$txt}</a>");
                                $linkeado = true;
                            }
                            else
                                echo($txt);
                        }
                    $n++;
                    echo("</td");
                }
            }
            //if($hayimg) echo "\n		   ><td><img src=\"icono/imagenesChicas/{$fila[2]}\" alt=\"\" /></td";
            $creado = new DateTime($fila[2]);
            $modificado = new DateTime($fila[3]);
            echo("
		   ><td>".$creado->format("d-m-Y G:i")." hs.</td
		   ><td>".$modificado->format("d-m-Y G:i")." hs.</td
		   ><td><input type=\"text\" value=\"{$f_orden}\" size=\"3\" /><img src=\"/img/flecha_bt\" onclick=\"document.location.href='listar?seccion={$seccion_id}{$items_link}&amp;pagina={$pagina}&amp;n_orden={$fila[0]},{$f_orden},'+this.previousSibling.value\" alt=\"\" /></td
		   ></tr");
            }while($fila = $consulta->fetch_row());
        echo("
		 ></tbody
		></table>");

        echo('
  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ningún item.</div>
  <div id="listado_opciones" style="padding:4px;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado(\'lista_item[]\');" /></div>
  <div id="listado_result"></div>
  <div>Total: '.$total.'</div>
  <div id="paginador">Páginas:');

        if($pagina > 1)
            echo("<a href=\"listar?seccion={$seccion_id}&amp;pagina=".($pagina - 1)."{$items_link}\">Anterior</a> ");
        for($p = 1; $p <= $paginas; $p++)
            echo(($p == $pagina) ? "<b>{$p}</b> " : "<a href=\"listar?seccion={$seccion_id}&amp;pagina={$p}{$items_link}\">{$p}</a> ");
        if($pagina < $paginas)
            echo("<a href=\"listar?seccion={$seccion_id}&amp;pagina=".($pagina + 1)."{$items_link}\">Siguiente</a> ");

        echo('</div>
	 </form>');
    }

    echo('
		<table class="tabla" id="tablaReferencia" style="width:auto;position:absolute;display:none;">
		 <thead>
		  <tr class="orden">
		   <td>Referencia</td>
		  </tr>
		 </thead>
		 <tbody>
		  <tr class="sel_fila">
		   <td>Seleccionado</td></tr>
		  <tr>
		   <td>Publicado</td></tr>
		  <tr class="inactivo">
		   <td>No publicado</td></tr>
		  <tr class="enproceso">
		   <td>Siendo editado</td></tr>
		  <tr class="actual">
		   <td>actual</td></tr>
		  <tr class="nofinaliz">
		   <td>nofinaliz</td></tr>
		  <tr class="sinverificar">
		   <td>sinverificar</td></tr>
		  <tr class="suspendido">
		   <td>suspendido</td></tr>
		 </tbody>
		</table>');

    /*
    <script type="text/javascript" defer="defer">
    //<![CDATA[
    / *
    var tablaListado = document.getElementById('tablaListado');
    var listadoOpciones = document.getElementById('listado_opciones');
    var listadoResult = document.getElementById('listado_result');
    var orden = 5;
    var CAT = '<?php echo $cat; ?>';
    var CAT_SUP = '<?php echo $cat_superior; ?>';
    loadXMLDoc('./galeria.xml?leng=<?php echo "{$leng}&cat={$cat}&cat_sup={$cat_superior}"; ?>', cargarListado, null);
    * /
    //]]>
    </script>
    */


    }
    else
        echo("No se encontró ningún item.");
    $vista->html(ob_get_contents());
    ob_end_clean();
    $vista->mostrar();
    //include('inc/iapie.php');
}
else
	include('./error/404.php');

?>