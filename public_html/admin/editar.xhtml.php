<?php
//header("Content-Type: text/plain");
$mod = "listar";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();

//$seccion = Secciones::obtenerPorId((int) $_REQUEST['seccion']);
$seccion = DB_Secciones::obtenerSeccion(1, array('id' => (int) $_REQUEST['seccion']));

if($seccion && $seccion->link_cms == 'listar') { //$fila_seccion = $cons_seccion->fetch_row())
	$seccion_id = $seccion->id;
	$titulo = $seccion->getNombre($_SESSION['leng_id']);
	$seccion_identificador = "editar";
	$t_categorias = $seccion->categorias;
	//$secciones = new adminsecciones();
	require('inc/ad_sesiones.php');

	$vista = new VistaAdmin_Documento($seccion);

	$vista->agregarCSS('calendario');
	$vista->agregarJS('editar', 'calendar', 'calendar_es-uy', 'calendar-setup');

// agregar / editar
//if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
// {
	$no_poromision = TRUE;
	$transaccion = "Agregar";


	if($idiomas_listado = Idiomas::Listado($_SESSION['leng_id'])) {
		$lengs_tot = $idiomas_listado->total;
		foreach($idiomas_listado->getIterator() AS $item) {
			$leng_id = $item->id;
			$idiomas_id_arr[] = $leng_id;
			$idiomas_arr[$leng_id] = $item->nombre ? $item->nombre : $item->nombre_nativo;
			$idiomas_xmllang_arr[$leng_id] = $item->codigo;
			$idiomas_dir_arr[$leng_id] = $item->dir;
		}//while($fila_lengs = $consulta_lengs->fetch_row());
		//$consulta_lengs->close();
	}

	if(!empty($_REQUEST['id'])) {
		//$sel_idioma = $_REQUEST['leng'];
		if(!$consulta_item = $mysqli->query("SELECT seccion_id, estado_id, superior_id, orden, f_creado FROM `items` WHERE id = '".$_REQUEST['id']."'"))
			die("\n".__LINE__." mySql: ".$mysqli->error);
		if($fila_item = $consulta_item->fetch_assoc()) {
			$transaccion = "Editar";
			$estado = $fila_item['estado_id'];
			//$texto = $fila_item['texto'];
			//$miniatura = $fila_item['miniatura'];
			$creada = $fila_item['f_creado'];
			//$nombre = $fila_item['titulo'];
			//$cat = $fila_item['categoria_id'];
			//$secciones->rearmado($cat);
			$seccion_id = $fila_item['seccion_id'];
			$superior_id = $fila_item['superior_id'];
			$consulta_item->close();
			//$leng = $_REQUEST['leng'];
			$id = $_REQUEST['id'];

			$valores = array();
			if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, `date`, `text`, `int`, `num`, leng_id FROM items_valores WHERE item_id = '{$id}'"))
				echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_row()) {
				do {
					$valor = $fila_valores[0];
					if($fila_valores[7])
						$valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
					else
						$valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
				}while($fila_valores = $cons_valores->fetch_row());
				$cons_valores->close();
			}

			/*
			if(!$consulta_atts = $mysqli->query("SELECT atributo_id, texto FROM `galerias_info` gi JOIN galerias_valores_t gvt ON gi.valor_id = gvt.valor_id WHERE gi.galeria_id = '".$_REQUEST['id']."' AND gvt.leng_id = '{$leng_id}'")) die("\n".__LINE__." mySql: ".$mysqli->error);
			if($fila_atts = $consulta_atts->fetch_row())
			{
			do
			{
			$atributos[$fila_atts[0]] = $fila_atts[1];
			}while($fila_atts = $consulta_atts->fetch_row());
			$consulta_atts->close();
			}
			*/
		}
	}

  //include('./vistas/iaencab.php');
  //$seccion = $fila_seccion[2];

//echo date("Y-m-d H:i:s", time()+480);
//echo "<br />\ndate(\"Y-m-d H:i:s\", ".time()."+480)<br />\n";

	ob_start();

	echo("<div>".(($estado > 0) ? "Publicado" : "No publicado")."</div>");
	if($superior_id)
		echo("<p><b>Artículo</b>: <a href=\"http://".DOMINIO."/item?id={$superior_id}\" target=\"_blank\">".DOMINIO."/item?id={$superior_id}</a></p>");
	function nav_cat($p_id, $nav_cat) {
		global $mysqli, $seccion_id;
		if(!$resultcat = $mysqli->query("SELECT c.id, c.superior, cn.nombre FROM items_categorias c LEFT JOIN items_categorias_nombres cn ON c.id = cn.id AND cn.leng_id = 1 WHERE c.id = '{$p_id}' LIMIT 1"))
			die(__LINE__." - ".$mysqli->error);
		if($row_cat = $resultcat->fetch_row()) {
			$nombre = $row_cat[2] ? $row_cat[2] : "id: {$row_cat[0]}";
			$nav_cat = nav_cat($row_cat[1], $nav_cat)."<a href=\"listar?seccion={$seccion_id}&amp;cat={$row_cat[0]}\">".htmlspecialchars($nombre)."</a>&nbsp;&gt;&nbsp;";
		}
		return $nav_cat;
	}
	echo("<div><a href=\"listar?seccion={$seccion_id}&amp;cat=0\">Inicio</a>&nbsp;&gt;&nbsp;".nav_cat($_GET['cat'], NULL)."{$transaccion} item</div>");

	//if(is_array($secciones->secciones[$seccion_id])) $subcategorias = $secciones->secciones[$seccion_id];
	//elseif($secciones->superior[$seccion_id] != 0) $subcategorias = $secciones->secciones[$secciones->superior[$seccion_id]];

	$atributos = array();
	$atributos_all = array();
	//echo "SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra, isaa.superior, ia.formato FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = {$_SESSION['leng_id']}, atributos_tipos at, items_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND seccion_id = '{$seccion_id}' ORDER BY orden";
	if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, ia.tipo_id, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra, isaa.superior FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = ".$_SESSION['leng_id'].", items_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.id = isaa.atributo_id AND seccion_id = '".$seccion_id."' ORDER BY orden"))
		throw new Exception($mysqli->error, $mysqli->errno);
	//$atributos_all = $atributos_tipos->fetch_all(MYSQLI_ASSOC);
	if($fila_at = $atributos_tipos->fetch_assoc()) {
		do {
			$atributos_all[] = $fila_at;
			$attr_id = array_shift($fila_at);
			$atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
		}while($fila_at = $atributos_tipos->fetch_assoc());
		$atributos_tipos->close();
	}

	echo("
	<form name=\"formedicion\" id=\"formedicion\" action=\"editar_guardar?seccion={$seccion_id}&amp;cat={$_REQUEST['cat']}\" method=\"post\">
	 <input type=\"hidden\" name=\"tipo\" value=\"i\" />
	 <input type=\"hidden\" name=\"id\" value=\"{$id}\" />
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"publicar\" value=\"0\" />
	 <input type=\"hidden\" name=\"seccion\" value=\"{$seccion_id}\" />
	 <input type=\"hidden\" name=\"leng[]\" value=\"{$leng}\" />\n");

	function subcategoria($subcat, $n, $seleccionado = false, $exclusion = false) {
		global $mysqli, $seccion_id;
		$separador_niv = "&nbsp;&nbsp;&nbsp;&nbsp;";
		if($exclusion) {
			$excluir = "c.`id` != '$exclusion' AND";
		}

		if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE {$excluir} `superior` = '{$subcat}' AND seccion_id = {$seccion_id} ORDER BY cn.`nombre`"))
			die(__LINE__." - ".$mysqli->error);
		if($row_subcat = $tbsubcat->fetch_row()) {
			$i = 0;
			do {
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

	if($t_categorias) {
		echo("<fieldset><legend>Categorías</legend>");
		if(@include('iacache/categorias_'.$seccion_id.'.php')) {
			echo("<input type=\"button\" onclick=\"asignarCat(this.previousSibling.options[this.previousSibling.selectedIndex].value)\" value=\"Asignar\" /><ul id=\"as_categorias\"></ul>
<script type=\"text/javascript\">");
		if($id) {
			if(!$resultcats = $mysqli->query("SELECT categoria_id, orden FROM items_a_categorias WHERE item_id = {$id}"))
				die(__LINE__." - ".$mysqli->error);
			if($row_cats = $resultcats->fetch_row()) {
				do {
					echo("\nasignarCat({$row_cats[0]}, ".($row_cats[1] ? $row_cats[1] : 'null').");");
				}while($row_cats = $resultcats->fetch_row());
			}
		}
		elseif(!empty($_REQUEST['cat']))
			echo("\nasignarCat({$_REQUEST['cat']}, null);");
		echo("
</script>\n");
		}
		else
			echo("<p>No hay categorías disponibles.</p>");
		echo("</fieldset>");
	}

	$vista->html(ob_get_contents());
	ob_clean();
	$form = new VistaAdmin_Form();
	$vista->agregarComponente($form);
	$form->thead = '<thead>
	  <tr>
	   <td colspan="2">'.$transaccion.' item</td></tr>
	 </thead>';

	if(count($atributos_all)) {
		/* <?php / * this.value='Guardando...'" * / ?> */
		/* <?php / * onclick="aceptarFormGal(document.forms['formedicion'], true);this.value='Guardando/Publicando...'" * / ?> */
		$form->tfoot = '<tfoot>
	  <tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="/listar?seccion='.$seccion_id.'&amp;cat='.$cat.'">Regresar</a></div></td></tr>
	  <tr>
	   <td colspan="2" style="text-align:center;"><input type="button" value="Cancelar" onclick="document.location.href=\'listar?seccion='.$seccion_id.'&amp;cat=\'" />&nbsp;&nbsp;<input type="submit" name="btGuardar" id="guardar" value="Guardar" onclick="return aceptarFormGal(this.form, false);"     />&nbsp;&nbsp;<input type="submit" name="btPublicar" value="Guardar/Publicar" onclick="return aceptarFormGal(this.form, true);"     />';

		if($id && $estado > 0)
			$form->tfoot .= '&nbsp;&nbsp;<input type="button" name="btElimPublic" value="Eliminar Publicaci&oacute;n" onclick="eliminarPublicacion('.$seccion_id.', \''.$seccion->identificador.'\');" />';
		$form->tfoot .= '</td></tr>
	</tfoot>';


		/**********************************************************************
		*
		* agregear cache en
		* RUTA_CARPETA."iacache/form-".implode("-", array_keys($atributos))."-.php";
		*
		**********************************************************************/



		//$formcampo = new formCampo2($id);
		foreach($atributos_all AS $a) {
			$campo = VistaAdmin_Form::crearComponentePorId($a['tipo_id']);
			$campo->id = $a['id'];
			$campo->sugerido = $a['sugerido'];
			$campo->unico = $a['unico'];
			//$campo->tipo = $a['tipo'];
			//$campo->subtipo = $a['subtipo'];
			$campo->nombre = $a['nombre'];
			//$campo->identificador = $a['identificador'];
			$campo->poromision = $a['poromision'];
			$campo->string = $a['string'];
			$campo->date = $a['date'];
			$campo->text = $a['text'];
			$campo->int = $a['int'];
			$campo->num = $a['num'];
			$campo->extra = unserialize($a['extra']);
			$campo->superior = $a['superior'];
			//$campo->nodo_tipo = $a['nodo_tipo'];
			//$campo->formato = $a['formato'];
			$campo->valores = $valores[$a['id']];

			$form->agregarComponente($campo);
		}

		//unset($formcampo);
	}


	//else
	//  echo "<tbody>";




/*
  $imagenes = array();
  if(!$consulta_imgs = $mysqli->query("SELECT gi.imagen_id, imagen_archivo_nombre, imagen_titulo, imagen_texto, imagen_fecha, imagen_estado, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` gi LEFT JOIN `galerias_imagenes_textos` git ON gi.imagen_id = git.imagen_id WHERE `galeria_id` = '{$id}' AND `imagen_estado` >= '1' ORDER BY ordennull ASC, imagen_orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($total_imgs = $consulta_imgs->num_rows)
   {
	while($fila_imgs = $consulta_imgs->fetch_row())
	 {
	  $imagenes[$fila_imgs[0]] = $fila_imgs[1];
	  echo "<input type=\"hidden\" name=\"img_estado[{$fila_imgs[0]}]\" value=\"{$fila_imgs[5]}\" /><input type=\"hidden\" name=\"img_titulo[{$fila_imgs[0]}]\" value=\"{$fila_imgs[2]}\" /><input type=\"hidden\" name=\"img_fecha[{$fila_imgs[0]}]\" id=\"img_fecha{$fila_imgs[0]}\" value=\"{$fila_imgs[4]}\" /><textarea name=\"img_texto[{$fila_imgs[0]}]\" rows=\"6\" cols=\"40\" class=\"oculto\">{$fila_imgs[3]}</textarea>";
	  //echo "<span></span><input type=\"image\" name=\"imagen[]\" src=\"imagen?archivo=img/galerias/imagenesChicas/{$fila_imgs[1]}\" value=\"{$fila_imgs[0]}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg({$fila_imgs[0]}, '{$fila_imgs[1]}', this)\" title=\"{$fila_imgs[1]}\" alt=\"{$fila_imgs[1]}\" />";
	 }
	$consulta_imgs->close();
   }
*/


	echo('
</form>
	<div id="subirArchivos"><form name="en" action="" method="post" enctype="multipart/form-data" target="frsubirArchivo"><fieldset><legend>Subir archivo</legend><input type="hidden" name="atributo" /><input type="hidden" name="indice" /><input type="hidden" name="retorno" /><input type="file" name="archivo" /><br /><input type="button" value="Cancelar" onclick="this.parentNode.parentNode.parentNode.style.display=\'none\'" /> <input type="button" value="Aceptar" onclick="subirImg(this.form[\'archivo\'])" /><span id="subirArchivosaviso" style="margin-left:1em;visibility:hidden;">Enviando archivo, aguarde...</span></fieldset></form></div>');



/*
SET DE IMAGENES
	<form name="en" action="" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="img_editando" />
	<table class="tabla">
	 <tbody>
	  <tr id="fila_galeria"<?php

  if(!$id || !count($imagenes))
   { echo " style=\"display:none;\"><td><div id=\"galeria\">"; }
  else
   {
	echo "><td><div id=\"galeria\">";
	foreach($imagenes AS $imgK => $imgV)
	 {
	  echo "<span></span><input type=\"image\" name=\"imagen[]\" src=\"imagen/img/galerias/imagenesChicas/{$imgV}\" value=\"{$imgK}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg(this)\" title=\"{$imgV}\" alt=\"{$imgV}\" />";
	 }
   }

?><span></span></div><input type="image" name="eliminarImg" src="img/papelera" alt="Eliminar" title="Arrastre hasta aquí para eliminar" style="background:none;border:none;" /></td></tr>
	  <tr id="fila_carga"
	   ><td
	    ><div><input type="hidden" name="seccion" value="<?php echo $seccion; ?>" /><input type="file" name="img_en" onchange="subirImg(this, 'formedicion');" /><!-- &nbsp;<input type="button" name="imagenserv" value="Examinar servidor..." onclick="abrirModal('./examinar?carpeta=<?php echo $seccion; ?>/imagenes', 510, 354);" /> --></div
	    ><div></div
	    ><fieldset style="display:none;"
	     ><legend></legend
	      ><img src="img/trans" alt=""
	      /><ol
	       ><li><label for="remplazar">Remplazar imagen</label><input type="file" name="remplazar" id="remplazar" onchange="remplazarImg(this, 'formedicion');" /></li
	       ><li><label for="img_estado">Habilitada</label><input type="checkbox" name="img_estado" id="img_estado" onchange="actualizarImgEstado(this)" /></li
		   ><li><label for="img_titulo">Título</label><input type="text" name="img_titulo" id="img_titulo" onblur="actualizarImgInfo(this)" /></li
		   ><li><label for="img_texto">Descripción</label><textarea name="img_texto" id="img_texto" rows="15" cols="40" onblur="actualizarImgInfo(this)"></textarea></li
		   ><li><label>Fecha</label><span id="mostrar_fechaImg"></span>&nbsp;&nbsp;<img src="/img/icono_calendario" id="tn_calendarioImg" style="cursor:pointer;" title="Abrir calendario" alt="Abrir calendario" /></li
		  ></ol
		 ></fieldset
		></td></tr>
	  <tr id="avisoguardar" style="display:none;"><td><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']; ?>">Regresar</a></div></td></tr>
	  <tr>
	   <td align="center"><input type="button" value="Cancelar" onclick="document.location.href='<?php echo php_self(); ?>'" />&nbsp;&nbsp;<input type="button" name="btGuardar" id="guardar" value="Guardar" onclick="aceptarFormGal(document.forms['formedicion'], false);this.value='Guardando...'" />&nbsp;&nbsp;<input type="button" name="btPublicar" value="Guardar/Publicar" onclick="aceptarFormGal(document.forms['formedicion'], true);this.value='Guardando/Publicando...'" />&nbsp;&nbsp;<input type="button" name="btElimPublic" value="Eliminar Publicaci&oacute;n" onclick="eliminarPublicacion();" /></td></tr>
	 </tbody>
	</table>
	</form>
<script type="text/javascript" defer="defer">
 var galeriaDiv = document.getElementById('galeria');
 var filaCargando = document.getElementById('fila_carga').firstChild;
 filaCargando['subiendoTotal'] = 0;
 var CARPETA = '<?php echo $seccion; ?>';
</script>
*/
	if($id) {
		$sub_i_cons = $mysqli->query("SELECT COUNT(*), seccion_id FROM items i WHERE superior_id = {$id} GROUP BY seccion_id");
		if($sub_i_fila = $sub_i_cons->fetch_row()) {
			$i = 0;
			echo("<ul>");
			do {
				echo("<li><a href=\"/listar?seccion={$sub_i_fila[1]}&amp;depend={$id}\"><b>".$secciones_nombres[$sub_i_fila[1]]."</b>: {$sub_i_fila[0]}</a></li>");
			}while($sub_i_fila = $sub_i_cons->fetch_row());
			echo("</ul>");
		}
	}

	echo('
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
	<iframe id="frsubirArchivo" name="frsubirArchivo" style="display:none;"></iframe>');


	//}
	//include('inc/iapie.php');
	$vista->html(ob_get_contents());
	ob_end_clean();
	$vista->mostrar();
}
else
	include('./error/404.php');

?>