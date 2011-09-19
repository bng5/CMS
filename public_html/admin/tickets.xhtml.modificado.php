<?php

//header("Content-type: application/xhtml+xml; charset=utf-8");
$seccion_id = 8;
$seccion = "tickets";
$mod = "tickets";
$titulo = "Tickets de soporte";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();
require('inc/ad_sesiones.php');


//sprintf("%03d", $pr_id);


  // modificar
  if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
   {
    $modificar = $_POST['lista_item'];
    $modificadas = 0;

    // borrar
    if($_POST['mult_submit'] == "Eliminar completamente")
     {
      $modificacion_tipo_accion = "eliminadas";
      $borrar = new Item_borrar($seccion);
      for($i = 0; $i < count($modificar); $i++) $borrar->Item($modificar[$i], true);
      $modificadas = $borrar->modificadas;
     }

    // habilitar
    elseif($_POST['mult_submit'] == "Publicar")
     {
      $modificacion_tipo_accion = "publicados";
	  $publicar = new Item_publicar($seccion);
      for($i = 0; $i < count($modificar); $i++)
       {
        $publicar->Item($modificar[$i]);
	    $mysqli->query("UPDATE `items` SET `estado_id` = '1' WHERE `id` = '{$modificar[$i]}' LIMIT 1");
       }
      $modificadas = $publicar->modificadas;
     }
    // deshabilitar
    elseif($_POST['mult_submit'] == "Eliminar publicación")
     {
      $modificacion_tipo_accion = "eliminadas de la publicaci&oacute;n";
      $borrar = new Item_borrar($seccion);
      for($i = 0; $i < count($modificar); $i++) $borrar->Item($modificar[$i]);
      $modificadas = $borrar->modificadas;
     }
    if($modificadas > 0)
     {
      $div_mensaje = "Items ".$modificacion_tipo_accion.": ".$modificadas;
      //tabla_informacion("Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas);
     }
   }
  // fin modificar

  if(!empty($_GET['eliminarcat']))
   {
   	$mysqli->query("DELETE FROM items_categorias WHERE id = '{$_GET['eliminarcat']}' AND seccion_id = ${seccion_id} LIMIT 1");
   	if($mysqli->affected_rows == 1)
   	 {
	  $mysqli->query("DELETE FROM items_categorias_nombres WHERE id = '{$_GET['eliminarcat']}'");
	  $mysqli->query("DELETE FROM items_a_categorias WHERE categoria_id = '{$_GET['eliminarcat']}'");
	  $mysqli->query("DELETE FROM categorias_valores WHERE categoria_id = '{$_GET['eliminarcat']}'");
	  $mysqli->query("DELETE FROM pubcats__${seccion} WHERE id = '{$_GET['eliminarcat']}'");
	  $div_mensaje = "Se ha borrado la categoría.";
	 }
   }
  //$cat_superior = current($secciones->actual_superior);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<style type="text/css">

fieldset {
	margin: 1.5em 0;
	padding:0;
/*	float:left;
	clear:left;
	width:100%;*/
}
legend {
	margin-left: 1em;
	color: #000000;
	font-weight: bold;
}

label {
	float: left;
	width: 10em;
	margin-right: 1em;
}

label.opcion {
	float:none;
	width:auto;
	margin-right: 1em;
}

sup.required {
	color:red;
}
/*
fieldset ol {
	padding: 1em 1em 0 1em;
	list-style-type: none;
}
*/
fieldset li {
	padding-bottom: 1em;
	list-style-type: none;
	float: left;
	clear: left;
	width: 100%;
	margin-left:1em;
}

fieldset.submit {
	border-style: none;
}

#resumen, textarea {
	width:40em;
}

ul#navegadorInfo li input {
	width:39em;
}
label.sublista {
	width:auto;
	padding-left:15px;
	background-image:url(/img/c);
	background-position:center left;
	background-repeat:no-repeat;

}
ul#navegadorInfo {
	margin-top:2em;
}



</style>







<?php




  include('inc/iaencab.php');
?>









<form name="reporte" method="post" enctype="multipart/form-data" action="/tickets_guardar" onsubmit="return validarFormTicket(this)">
 <input type="hidden" name="ia" value="modificar" />
 <!-- input type="hidden" name="id" value="" / -->
<fieldset>
 <legend>Nuevo ticket</legend>
  <ul>
   <li><label>Código</label> <span>CÓDIGO</span></li>
<?php

  $consulta_cats = $mysqli->query("SELECT id, nombre FROM tickets_categorias ORDER BY id");//) die(__LINE__."<br />\n".$mysqli->error);
  if($fila = $consulta_cats->fetch_row())
   {
	echo "   <li><label for=\"tipo\">Categoría <sup class=\"requerido\">*</sup></label> <select tabindex=\"1\" name=\"tipo\" id=\"tipo\"><option value=\"0\" selected=\"selected\">(seleccionar)</option>";
   	do
   	 {
	  echo "<option value=\"{$fila[0]}\">{$fila[1]}</option>";
   	 }while($fila = $consulta_cats->fetch_row());
   	echo "</select></li>";
   }

?>
   <li><label for="reproducibilidad">Reproducibilidad</label> <select tabindex="2" name="reproducibilidad" id="reproducibilidad"><option value="1">siempre</option><option value="2">a veces</option><option value="3">aleatorio</option><option value="4" selected="selected">no se ha intentado</option><option value="5">no reproducible</option><option value="0">desconocido</option></select></li>
   <li><label for="severidad">Severidad</label> <select tabindex="3" name="severidad" id="severidad"><option value="1">funcionalidad</option><option value="2">trivial</option><option value="3">texto</option><option value="4">ajuste</option><option value="5" selected="selected" >menor</option><option value="6">mayor</option><option value="7">fallo</option><option value="8">bloqueo</option></select></li>
   <li><label for="resumen">Resumen <sup class="requerido">*</sup></label> <input tabindex="9" type="text" name="resumen" id="resumen" size="80" maxlength="128" value="" /></li>
   <li><label for="descripcion">Descripción <sup class="requerido">*</sup></label> <textarea tabindex="10" name="descripcion" id="descripcion" cols="80" rows="10"></textarea></li>
   <li><label for="pasos_reproducirlo">Pasos para reproducirlo</label> <textarea tabindex="11" name="pasos_reproducirlo" id="pasos_reproducirlo" cols="80" rows="10"></textarea></li>
   <li><label for="info_adicional">Información Adicional</label> <textarea tabindex="12" name="info_adicional" id="info_adicional" cols="80" rows="10"></textarea></li>
   <li><label for="archivo">Subir Archivo <small class="small">(Tamaño Máximo: 8&nbsp;MB)</small></label> <input tabindex="13" name="archivo" id="archivo" type="file" /></li>
   <li><label class="sublista" onclick="mostrar_subLista(this)">Información de su navegador</label><ul id="navegadorInfo">
<?php

/*
$cabeceras = apache_request_headers();
foreach($cabeceras AS $c_k => $c_v)
 {
  echo "<li><label for=\"${c_k}\">${c_k}</label> <input type=\"text\" name=\"cabeceras[${c_k}]\" id=\"${c_k}\" value=\"${c_v}\" readonly=\"readonly\" />\n";
 }
*/
$patron = '/^HTTP_(.*)$/';
foreach($_SERVER AS $s_k => $s_v)
 {
  if(!preg_match($patron, $s_k, $coincidencias)) continue;
  $s_k = ucwords(strtolower($coincidencias[1]));
  echo "<li><label for=\"${s_k}\">${s_k}</label> <input type=\"text\" name=\"cabeceras[${s_k}]\" id=\"${s_k}\" value=\"".htmlspecialchars($s_v)."\" readonly=\"readonly\" />\n";
 }

?>
	 <li><label for="javascriptHab">JavaScript habilitado</label> <input type="text" name="javascript[habilitado]" id="javascriptHab" readonly="readonly" value="No" /></li></ul></li>
   <li><label>Visibilidad</label> <input tabindex="14" type="radio" name="visibilidad" id="visibilidad1" value="1" checked="checked" /> <label for="visibilidad1" class="opcion">Público</label> <input tabindex="15" type="radio" name="visibilidad" id="visibilidad0" value="50" /> <label for="visibilidad0" class="opcion">Privado</label></li>
   <li><span class="requerido"><sup>*</sup> Requerido</span></li>
   <li><input tabindex="17" type="submit" class="button" value="Enviar Reporte" /></li>
  </ul>
</fieldset>
</form>

<?php

















  if($cs_info) echo "  <div><a href=\"".APU."info?seccion={$_REQUEST['seccion']}\">Editar sección</a></div>";
  echo "  <div><a href=\"".APU."editar?seccion={$_REQUEST['seccion']}&amp;cat={$_REQUEST['cat']}\">Agregar item</a>";
  if($cs_categorias && !$lim_prof && $_SESSION['permisos']['admin_seccion_c'][$seccion_id] > 1)
	echo " - <a href=\"".APU."categoria?seccion={$_REQUEST['seccion']}&amp;cat=${cat}\">Agregar categoría</a>";

  echo "</div>";
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 5)
   {
   	echo "<div><a href=\"".APU."configuracion?seccion=${seccion_id}\">Configuraci&oacute;n de items</a>";
   	if($cs_categorias) echo " - <a href=\"".APU."configuracion_c?seccion=${seccion_id}\">Configuraci&oacute;n de categorías</a>";
   	echo "</div>\n";
   }
  //echo "<fieldset><legend>Aviso</legend>Todas las modificaciones que se realizen en este listado afectan directamente a la publicaci&oacute;n.</fieldset>";
  //echo "<form action=\"#\" name=\"orden\"><fieldset><legend>Orden de publicaci&oacute;n</legend><label for=\"orden_criterio\">Ordenar seg&uacute;n</label><select name=\"orden_criterio\" id=\"orden_criterio\"><option value=\"5\">T&iacute;tulo</option><option value=\"3\">Posici&oacute;n</option><option value=\"4\">Fecha de creaci&oacute;n</option></select>&nbsp;<input type=\"checkbox\" name=\"orden_dir\" id=\"orden_dir\" value=\"1\" /><label for=\"orden_dir\">inverso</label><input type=\"button\" value=\"Aplicar\" onclick=\"ordenPublicacion('${seccion}', this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].value, this.form['orden_dir'].checked, this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].text);\" /></fieldset></form>

  //$consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, xml_lang, dir $campos FROM `lenguajes` le LEFT JOIN secciones_textos st ON le.leng_id = st.leng_id $condicion LEFT JOIN `secciones` se ON se.seccion_id = st.seccion_id WHERE $lenguajes ORDER BY $orden";


  //$mysqli = BaseDatos::Conectar();
/*********************************************************************/


  $estado_arr = array("Deshabilitado", "Habilitado");
  $clase_estado = array("inactivo", "", "enproceso");



  $orden = empty($_REQUEST["orden"]) ? 1 : $_REQUEST["orden"];
  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("i.`orden`", "il.`atributo2`", "il.`atributo3`", "il.`atributo4`");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));
  $bsq_cat = $cat ? "= {$cat}" : "IS NULL";
  $orden_prov = $cat ? "iac" : "i";
  $orden_prov_bool = ($orden_prov == "i");


  //if(!$cons_total = $mysqli->query("SELECT id FROM items i LEFT JOIN items_a_categorias iac ON i.id = iac.item_id WHERE seccion_id = {$_REQUEST['seccion']} AND iac.categoria_id ${bsq_cat}")) die (__LINE__.": ".$mysqli->error);
  if($total = $cons_total->num_rows)
   {
	$a = 20;
	$paginas = ceil($total / $a);
	$pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
	if($pagina > $paginas) $pagina = $paginas;
	$desde = ($pagina - 1) * $a;

	/*SELECT iv.item_id, ia.identificador, at.tipo, at.subtipo, iv.string, iv.`date`, iv.`text`, iv.`int`, iv.num FROM items i, (items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1) LEFT JOIN items_valores iv ON ia.id = iv.atributo_id AND iv.leng_id = 1, items_secciones_a_atributos isaa, atributos_tipos at WHERE i.id = iv.item_id AND ia.id = isaa.atributo_id AND isaa.seccion_id = 5 AND isaa.en_listado = 1 AND ia.tipo_id = at.id ORDER BY iv.item_id, isaa.orden*/
	//SELECT * FROM items_atributos ia LEFT JOIN items_secciones_a_atributos isaa ON ia.id = isaa.atributo_id WHERE isaa.seccion_id = 5 AND isaa.en_listado = 1
	//echo $total."<br />SELECT id, estado_id, orden, f_creado, DATE_FORMAT(f_creado, '%e--%Y %H:%i hs.'), DATE_FORMAT(f_creado, '%c'), `orden` IS NULL AS ordennull FROM items WHERE categoria_id = '{$_REQUEST['seccion']}' ORDER BY ${db_orden} LIMIT ${desde}, ${a}";
	//echo "SELECT i.id, i.estado_id, il.imagen, il.atributo1, il.atributo2, il.atributo3, il.atributo4, `orden` IS NULL AS ordennull FROM items i LEFT JOIN items_lista il ON i.id = il.id WHERE i.seccion_id = {$_REQUEST['seccion']} ORDER BY ${db_orden} LIMIT ${desde}, ${a}";
	//SELECT id, estado_id, orden, f_creado, DATE_FORMAT(f_creado, '%e--%Y %H:%i hs.'), DATE_FORMAT(f_creado, '%c'), `orden` IS NULL AS ordennull FROM items WHERE seccion_id = '{$_REQUEST['seccion']}' ORDER BY ${db_orden} LIMIT ${desde}, ${a}

	$attrs_lista = array();
	if(!$consulta_attrs = $mysqli->query("SELECT isaa.atributo_id, ia.tipo_id, ian.atributo FROM items_secciones_a_atributos isaa, items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1 WHERE isaa.atributo_id = ia.id AND isaa.seccion_id = '{$_REQUEST['seccion']}' AND (ia.tipo_id = 1 OR ia.tipo_id = 8 OR ia.tipo_id = 21) ORDER BY orden")) die(__LINE__."<br />\n".$mysqli->error);
	if($fila_attrs = $consulta_attrs->fetch_row())
	 {
	  $cons_campos = '';
	  $abre_parts = '';
	  $i = 1;
	  do
	   {
		$tipo = $fila_attrs[1];
		if($attrs_lista[$tipo]) continue;
		$attrs_lista[$tipo] = array($fila_attrs[0], $fila_attrs[2]);
		if($tipo == 1 || $tipo == 21)
		 {
		  $cons_campos .= ", iv${i}.`string`";
		  $abre_parts .= "(";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]})";
		 }
		else
		 {
		  $cons_campos .= ", im.archivo";
		  $abre_parts .= "((";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]}) LEFT JOIN imagenes_orig im ON iv${i}.`int` = im.id)";
		 }
		$i++;
		if(count($attrs_lista) == 2) break;
	   }while($fila_attrs = $consulta_attrs->fetch_row());
	 }

//SELECT isaa.atributo_id, ia.tipo_id FROM items_secciones_a_atributos isaa JOIN items_atributos ia ON isaa.atributo_id = ia.id WHERE isaa.seccion_id = 8 AND (ia.tipo_id = 1 OR ia.tipo_id = 8 OR ia.tipo_id = 21) ORDER BY orden
//echo "SELECT i.id, i.estado_id, im.archivo AS imagen, iv2.`string` AS titulo, i.f_creado, i.f_modificado, i.orden FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = ${bsq_img}) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = ".current($attrs_lista_k)." AND iv2.leng_id = 1) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = {$_REQUEST['seccion']} AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY ${db_orden} LIMIT ${desde}, ${a}";

/*
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden${cons_campos} FROM ${abre_parts}items i${cons_tablas} LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- imagen y string
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, im.archivo, iv2.`string` FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 6) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = 7) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- sin atributos
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden FROM items i LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = 5 AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- string no leng
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string` FROM (items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- string
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string` FROM (items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7 AND iv1.leng_id = 1) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- imagen
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, im.archivo FROM ((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 6) LEFT JOIN imagenes im ON iv1.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
-- string e imagen
SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, i.orden, iv1.`string`, im.archivo FROM (((items i LEFT JOIN items_valores iv1 ON i.id = iv1.item_id AND iv1.atributo_id = 7) LEFT JOIN items_valores iv2 ON i.id = iv2.item_id AND iv2.atributo_id = 6) LEFT JOIN imagenes im ON iv2.`int` = im.id) LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY i.`orden` ASC LIMIT ${desde}, ${a}
*/

	if(!$consulta = $mysqli->query("SELECT i.id, i.estado_id, i.f_creado, i.f_modificado, ${orden_prov}.orden${cons_campos}, ${orden_prov}.orden IS NULL AS orden_null FROM ${abre_parts}items i${cons_tablas} LEFT JOIN items_a_categorias iac ON iac.item_id = i.id WHERE i.seccion_id = '{$_REQUEST['seccion']}' AND iac.categoria_id ${bsq_cat} GROUP BY i.id ORDER BY `orden_null`, ${orden_prov}.`orden` ASC LIMIT ${desde}, ${a}")) die(__LINE__.": ".$mysqli->error);
	if($fila = $consulta->fetch_row())
	 {
	  echo "		<!-- style=\"\" display:none; -->
		<form action=\"/listar?seccion={$_REQUEST['seccion']}&amp;cat=${cat}&amp;pagina=${pagina}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\" style=\"width:auto;\"";// style=\"display:none;\"
	  //		 ><caption><select name=\"\"><option value=\"\"></option></select></caption
	  echo "
		 ><thead
		  ><tr class=\"orden\"
		   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td";
	  if(!count($attrs_lista))
	   {
	   	echo "
		   ><td>Id</td";
	   }
	  foreach($attrs_lista AS $attr_nombre)
	   {
		echo "
		   ><td>{$attr_nombre[1]}</td";
	   }
	  echo "
		   ><td>Creado</td
		   ><td>Modificado</td
		   ><td>Orden</td
		  ></tr
		 ></thead
		 ><tbody";
		//if($fila_attrs = $consulta_attrs->fetch_row())
	  $f_orden = $desde;
	  $f_consulta = ($orden_prov == "i") ? "UPDATE items SET orden = %d WHERE id = %d" : "UPDATE items_a_categorias SET orden = %d WHERE item_id = %d AND categoria_id = %d";
	  do
       {
		$f_orden++;

		if($f_orden != $fila[4])
		 {
		 //	${orden_prov}
		  /*echo "><tr><td colspan=\"6\">";
		  //UPDATE items SET orden = ${f_orden} WHERE id = {$fila[0]}
		  printf($f_consulta, $f_orden, $fila[0], $cat);
		  echo "</td></tr";
		  */
		  $mysqli->query(sprintf($f_consulta, $f_orden, $fila[0], $cat));
		 }
		echo "
		  ><tr class=\"{$clase_estado[$fila[1]]}\"
		   ><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\" onclick=\"selFila(this, '{$clase_estado[$fila[1]]}');\" /></td";
		if(!count($attrs_lista))
		 {
		  echo "
		   ><td><a href=\"/editar?seccion=${seccion_id}&amp;id={$fila[0]}{$items_link}\">{$fila[0]}</a></td";
		 }
		else
		 {
		  $n = 5;
		  $linkeado = false;
		  foreach($attrs_lista AS $attrs_lista_k => $attrs_lista_v)
		   {
		   	echo "><td>";
		   	if($attrs_lista_k == 8)
		   	 {
		   	  if($attrs_lista[1] || $attrs_lista[21]) echo "<img src=\"icono/2/{$fila[$n]}\" alt=\"\" />";
		   	  else
		   	   {
		   	   	echo "<a href=\"/editar?seccion=${seccion_id}&amp;id={$fila[0]}{$items_link}\"><img src=\"icono/2/{$fila[$n]}\" alt=\"\" /></a>";
		   	   	$linkeado = true;
		   	   }
		   	 }
		   	else
		   	 {
		   	  $txt = $fila[$n] ? htmlspecialchars($fila[$n]) : "id: {$fila[0]}";
		   	  if(!$linkeado)
		   	   {
		   	   	echo "<a href=\"/editar?seccion=${seccion_id}&amp;id={$fila[0]}{$items_link}\">${txt}</a>";
		   	   	$linkeado = true;
		   	   }
		   	  else echo $txt;
		   	 }
		    $n++;
			echo "</td";
		   }
		 }
		//if($hayimg) echo "\n		   ><td><img src=\"icono/imagenesChicas/{$fila[2]}\" alt=\"\" /></td";
		echo "
		   ><td>{$fila[2]}</td
		   ><td>{$fila[3]}</td
		   ><td><input type=\"text\" value=\"{$f_orden}\" size=\"3\" /><img src=\"/img/flecha_bt\" onclick=\"document.location.href='/listar?seccion=${seccion_id}{$items_link}&amp;pagina=${pagina}&amp;n_orden={$fila[0]},{$f_orden},'+this.previousSibling.value\" alt=\"\" /></td
		   ></tr";
	   }while($fila = $consulta->fetch_row());
	  echo "
		 ></tbody
		></table>";

?>

  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ningún item.</div>
  <div id="listado_opciones" style="padding:4px;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado('lista_item[]');" /></div>
  <div id="listado_result"></div>
  <div>Total: <?php echo $total ?></div>
  <div id="paginador">Páginas:
<?php

	if($pagina > 1) echo "<a href=\"/listar?seccion=${seccion_id}&amp;pagina=".($pagina - 1)."{$items_link}\">Anterior</a> ";
	for($p = 1; $p <= $paginas; $p++)
	  echo ($p == $pagina) ? "<b>${p}</b> " : "<a href=\"/listar?seccion=${seccion_id}&amp;pagina=${p}{$items_link}\">${p}</a> ";
	if($pagina < $paginas) echo "<a href=\"/listar?seccion=${seccion_id}&amp;pagina=".($pagina + 1)."{$items_link}\">Siguiente</a> ";

?></div>
	 </form>
<?php

     }
?>

<!-- script type="text/javascript" defer="defer">
//<![CDATA[
/*
var tablaListado = document.getElementById('tablaListado');
var listadoOpciones = document.getElementById('listado_opciones');
var listadoResult = document.getElementById('listado_result');
var orden = 5;
var CAT = '<?php echo $cat; ?>';
var CAT_SUP = '<?php echo $cat_superior; ?>';
loadXMLDoc('./galeria.xml?leng=<?php echo "${leng}&cat=${cat}&cat_sup=${cat_superior}"; ?>', cargarListado, null);
*/
//]]>
</script -->

<?php

   }
  else echo "No se encontró ningún item.";
  include('inc/iapie.php');

?>