<?php

$mod = "listar";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();

try {
	$seccion = Secciones::obtenerPorId((int) $_REQUEST['seccion']);
}
catch(Exception $e) {

}
 //$consulta_seccion = "SELECT id, nombre, identificador FROM `admin_secciones` WHERE `id` = '{$_REQUEST['seccion']}' AND info = 1 LIMIT 1";
if($seccion && $seccion->info)
 {
  //$seccion_id = $seccion->getId();
  $id = $seccion->id;
  $titulo = $seccion->getNombre($_SESSION['leng_id']);
  //$seccion = "editar";//$fila_seccion[2];
  //$secciones = new adminsecciones();
  require('inc/ad_sesiones.php');

  $vista = new VistaAdmin_Documento($seccion);
  $vista->agregarJS('editar');
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $secciones_nombres[$seccion->id]." - ".SITIO_TITULO; ?></title>
*/



// agregar / editar
//if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
// {
  $no_poromision = TRUE;
  if(!$consulta_lengs = $mysqli->query("SELECT l.id, nombre, nombre_nativo, codigo, dir FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.id = ln.id AND ln.leng_id = {$_SESSION['leng_id']} ORDER BY codigo")) die("\n".__LINE__." mySql: ".$mysqli->error); // WHERE leng_habilitado = '1'
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

//  if(!empty($_REQUEST['seccion']))
//   {
	//$sel_idioma = $_REQUEST['leng'];

//	if(!$consulta_item = $mysqli->query("SELECT id, estado_id, orden, f_creado FROM `secciones` WHERE id = '".$_REQUEST['seccion']."'")) die("\n".__LINE__." mySql: ".$mysqli->error);
//	if($fila_item = $consulta_item->fetch_assoc())
//	 {
	  $transaccion = "Editar";
//	  $estado = $fila_item['estado_id'];
	  //$texto = $fila_item['texto'];
	  //$miniatura = $fila_item['miniatura'];
//	  $creada = $fila_item['f_creado'];
	  //$nombre = $fila_item['titulo'];
	  //$cat = $fila_item['categoria_id'];
	  //$secciones->rearmado($cat);
//	  $seccion_id = $fila_item['seccion_id'];
//	  $consulta_item->close();
	  //$leng = $_REQUEST['leng'];
//	  $id = $_REQUEST['seccion'];

  $valores = array();
  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, `date`, `text`, `int`, `num`, leng_id FROM secciones_valores WHERE item_id = '${id}'")) echo __LINE__." - ".$mysqli->error;
  if($fila_valores = $cons_valores->fetch_row())
   {
    do
     {
	  $valor = $fila_valores[0];
	  if($fila_valores[7])
		  $valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
	  else
		  $valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
	 }while($fila_valores = $cons_valores->fetch_row());
	$cons_valores->close();
   }

  //include('./vistas/iaencab.php');
  //$seccion = $fila_seccion[2];

  if($_SESSION['permisos']['admin_seccion'][$seccion->id] >= 5)
	$vista->html("<div><a href=\"".APU."configuracion_s?seccion=".$seccion->id."\">Configuraci&oacute;n</a></div>\n");

  if(is_array($secciones->secciones[$seccion->id]))
    $subcategorias = $secciones->secciones[$seccion->id];
  elseif($secciones->superior[$seccion->id] != 0)
    $subcategorias = $secciones->secciones[$secciones->superior[$seccion->id]];

  $atributos = array();
//$vista->html("
//SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.formato, at.tipo, at.subtipo, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = ".$_SESSION['leng_id'].", atributos_tipos at, secciones_a_atributos isaa LEFT JOIN secciones_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND seccion_id = '".$seccion->id."' ORDER BY orden
//");

  if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.tipo_id, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = ".$_SESSION['leng_id'].", secciones_a_atributos isaa LEFT JOIN secciones_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.id = isaa.atributo_id AND seccion_id = '".$seccion->id."' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
  if($fila_at = $atributos_tipos->fetch_assoc())
   {
	do
	 {
	  $attr_id = array_shift($fila_at);
	  $atributos[$attr_id] = $fila_at;
	  $atributos[$attr_id]['poromision'] = $fila_at[$fila_at['tipo']];
	 }while($fila_at = $atributos_tipos->fetch_assoc());
	$atributos_tipos->close();
   }

   /*target=\"frguardar\"*/
  $vista->html("
	<form name=\"formedicion\" id=\"formedicion\" action=\"info_guardar?seccion=".$seccion->id."\" method=\"post\">
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"publicar\" value=\"0\" />
	 <input type=\"hidden\" name=\"seccion\" value=\"{$seccion->id}\" />
	 <input type=\"hidden\" name=\"cat\" value=\"{$_REQUEST['cat']}\" />\n");
 // if($lengs_tot == 1)
  $vista->html(" <input type=\"hidden\" name=\"leng[]\" value=\"${leng}\" />\n");

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


  	$form = new VistaAdmin_Form();
	$form->thead = '<thead>
	  <tr>
	   <td colspan="2">'.$transaccion.' sección</td></tr>
	 </thead>';
	/* <?php / * this.value='Guardando...'" * / ?> */
	/* <?php / * this.value='Guardando/Publicando...'" * / ?> */
	$form->tfoot = '<tfoot>
	  <tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="/info?de='.$_REQUEST['de'].'&amp;sup='.$sup.'&amp;cat='.$cat.'">Regresar</a></div></td></tr>
	  <tr>
	   <td colspan="2" style="text-align:center;"><input type="button" value="Cancelar" onclick="document.location.href=\'listar?seccion='.$seccion->id.'&amp;cat=\'" />&nbsp;&nbsp;<input type="submit" name="btGuardar" id="guardar" value="Guardar"  onclick="aceptarFormGal(this.form, false);"    />&nbsp;&nbsp;<input type="submit" name="btPublicar" value="Guardar/Publicar" onclick="aceptarFormGal(this.form, true);"    /></td></tr>
	 </tfoot>';


/**********************************************************************
* 
* agregear cache en 
* RUTA_CARPETA."iacache/form-".implode("-", array_keys($atributos))."-.php";
* 
**********************************************************************/

	//$formcampo = new formCampo($seccion);
	foreach($atributos AS $k => $a) {
		$campo = VistaAdmin_Form::crearComponentePorId($a['tipo_id']);
		$campo->id = $k;
		$campo->sugerido = $a['sugerido'];
		$campo->unico = $a['unico'];
		/** OBSOLETO
		$campo->tipo = $a['tipo'];*/
		$campo->subtipo = $a['subtipo'];
		$campo->nombre = $a['nombre'];
		$campo->poromision = $a['poromision'];
		$campo->extra = unserialize($a['extra']);
		//$campo->formato = $a['formato'];
		//$formcampo->identificador = $a['identificador'];
		$campo->valores = $valores[$k];

		$form->agregarComponente($campo);

/*echo "
<tr>
<td colspan=\"2\"><pre>";
print_r($a);
//print_r(current($valores[$k]));
print_r($valores[$k]);
echo "</pre></td></tr>";
*/
	  //$cur_valor = current($valores[$k]);
	  /*$formcampo->id = $k;
	  $formcampo->sugerido = $a['sugerido'];
	  $formcampo->unico = $a['unico'];
	  $formcampo->tipo = $a['tipo'];
	  $formcampo->subtipo = $a['subtipo'];
	  $formcampo->nombre = $a['nombre'];
	  $formcampo->poromision = $a['poromision'];
	  $formcampo->extra = $a['extra'];
	  //$formcampo->identificador = $a['identificador'];
	  $formcampo->valores = $valores[$k];
	  */
	  //echo $formcampo->imprimir();
	 }
	 
	$vista->agregarComponente($form);




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

$vista->html('
	</form>
	<div id="subirArchivos"><form name="en" action="" method="post" enctype="multipart/form-data" target="frsubirArchivo"><fieldset><legend>Subir archivo</legend><input type="hidden" name="atributo" /><input type="hidden" name="indice" /><input type="file" name="archivo" /><br /><input type="button" value="Cancelar" onclick="this.parentNode.parentNode.parentNode.style.display=\'none\'" /> <input type="button" value="Aceptar" onclick="subirImg(this.form[\'archivo\'])" /><span id="subirArchivosaviso" style="margin-left:1em;visibility:hidden;">Enviando archivo, aguarde...</span></fieldset></form></div>');

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

$vista->html('
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
	<iframe id="frsubirArchivo" name="frsubirArchivo" style="display:none;"></iframe><!--  -->');


   //}
  //include('inc/iapie.php');
  $vista->mostrar();
 }
else
	include('./error/404.php');

?>