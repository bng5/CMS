<?php

$mod = "listar";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();


$seccion = Secciones::obtenerPorId((int) $_REQUEST['seccion']);

$consulta_seccion = $_REQUEST['id'] ? "SELECT ase.id, ase.`categorias_prof`, ase.identificador FROM `secciones` ase LEFT JOIN items_categorias i ON ase.id = i.seccion_id WHERE i.`id` = '{$_REQUEST['id']}' AND ase.`link_cms` = '${mod}' LIMIT 1" : "SELECT id, 'nombre', `categorias_prof`, identificador FROM `secciones` WHERE `id` = '{$_REQUEST['seccion']}' AND `link_cms` = '${mod}' LIMIT 1";
$cons_seccion = $mysqli->query($consulta_seccion);
if($fila_seccion = $cons_seccion->fetch_row()) {
	$seccion_id = $fila_seccion[0];
	$titulo = $fila_seccion[1];
	$prof_categorias = $fila_seccion[2];
	//$seccion = "editar";
	//$secciones = new adminsecciones();
	require('inc/ad_sesiones.php');

	$vista = new VistaAdmin_Documento($seccion);
	$vista->agregarJS('editar');
	ob_start();

// agregar / editar
//if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
// {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  $lenguajes = array();
  if(!$consulta_lengs = $mysqli->query("SELECT l.id, nombre, nombre_nativo, codigo, dir, codigo FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.id = ln.id AND ln.leng_id = 76 WHERE l.estado > 0 AND l.estado < 5 ORDER BY leng_poromision DESC")) die("\n".__LINE__." mySql: ".$mysqli->error); // WHERE leng_habilitado = '1'
  if($fila_lengs = $consulta_lengs->fetch_row())
   {
    $lengs_tot = $consulta_lengs->num_rows;
	do
	 {
	  $leng_id = $fila_lengs[0];
	  $lenguajes[$leng_id] = $fila_lengs[5];
	  $idiomas_id_arr[] = $leng_id;
	  $idiomas_arr[$leng_id] = $fila_lengs[1] ? $fila_lengs[1] : $fila_lengs[2];
	  $idiomas_xmllang_arr[$leng_id] = $fila_lengs[3];
	  $idiomas_dir_arr[$leng_id] = $fila_lengs[4];
	 }while($fila_lengs = $consulta_lengs->fetch_row());
	$consulta_lengs->close();
   }

  $cat = $_REQUEST['cat'];
  if(!empty($_REQUEST['id']))
   {
	//$sel_idioma = $_REQUEST['leng'];
	if(!$consulta_item = $mysqli->query("SELECT `seccion_id`, `superior`, `orden`, `estado_id` FROM `items_categorias` WHERE id = '{$_REQUEST['id']}'")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila_item = $consulta_item->fetch_assoc())
	 {
	  $transaccion = "Editar";
	  $estado = $fila_item['estado_id'];
	  $cat = $fila_item['superior'];
	  //$texto = $fila_item['texto'];
	  //$miniatura = $fila_item['miniatura'];
	  //$creada = $fila_item['f_creado'];
	  //$nombre = $fila_item['titulo'];
	  //$cat = $fila_item['categoria_id'];
	  //$secciones->rearmado($cat);
	  $seccion_id = $fila_item['seccion_id'];
	  $consulta_item->close();
	  //$leng = $_REQUEST['leng'];
	  $id = $_REQUEST['id'];

	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, `date`, `text`, `int`, `num`, leng_id FROM categorias_valores WHERE categoria_id = '${id}'")) echo __LINE__." - ".$mysqli->error;
	  if($fila_valores = $cons_valores->fetch_row())
	   {
	    do
	     {
		  $valor = $fila_valores[0];
		  if($fila_valores[7]) $valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
		  else $valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
	     }while($fila_valores = $cons_valores->fetch_row());
	 	$cons_valores->close();
	   }

	  /*
	  if(!$consulta_atts = $mysqli->query("SELECT atributo_id, texto FROM `galerias_info` gi JOIN galerias_valores_t gvt ON gi.valor_id = gvt.valor_id WHERE gi.galeria_id = '".$_REQUEST['id']."' AND gvt.leng_id = '${leng_id}'")) die("\n".__LINE__." mySql: ".$mysqli->error);
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

  //include('inc/iaencab.php');
  $seccion = $fila_seccion[3];
  /*
  if($id)
   {
	$dom_pub = substr($_SERVER['SERVER_NAME'], 6);
	echo "<div id=\"salida_publicacion\">Publicación: ";
	echo ($estado > 0) ? "<a href=\"http://${dom_pub}/item/${id}\" target=\"_blank\">${dom_pub}/item/${id}</a>" : "No publicado";
	echo "</div>";
   }
  */

  if(is_array($secciones->secciones[$seccion_id]))
	$subcategorias = $secciones->secciones[$seccion_id];
  elseif($secciones->superior[$seccion_id] != 0)
	$subcategorias = $secciones->secciones[$secciones->superior[$seccion_id]];

  $atributos = array();
  if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, ia.tipo_id, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = 1, categorias_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.id = isaa.atributo_id AND seccion_id = '{$seccion_id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
  if($fila_at = $atributos_tipos->fetch_assoc())
   {
	do
	 {
	  $attr_id = array_shift($fila_at);
	  $atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo_id' => $fila_at['tipo_id'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'],
		  'poromision' => $fila_at[$fila_at['tipo']]);/* TODO */
	 }while($fila_at = $atributos_tipos->fetch_assoc());
	$atributos_tipos->close();
   }

  echo "
	<form name=\"formedicion\" id=\"formedicion\" action=\"categoria_guardar?seccion={$seccion_id}\" ";/*target=\"frguardar\"*/echo " method=\"post\">
	 <input type=\"hidden\" name=\"id\" value=\"${id}\" />
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"publicar\" value=\"0\" />
	 <input type=\"hidden\" name=\"seccion\" value=\"{$seccion_id}\" />
	 <input type=\"hidden\" name=\"pos_actual\" value=\"{$fila_item['orden']}\" />\n";
 // if($lengs_tot == 1)
  echo " <input type=\"hidden\" name=\"leng[]\" value=\"${leng}\" />\n";

/*
echo "<pre>";
echo "\natributos\n";
print_r($atributos);
echo "\nvalores\n";
print_r($valores);
echo "</pre>";
*/


/*
	<table class="tabla" <?php if($lengs_tot > 1) echo "style=\"width:100%;\""; ?>
	 ><thead
	  ><tr
	   ><th colspan="2"><?php echo $transaccion; ?> Galer&iacute;a</th></tr
	 ></thead
	  ><tfoot
	   ><tr
	    ><td><input type="submit" /></td></tr>
	  </tfoot
	  ><tbody
*/

?>

	<table class="tabla">
	 <thead>
	  <tr>
	   <td colspan="2"><?php echo $transaccion; ?> categoría</td></tr>
	 </thead>
	 <tfoot>
	  <tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="listar?seccion=<?php echo $seccion_id ?>">Regresar</a></div></td></tr>
	  <tr>
	   <td colspan="2" style="text-align:center;"><input type="button" value="Cancelar" onclick="document.location.href='listar?<?php echo "seccion=${seccion_id}&amp;cat="; ?>'" />&nbsp;&nbsp;<input type="submit" name="btGuardar" id="guardar" value="Guardar" onclick="aceptarFormGal(this.form, false);"<?php /* this.value='Guardando...'" */ ?> />&nbsp;&nbsp;<input type="submit" name="btPublicar" value="Guardar/Publicar" onclick="aceptarFormGal(this.form, true);" <?php /* onclick="aceptarFormGal(document.forms['formedicion'], true);this.value='Guardando/Publicando...'" */ ?> />&nbsp;&nbsp;<input type="button" name="btElimPublic" value="Eliminar Publicaci&oacute;n" onclick="eliminarPublicacionCat('<?php echo $seccion ?>');" /></td></tr>
	  <!-- tr>
	   <td colspan="2" style="text-align:center"><input type="button" value="Cancelar" onclick="document.location.href='categoria'" />&nbsp;&nbsp;<input type="submit" value="Aceptar" /></td></tr -->
	 </tfoot>
	 <tbody>

<?php


  function subcategoriaSS($subcat, $n, $seleccionado, $exclusion)
   {
	global $mysqli, $seccion_id, $js_categorias, $js_categorias_nombres;
	if($exclusion)
	 { $excluir = "c.`id` != '${exclusion}' AND"; }

	if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE ${excluir} `superior` = '${subcat}' AND seccion_id = ${seccion_id} ORDER BY c.`orden`")) die(__LINE__." - ".$mysqli->error);
	if($row_subcat = $tbsubcat->fetch_row())
	 {
	  $js_categorias .= "CATEGORIAS[{$row_subcat[1]}] = {};\n";
	  $i = 0;
	  do
	   {
	   	$js_categorias .= "CATEGORIAS[{$row_subcat[1]}][{$row_subcat[0]}] = {$row_subcat[3]};\n";
	   	$js_categorias_nombres .= "CATEGORIAS_N[{$row_subcat[0]}] = '{$row_subcat[2]}';\n";
	   	$i++;
	   }while($row_subcat = $tbsubcat->fetch_row());
	 }
   }

  function subcategoria($subcat, $n, $seleccionado, $exclusion)
   {
	global $mysqli, $seccion_id, $js_categorias, $js_categorias_nombres, $limite;
	if(isset($limite) && $n >= $limite)
	 {
	  subcategoriaSS($subcat, $n, $seleccionado, $exclusion);
	  return;
	 }
	$separador_niv = "&nbsp;&nbsp;&nbsp;&nbsp;";
	if($exclusion)
	 { $excluir = "c.`id` != '${exclusion}' AND"; }

	if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE ${excluir} `superior` = '${subcat}' AND seccion_id = ${seccion_id} ORDER BY cn.`nombre`")) die(__LINE__." - ".$mysqli->error);
	if($row_subcat = $tbsubcat->fetch_row())
	 {
//$limite++;
	  $js_categorias .= "CATEGORIAS[{$row_subcat[1]}] = {};\n";
	  $i = 0;
	  do
	   {
	   	$js_categorias .= "CATEGORIAS[{$row_subcat[1]}][{$row_subcat[0]}] = {$row_subcat[3]};\n";
	   	$js_categorias_nombres .= "CATEGORIAS_N[{$row_subcat[0]}] = '{$row_subcat[2]}';\n";
	   	$i++;
		echo "<option value=\"".$row_subcat[0]."\"";
		if ($seleccionado == $row_subcat[0])
		 { echo " selected=\"selected\""; }
		echo ">".str_repeat($separador_niv, $n).$row_subcat[2]."</option>\n";

		$subcat = $row_subcat[0];
		subcategoria($subcat, ++$n , $seleccionado, $exclusion);
		$n--;
	   } while($row_subcat = $tbsubcat->fetch_row());
	 }
   }
  echo "
	 <tr>
	  <td><label>Categoría:</label></td>
	  <td><ul class=\"campo_lista\">";
	  if($id)
	   {
		$nombres = array();
		$cons_nombres = $mysqli->query("SELECT leng_id, nombre FROM items_categorias_nombres WHERE id = {$id}");
		if($fila_nombres = $cons_nombres->fetch_row())
		 {
		  do
		   {
			$nombres[$fila_nombres[0]] = $fila_nombres[1];
		   }while($fila_nombres = $cons_nombres->fetch_row());
		 }
	   }
	  foreach($lenguajes AS $leng_id => $leng_cod)
	   {
	    echo "
	    <li><label for=\"nombre{$leng_id}\"><tt>({$leng_cod})</tt></label> <input type=\"text\" name=\"nombre[{$leng_id}]\" id=\"nombre{$leng_id}\" value=\"".htmlspecialchars($nombres[$leng_id])."\" size=\"30\" maxlength=\"30\" /></li>";
	   }
	  echo "</ul></td></tr>
	 <tr>
	  <td>Ubicado en:</td><td><select name=\"cat\" onchange=\"actOrdenSel(this.options[this.selectedIndex].value)\"><option value=\"0\">(Inicio)</option>\n";
	  $js_categorias = '';
	  $js_categorias_nombres = '';
	  if($prof_categorias >= 1) $limite = ($prof_categorias - 1);
      subcategoria(0, 0, $cat, $id);
      echo "</select>";

?>
<script type="text/javascript">
//<![CDATA[
var CATEGORIASEL = <?php echo $cat ?>;
var CATEGORIAS = {};
var CATEGORIAS_N = {};
<?php echo ${js_categorias} ?>
<?php echo ${js_categorias_nombres} ?>

agregarEvento(window, 'load', actOrden);

//]]>
</script></td></tr>
	 <tr>
	  <td>Orden:</td><td><ul style="list-style-type:none;">
 <li><input type="radio" name="pos" value="1" id="pos1" /> <label for="pos1">Último</label></li>
 <li><input type="radio" name="pos" value="2" id="pos2" /> <label for="pos2">Antes de </label><select name="antesde" id="j_orden"><option value=""> </option></select></li>
</ul></td></tr>


<?php

/**********************************************************************
*
* agregear cache en
* RUTA_CARPETA."iacache/form-".implode("-", array_keys($atributos))."-.php";
*
**********************************************************************/

	if(count($atributos)) {
		echo '<tr><td colspan="2">';
		$vista->html(ob_get_contents());
		ob_clean();
		//$formcampo = new formCampo2($seccion);
		$form = new VistaAdmin_Form();
		$vista->agregarComponente($form);
		foreach($atributos AS $k => $a) {
			$campo = VistaAdmin_Form::crearComponentePorId($a['tipo_id']);
			$campo->id = $k;//$a['id'];
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
			$campo->valores = $valores[$k];
			$form->agregarComponente($campo);

	/*	  $formcampo->id = $k;
		  $formcampo->sugerido = $a['sugerido'];
		  $formcampo->unico = $a['unico'];
		  $formcampo->tipo = $a['tipo'];
		  $formcampo->subtipo = $a['subtipo'];
		  $formcampo->nombre = $a['nombre'];
		  $formcampo->poromision = $a['poromision'];
		  $formcampo->extra = $a['extra'];
		  //$formcampo->identificador = $a['identificador'];
		  $formcampo->valores = $valores[$k];
		  echo "
		  <tr>".$formcampo->imprimir()."</tr>";

	 */
		 }
		 echo "</td></tr>";
	}

?>
	 </tbody>
	</table>
<?php

/*
  $imagenes = array();
  if(!$consulta_imgs = $mysqli->query("SELECT gi.imagen_id, imagen_archivo_nombre, imagen_titulo, imagen_texto, imagen_fecha, imagen_estado, `imagen_orden` IS NULL AS ordennull FROM `galerias_imagenes` gi LEFT JOIN `galerias_imagenes_textos` git ON gi.imagen_id = git.imagen_id WHERE `galeria_id` = '${id}' AND `imagen_estado` >= '1' ORDER BY ordennull ASC, imagen_orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($total_imgs = $consulta_imgs->num_rows)
   {
	while($fila_imgs = $consulta_imgs->fetch_row())
	 {
	  $imagenes[$fila_imgs[0]] = $fila_imgs[1];
	  echo "<input type=\"hidden\" name=\"img_estado[{$fila_imgs[0]}]\" value=\"{$fila_imgs[5]}\" /><input type=\"hidden\" name=\"img_titulo[{$fila_imgs[0]}]\" value=\"{$fila_imgs[2]}\" /><input type=\"hidden\" name=\"img_fecha[{$fila_imgs[0]}]\" id=\"img_fecha{$fila_imgs[0]}\" value=\"{$fila_imgs[4]}\" /><textarea name=\"img_texto[{$fila_imgs[0]}]\" rows=\"6\" cols=\"40\" class=\"oculto\">{$fila_imgs[3]}</textarea>";
	  //echo "<span></span><input type=\"image\" name=\"imagen[]\" src=\"imagen?archivo=img/galerias/imagenesChicas/${fila_imgs[1]}\" value=\"${fila_imgs[0]}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg(${fila_imgs[0]}, '${fila_imgs[1]}', this)\" title=\"${fila_imgs[1]}\" alt=\"${fila_imgs[1]}\" />";
	 }
	$consulta_imgs->close();
   }
*/

?>

	</form>
	<div id="subirArchivos"><form name="en" action="" method="post" enctype="multipart/form-data" target="frsubirArchivo"><fieldset><legend>Subir archivo</legend><input type="hidden" name="atributo" /><input type="hidden" name="indice" /><input type="file" name="archivo" /><br /><input type="button" value="Cancelar" onclick="this.parentNode.parentNode.parentNode.style.display='none'" /> <input type="button" value="Aceptar" onclick="subirImg(this.form['archivo'])" /><span id="subirArchivosaviso" style="margin-left:1em;visibility:hidden;">Enviando archivo, aguarde...</span></fieldset></form></div>

<?php

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
	  echo "<span></span><input type=\"image\" name=\"imagen[]\" src=\"imagen/img/galerias/imagenesChicas/${imgV}\" value=\"${imgK}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg(this)\" title=\"${imgV}\" alt=\"${imgV}\" />";
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
?>
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
	<iframe id="frsubirArchivo" name="frsubirArchivo" style="display:none;"></iframe>
<?php

   //}
	$vista->html(ob_get_contents());
	ob_end_clean();
	$vista->mostrar();
}
else
	include('./error/404.php');

?>