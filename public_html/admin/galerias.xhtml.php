<?php

//header("Content-type: application/xhtml+xml; charset=utf-8");

$titulo = "Galerías";
$seccion = "galerias";
if(!empty($_GET['cat']))
 {
  $seccion_id = $_GET['cat'];
  $cat = $_GET['cat'];
 }
else
 {
  $seccion_id = 9;
  $cat = 9;
 }

require('../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../inc/ad_sesiones.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
<style type="text/css">
#galeria span {
	height:13px !important;
}
</style>
<?php

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
    $modificacion_tipo_accion = "publicadas";
    $publicar = new Item_publicarBarriola($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
      $publicar->Item($modificar[$i]);
	  $mysqli->query("UPDATE `galerias` SET `estado_id` = '1' WHERE `id` = '{$modificar[$i]}' LIMIT 1");
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
    $div_mensaje = "Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas;
    //tabla_informacion("Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas);
   }
 }

// agregar / editar
if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  if(!$consulta_lengs = $mysqli->query("SELECT l.id, nombre, nombre_nativo, xml_lang, dir FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.id = ln.id AND ln.leng_id_nombre = '1' ORDER BY iso_639_3")) die("\n".__LINE__." mySql: ".$mysqli->error); // WHERE leng_habilitado = '1'
  if($fila_lengs = $consulta_lengs->fetch_row())
   {
    $lengs_tot = $consulta_lengs->num_rows;
	do
	 {
	  $leng_id = $fila_lengs[0];
	  $idiomas_id_arr[] = $leng_id;
	  $idiomas_arr[$leng_id] = $fila_lengs[1] ? $fila_lengs[1] : $fila_lengs[2];
	  $idiomas_xmllang_arr[$leng_id] = $fila_lengs[3];
	  $idiomas_dir_arr[$leng_id] = $fila_lengs[4];
	 }while($fila_lengs = $consulta_lengs->fetch_row());
	$consulta_lengs->close();
   }

  if(!empty($_REQUEST['id']))
   {
	//$sel_idioma = $_REQUEST['leng'];
	if(!$consulta_item = $mysqli->query("SELECT estado_id, miniatura, UNIX_TIMESTAMP(`creada`) AS creada, titulo, texto, categoria_id FROM `galerias` ga LEFT JOIN `galerias_textos` gt ON ga.id = gt.galeria_id AND gt.leng_id = '${leng}' WHERE ga.id = '".$_REQUEST['id']."'")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila_item = $consulta_item->fetch_assoc())
	 {
	  $transaccion = "Editar";
	  $estado = $fila_item['estado'];
	  $texto = $fila_item['texto'];
	  $miniatura = $fila_item['miniatura'];
	  $creada = $fila_item['creada'];
	  $nombre = $fila_item['titulo'];
	  $cat = $fila_item['categoria_id'];
	  $secciones->rearmado($cat);
	  $seccion_id = $cat;
	  $consulta_item->close();
	  //$leng = $_REQUEST['leng'];
	  $id = $_REQUEST['id'];

	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, UNIX_TIMESTAMP(`date`), text, `int` FROM galerias_valores WHERE galeria_id = '".$_REQUEST['id']."'")) echo __LINE__." - ".$mysqli->error;
	  if($fila_valores = $cons_valores->fetch_row())
	   {
	    do
	     {
		  $valor = $fila_valores[0];
		  $valores[$valor][] = array($fila_valores[1], $fila_valores[2], $fila_valores[3], $fila_valores[4], $fila_valores[5]);
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

  include('iaencab.php');
  echo "  <div id=\"div_mensaje\"";
  if(!$div_mensaje) echo " style=\"display:none;\"";
  echo ">$div_mensaje</div>\n";

  if(is_array($secciones->secciones[$seccion_id])) $subcategorias = $secciones->secciones[$seccion_id];
  elseif($secciones->superior[$seccion_id] != 0) $subcategorias = $secciones->secciones[$secciones->superior[$seccion_id]];

  $atributos = array();
  if(!$atributos_tipos = $mysqli->query("SELECT ga.id, sugerido, unico, tipo, subtipo, atributo, identificador FROM galerias_atributos ga LEFT JOIN galerias_atributos_n gan ON ga.id = gan.id AND leng_id = '1', galerias_categorias_a_atributos gcaa WHERE  ga.id = gcaa.atributo_id AND categoria_id =  '${cat}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
  if($fila_at = $atributos_tipos->fetch_row())
   {
	do
	 {
	  $atributos[$fila_at[0]] = array('sugerido' => $fila_at[1], 'unico' => $fila_at[2], 'tipo' => $fila_at[3], 'subtipo' => $fila_at[4], 'nombre' => $fila_at[5], 'identificador' => $fila_at[6]);
	 }while($fila_at = $atributos_tipos->fetch_row());
	$atributos_tipos->close();
   }

  echo "
	<form name=\"formedicion\" id=\"formedicion\" action=\"".php_self()."_guardar\" target=\"frguardar\" method=\"post\">
	 <input type=\"hidden\" name=\"id\" value=\"${id}\" />
	 <input type=\"hidden\" name=\"idioma\" value=\"${leng}\" />
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"publicar\" value=\"0\" />\n";
  if(!count($subcategorias)) echo "	 <input type=\"hidden\" name=\"cat\" value=\"${cat}\" />\n";
  if($lengs_tot == 1) echo " <input type=\"hidden\" name=\"leng[]\" value=\"${leng}\" />\n";

/*
echo "<pre>atributos\n";
print_r($atributos);
echo "\nvalores\n";
print_r($valores);
echo "</pre>";
*/

?>
	<table class="tabla" <?php if($lengs_tot > 1) echo "style=\"width:100%;\""; ?>>
	 <thead>
	  <tr>
	   <th colspan="2"><?php echo $transaccion; ?> Galer&iacute;a</th></tr>
<?php

  if(count($subcategorias))
   {
	echo "
	   <tr>
	    <td><label for=\"cat\">Categoría</label>:</td>
	    <td><select name=\"cat\" id=\"cat\">";
	foreach($subcategorias AS $subcat)
	 {
	  echo "<option value=\"{$subcat['id']}\"";
	  if($subcat['id'] == $cat) echo " selected=\"selected\"";
	  echo ">{$subcat['nombre']}</option>";
	 }
	echo "</select></td></tr>";
   }

?>
	  </thead>
	  <tbody>
	   <tr>
	    <td><label for="nombre1">Nombre</label>:</td>
	    <td><input type="text" name="nombre[]" id="nombre1" value="<?php echo $nombre; ?>" size="30" maxlength="30" /></td></tr>
<?php

  include('../../inc/formulario2.php');
  $i = 1;
  foreach($atributos AS $k => $a)
   {
	$req = ($a['sugerido'] == 2) ? "<span>*</span>": false;
	if($a['sugerido'] == 0 && !$valores[$k]) continue;
	$v =  is_array($valores[$k][0]) ? $valores[$k][0][0] : false;
	//$x = var_export($valores[$k], true);
	echo "
	  <tr>
	   <td>".pedir_dato($k, $v, $valores[$k], $a['tipo'], $a['subtipo'], $a['nombre'], $i, $a['identificador'], $cat)."${req}</td></tr>";
	  $i++;
	 }
  /*
  if(!$consulta_at_extra = $mysqli->query("SELECT at.id, atributo, identificador, unico, tipo FROM `galerias_atributos` at LEFT JOIN `galerias_atributos_n` an ON at.id = an.id AND leng_id = '1' WHERE sugerido = '1' ORDER BY orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_atribs = $consulta_at_extra->fetch_row())
   {
    do
     {
	  echo "
	 <tr>
	  <td><label for=\"atributo${fila_atribs[0]}\">${fila_atribs[1]}</label>:</td>
	  <td><input type=\"text\" name=\"atributo[${fila_atribs[0]}][]\" id=\"atributo${fila_atribs[0]}\"";
	  if($atributos[$fila_atribs[0]]) echo " value=\"${atributos[$fila_atribs[0]]}\"";
	  echo " size=\"30\" maxlength=\"30\" /></td></tr>";
	 }while($fila_atribs = $consulta_at_extra->fetch_row());
	$consulta_at_extra->close();
   }
  */

?>
	  <tr><td><label>&Iacute;cono</label>:</td><td id="celdaIcono"><?php if($fila_item['miniatura']) { echo "<img src=\"icono?archivo=img/galerias/imagenes/${fila_item['miniatura']}\" id=\"icono\" alt=\"\" /><input type=\"hidden\" name=\"miniatura\" value=\"${fila_item['miniatura']}\" />"; } ?></td></tr>
	  <tr>
	   <td colspan="2" align="center">Descripci&oacute;n:<br /><textarea name="descripcion[]" cols="40" rows="6" style="width:98%;"><?php echo $texto; ?></textarea></td></tr>
	 </tbody>
	</table>
<?php

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

?>

	</form>
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
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
<?php

 }


/* por omision */
if(!$no_poromision)
 {
  $cat_superior = current($secciones->actual_superior);
  include('iaencab.php');
  echo "  <div id=\"div_mensaje\"";
  if(!$div_mensaje) echo " style=\"display:none;\"";
  echo ">$div_mensaje</div>\n";



  if($cat != 9) echo "  <div><a href=\"./".php_self()."?ia=agregar&amp;cat=${cat}\">Agregar galer&iacute;a</a></div>";
  if($_SESSION['permisos'][$seccion] >= 5) echo "<div><a href=\"conf_${seccion}\">Configuraci&oacute;n</a></div>\n";
  //echo "<fieldset><legend>Aviso</legend>Todas las modificaciones que se realizen en este listado afectan directamente a la publicaci&oacute;n.</fieldset>";
  //echo "<form action=\"#\" name=\"orden\"><fieldset><legend>Orden de publicaci&oacute;n</legend><label for=\"orden_criterio\">Ordenar seg&uacute;n</label><select name=\"orden_criterio\" id=\"orden_criterio\"><option value=\"5\">T&iacute;tulo</option><option value=\"3\">Posici&oacute;n</option><option value=\"4\">Fecha de creaci&oacute;n</option></select>&nbsp;<input type=\"checkbox\" name=\"orden_dir\" id=\"orden_dir\" value=\"1\" /><label for=\"orden_dir\">inverso</label><input type=\"button\" value=\"Aplicar\" onclick=\"ordenPublicacion('${seccion}', this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].value, this.form['orden_dir'].checked, this.form['orden_criterio'][this.form['orden_criterio']['selectedIndex']].text);\" /></fieldset></form>
  echo "		<!-- style=\"\" display:none; -->
		<form action=\"".php_self()."?cat=${cat}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\" style=\"display:none;\"";
//		 ><caption><select name=\"\"><option value=\"\"></option></select></caption
echo "
		 ><thead
		  ><tr class=\"orden\"
		   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
		   ><td style=\"width:50px;\">&nbsp;</td
		   ><td>T&iacute;tulo</td
		   ><td>Posici&oacute;n</td
		   ><td>Creada</td></tr
		 ></thead
		 ><tbody
		  ><tr
		   ><td colspan=\"4\"></td></tr
		 ></tbody
		></table>";

?>

  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ninguna novedad.</div>
  <div id="listado_opciones" style="padding:4px;display:none;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado('lista_item[]');" /></div>
  <div id="listado_result"></div>
	 </form>


<script type="text/javascript" defer="defer">
//<![CDATA[
var tablaListado = document.getElementById('tablaListado');
var listadoOpciones = document.getElementById('listado_opciones');
var listadoResult = document.getElementById('listado_result');
var orden = 5;
var CAT = '<?php echo $cat; ?>';
var CAT_SUP = '<?php echo $cat_superior; ?>';
loadXMLDoc('./galeria.xml?leng=<?php echo "${leng}&cat=${cat}&cat_sup=${cat_superior}"; ?>', cargarListado, null);
//]]>
</script>


<?php
	
 }
include('iapie.php');

?>