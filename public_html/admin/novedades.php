<?php

/*if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
 {

  header("Content-type: application/xhtml+xml; charset=UTF-8");
   }
else
 {*/
 // header("Content-type: text/html; charset=UTF-8");
  //}

$titulo = "Novedades";
$seccion = "novedades";

require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');

$p_id = intval($_REQUEST["p_id"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<script type="text/javascript">
// <![CDATA[
<?php

echo "

 var dia = ".date("j").";
 var mes = ".date("n").";
 var mes_ind = ".(date("n") - 1).";
 var anyo = ".date("Y").";";

?>

// ]]>
</script>

<?php

include('iaencab.php');

// modificar
if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
 {
  $modificar = $_POST['lista_item'];
  $modificadas = 0;

  // borrar
  if($_POST['mult_submit'] == "Eliminar")
   {
    $modificacion_tipo_accion = "eliminadas";
    for($i = 0; $i < count($modificar); $i++)
     {
      mysql_query("DELETE FROM `novedades_textos` WHERE `novedad_id` = '".$modificar[$i]."'", $mysql);
	  mysql_query("DELETE FROM `novedades` WHERE `novedad_id` = '".$modificar[$i]."'", $mysql);
      if(mysql_affected_rows())
       { $modificadas++; }
     }
   }

  // habilitar
  elseif($_POST['mult_submit'] == "Habilitar")
   {
    $modificacion_tipo_accion = "habilitadas";
    for($i = 0; $i < count($modificar); $i++)
     {
      mysql_query("UPDATE `novedades` SET `novedad_estado` = '1' WHERE `novedad_id` = '".$modificar[$i]."' LIMIT 1", $mysql);
      if(mysql_affected_rows($mysql))
       { $modificadas++; }
     }
   }

  // deshabilitar
  elseif($_POST['mult_submit'] == "Deshabilitar")
   {
    $modificacion_tipo_accion = "deshabilitadas";
    for($i = 0; $i < count($modificar); $i++)
     {
      mysql_query("UPDATE `novedades` SET `novedad_estado` = '2' WHERE `novedad_id` = '".$modificar[$i]."' LIMIT 1", $mysql);
      if(mysql_affected_rows($mysql))
       { $modificadas++; }
     }
   }

  if($modificadas > 0)
   { tabla_informacion("Novedades ".$modificacion_tipo_accion.": ".$modificadas.""); }
 }

// agregar / editar
if($_REQUEST["ia"] == "editar" || !empty($_REQUEST['id']))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  $dia = date("j");
  $mes = date("n");
  $anyo = date("Y");
  $estado = 2;
  if(!empty($_REQUEST['id']))
   {
    //$bsq_leng = $_REQUEST['leng'] ? "AND .nt.`leng_id` = '".$_REQUEST['leng']."'" : false;
    if(!$result = $mysqli->query("SELECT n.*, GROUP_CONCAT(novedad_img ORDER BY img_orden ASC SEPARATOR ' - ') AS imagenes FROM `novedades` n LEFT JOIN `novedades_img` ni ON n.novedad_id = ni.novedad_id WHERE n.`novedad_id` = '".$_REQUEST['id']."' GROUP BY n.novedad_id")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila = $result->fetch_assoc())
	 {
	  $transaccion = "Editar";
	  $imagenes = explode(" - ", $fila['novedad_img']);
	  $fecha = explode(" - ", $fila['novedad_fecha']);
	  if(count($fecha) == 3)
	   {
		$mk_fecha = mktime(0, 0, 0, $fecha[1], $fecha[2], $fecha[0]);
		$dia = date(j, $mk_fecha);
		$mes = date(n, $mk_fecha);
		$anyo = date(Y, $mk_fecha);
	   }
	  $estado = $fila['novedad_estado'];
	  $result->close();
     }
   }
   
  if(!$consulta_lengs = $mysqli->query("SELECT l.leng_id, leng_nombre, nombre_nativo, xml_lang, dir FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.leng_id = ln.leng_id AND ln.leng_id_nombre = '1' ORDER BY iso_639_3")) die("\n".__LINE__." mySql: ".$mysqli->error); // WHERE leng_habilitado = '1'
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
  $sel_idioma = $_REQUEST['leng'];
  if(!$consulta_nov = $mysqli->query("SELECT novedad_titulo, novedad_subtitulo, novedad_texto FROM `novedades_textos` WHERE leng_id = '".$_REQUEST['leng']."' AND `novedad_id` = '".$_REQUEST['id']."' AND `nov_version_act` = 1")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_nov = $consulta_nov->fetch_row())
   {
	$titulo = $fila_nov[0];
	$subtitulo = $fila_nov[1];
	$texto = $fila_nov[2];
	$consulta_nov->close();
   }

  $estado_arr[$estado] = " checked=\"checked\"";
  echo "
	<form name=\"formedicion\" method=\"post\" action=\"".php_self()."_guardar".$sesion1."\" target=\"frguardar\">
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"id\" value=\"".$fila['novedad_id']."\" />";

?>
	 <input type="hidden" name="hdnoticias" id="hdnoticias" value="<?php echo $fila['novedad_img']; ?>" />
	 <input type="hidden" name="dia" value="<?php echo $dia; ?>" />
	 <input type="hidden" name="mes" value="<?php echo $mes; ?>" />
	 <input type="hidden" name="anyo" value="<?php echo $anyo; ?>" />
	 <input type="hidden" name="estado" value="<?php echo $estado; ?>" />
<?php if($lengs_tot == 1) echo "<input type=\"hidden\" name=\"leng[]\" value=\"".key($idiomas_arr)."\" />\n"; ?> 
	<textarea name="descripcion" id="descripcion" style="display:none;" cols="" rows=""><?php echo $fila['novedad_texto']; ?></textarea>
	<table class="tabla" <?php if($lengs_tot > 1) echo "style=\"width:100%;\""; ?>>
	 <thead>
	  <tr>
	   <th <?php if($lengs_tot > 1) echo "colspan=\"2\""; ?>><?php echo $transaccion; ?> novedad</th></tr>
	 </thead>
	 <tbody>
	  <tr>
<?php

  $h = 1;
  $float = array(1 => "right", "left");
  $ancho = array(1 => 98, 2);
  $visibilidad = array(1 => "hidden", "visible");
  $floatdir = " gl";
  $r = -1;
  if($lengs_tot > 2) $lengs_tot = 2;
  for($i = 0; $i < $lengs_tot; $i++)
   {
    $h = ($i+1);
    
    echo "
	   <td id=\"celda${h}\" style=\"width:${ancho[$h]}%;padding:0 0 3px;vertical-align:top;\">";
	if($lengs_tot > 1) echo "<div style=\"text-align:${float[$h]};padding:0 2px;visibility:${visibilidad[$h]};\"><a href=\"javascript:moverDivisor(${r});\">&${floatdir[$h]}t;</a></div>";
	echo "<div id=\"div${h}\" style=\"padding:0;overflow:hidden;white-space:nowrap;$mostrar\">";
	echo "<table><tbody>";
	if($lengs_tot > 1)
	 {
	  echo "<tr><td><label for=\"leng$h\">Idioma</label>:</td><td><select name=\"leng[]\" id=\"leng$h\" onchange=\"cargarXMLLeng(this, ${i}, ${id});\">";
	  if($i == 1) echo "<option value=\"\">-- Seleccione --</option>";
	  foreach($idiomas_arr AS $idioma_id => $idioma_nombre)
	   {
	    echo "<option value=\"$idioma_id\"";
	    if($i == 0 && $sel_idioma == $idioma_id) echo " selected=\"selected\"";
	    if($i == 1 && $sel_idioma == $idioma_id) echo " disabled=\"disabled\"";
	    echo ">${idioma_nombre}</option>";
	   }
	  //$sel_idioma = false;
	  $mostrar = "display: none;";
	  echo "</select></td></tr>";
	 }
	echo "
<tr><td><label for=\"titulo$h\">T&iacute;tulo</label>:</td><td><input type=\"text\" name=\"titulo[]\" id=\"titulo$h\" value=\"${titulo}\" size=\"32\" maxlength=\"32\" lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" xml:lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" dir=\"${idiomas_dir_arr[$idiomas_id_arr[$i]]}\" /></td></tr>
<tr><td><label for=\"subtitulo$h\">Subt&iacute;tulo</label>:</td><td><input type=\"text\" name=\"subtitulo[]\" id=\"subtitulo$h\" value=\"$subtitulo\" size=\"32\" maxlength=\"32\" lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" xml:lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" dir=\"${idiomas_dir_arr[$idiomas_id_arr[$i]]}\" /></td></tr>
</tbody></table>
<label for=\"texto$h\">Texto</label>:<br /><textarea name=\"texto[]\" id=\"texto${h}\" cols=\"\" rows=\"30\" style=\"width:98%;\" lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" xml:lang=\"${idiomas_xmllang_arr[$idiomas_id_arr[$i]]}\" dir=\"${idiomas_dir_arr[$idiomas_id_arr[$i]]}\">$texto</textarea></div></td>";
	$r += 2;
	$titulo = $subtitulo = $texto = false;
   }

?>	   
	   </tr>
	 </tbody>
	</table>
	</form>
<?php

  if($lengs_tot == 2)
   {

?>
<script type="text/javascript" defer="defer">
  var divisorPos = -1;
  var texto1 = document.getElementById('texto1');
  var texto2 = document.getElementById('texto2');
  var div1 = document.getElementById('div1');
  var div2 = document.getElementById('div2');
  var celda1 = document.getElementById('celda1');
  var celda2 = document.getElementById('celda2');
</script>
<?php

   }

?>
<!--  -->

<?php
/*
	 <tr>
	  <td width="60"><label for="titulo">T&iacute;tulo</label>:</td>
	  <td><input type="text" name="titulo" id="titulo" value="<?php echo $fila['novedad_titulo']; ?>" size="30" maxlength="30" /></td></tr>
	 <tr>
	  <td colspan="2">Descripci&oacute;n:<div style="text-align:center;padding-top:4px;"><textarea name="descripcion2" id="descripcion2" style="width:98%;" rows="15"><?php echo $fila['novedad_texto']; ?></textarea></div></td></tr>
	 </tbody>
*/
?>

	<form name="en" action="img_subir<?php echo $sesion1; ?>" target="frguardar" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="carpeta" value="novedades" />
	 <input type="hidden" name="borrar" value="" />
	<table id="tabladesc" class="tabla">
	 <tr>
	  <td width="60">Imagen:</td>
	  <td><div id="cargandonovedades_img" style="display:<?php if(!empty($fila['novedad_img'])) { echo "block;\"><img src=\"imagen?archivo=img/noticias/".$fila['novedad_img']."&amp;max=120\" alt=\"".$fila['novedad_img']."\" /"; } else { echo "none;\""; } ?>></div><input type="file" name="novedades_img" onchange="subirImg(this, 'formedicion');" />&nbsp;<input type="button" name="imagenserv" value="Examinar servidor..." onclick="abrirModal('./examinar?carpeta=noticias&amp;uso=noticias<?php echo $sesion2; ?>', 510, 354);" /></td></tr>
	 <tr>
	  <td>Fecha:</td>
	  <td><input type="text" name="fechadia" value="<?php echo $dia; ?>" size="2" maxlength="2" />&nbsp;<select name="fechames"><?php

  for($m = 1; $m <= count($meses); $m++)
   {
	echo "<option value=\"".$m."\"";
	if($m == $mes) echo " selected=\"selected\"";
	echo ">".substr($meses[$m], 0, 3)."</option>";
   }

?></select>&nbsp;<input type="text" name="fechaanyo" value="<?php echo $anyo; ?>" size="4" maxlength="4" /></td></tr>
	 <tr>
	  <td>Estado:</td>
	  <td><input type="radio" name="estado2" id="estado_1" value="1" onchange="document.forms['formedicion'].estado.value=this.value;"<?php echo $estado_arr[1]; ?> /><label for="estado_1">&nbsp;Habilitado</label><br /><input type="radio" name="estado2" id="estado_2" value="2" onchange="document.forms['formedicion'].estado.value=this.value;"<?php echo $estado_arr[2]; ?> /><label for="estado_2">&nbsp;Inhabilitado</label><br /><input type="radio" name="estado2" id="estado_3" value="3" onchange="document.forms['formedicion'].estado.value=this.value;"<?php echo $estado_arr[3]; ?> /><label for="estado_3">&nbsp;Habilitar en la fecha indicada</label></td></tr>
	</table>
	<table class="tabla">
	 <tr id="avisoguardar" style="display:none;"><td><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']."&amp;sesion=".$sesion; ?>">Regresar</a><!-- &nbsp;-&nbsp;<a href="javascript:resetearNotForm();">Agregar novedad</a> --></div></td></tr>
	 <tr>
	  <td align="center"><input type="button" value="Cancelar" onclick="document.location.href='<?php echo php_self()."?de=".$_REQUEST['de']."&amp;sesion=".$sesion; ?>'" />&nbsp;&nbsp;<input type="button" name="confirmar" id="guardar" value="Guardar" onclick="return validarForm(this, document.forms['formedicion']);" /></td></tr>
	</table>
	</form>
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>

<?php

 }


/* por omision */
if(!$no_poromision)
 {
  echo "
    <div id=\"opciones\"><a href=\"".php_self()."?ia=editar".$sesion2."\">Agregar Novedad</a></div>";

  $estado_arr = array(1 => "Habilitado", "Deshabilitado", "A partir de fecha indicada");
  $clase_estado = array(1 => "", "inactivo", "enproceso");
  $a = "25";
  $de = $_REQUEST["de"];
  if(empty($de))
   {
    $desde = "0";
    $limite_pre = $a;
   }
  else
   {
    $desde = $de;
    $limite_pre = ($de + $a);
   }
  $orden = $_REQUEST["orden"] ? $_REQUEST["orden"] : "1";
   
  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("`novedad_titulo`", "`iso_639_3`", "`novedad_estado`", "`novedad_fecha`");

  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));
  $filtro_leng = $_REQUEST['desp_leng'] ? "AND nt.leng_id = '".$_REQUEST['desp_leng']."'" : false;
  if(!$result = $mysqli->query("SELECT n.novedad_id, 
GROUP_CONCAT(DISTINCT novedad_titulo ORDER BY l.leng_id ASC SEPARATOR ' - ') AS titulo, 
novedad_estado, novedad_fecha, 
GROUP_CONCAT(DISTINCT l.leng_id ORDER BY l.leng_id ASC SEPARATOR ',') AS leng_ids, 
GROUP_CONCAT(DISTINCT iso_639_3 ORDER BY l.leng_id ASC SEPARATOR ' - ') AS idiomas, 
GROUP_CONCAT(DISTINCT leng_nombre ORDER BY l.leng_id ASC SEPARATOR ' - ') AS idiomas_nombres, 
GROUP_CONCAT(DISTINCT xml_lang ORDER BY l.leng_id ASC SEPARATOR ' - ') AS xml_leng, 
GROUP_CONCAT(dir ORDER BY l.leng_id ASC SEPARATOR ' - ') AS dir 
FROM `novedades` n JOIN `novedades_textos` nt ON n.`novedad_id` = nt.`novedad_id`, `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.leng_id = ln.leng_id AND ln.leng_id_nombre = '1' WHERE nt.leng_id = l.leng_id AND `nov_version_act` = '1' $filtro_leng GROUP BY novedad_id ORDER BY $db_orden LIMIT $desde,$a")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $result->fetch_array())
   {
    $clase_filas = array();
    echo "
	<form action=\"".php_self().$sesion1."\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	 <input type=\"hidden\" name=\"de\" value=\"${desde}\" />
	<table class=\"tabla\">";
	if(!$resultleng = $mysqli->query("SELECT nt.`leng_id`, `leng_nombre`, nombre_nativo, COUNT(*) AS `Filas` FROM `novedades_textos` nt, `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON  ln.leng_id = l.leng_id AND leng_id_nombre = '1' WHERE nt.leng_id = l.leng_id AND `nov_version_act` = 1 GROUP BY nt.`leng_id` ORDER BY `leng_nombre`")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($resultleng->num_rows > 1)
	 {
	  if($fila_leng = $resultleng->fetch_row())
	   {
	    echo "<caption><select name=\"desp_leng\" onchange=\"this.form.submit()\"><option value=\"0\">-- Todos --</option>";
	    do
	     {
	      $nombre = $fila_leng[1] ? $fila_leng[1] : $fila_leng[2];
		  echo "<option value=\"${fila_leng[0]}\"";
		  if($_REQUEST['desp_leng'] == $fila_leng[0]) echo " selected=\"selected\"";
		  echo ">${nombre} (${fila_leng[3]})</option>";
	     }while($fila_leng = $resultleng->fetch_row());
	    echo "</select><noscript>&nbsp;<input type=\"submit\" value=\"Actualizar\" /></noscript></caption>";
	   }
	 }
	echo "
	 <thead>
	 <tr class=\"orden\">
	  <td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td>
	  <td".$ordencolor[1]."><a href=\"".php_self()."?orden=".$ord_num[1].$sesion2."\" title=\"\">".$ord_fl[1]."T&iacute;tulo</a></td>
	  <td".$ordencolor[2]."><a href=\"".php_self()."?orden=".$ord_num[2].$sesion2."\" title=\"\">".$ord_fl[2]."Leng.</a></td>
	  <td".$ordencolor[3]."><a href=\"".php_self()."?orden=".$ord_num[3].$sesion2."\" title=\"\">".$ord_fl[3]."Estado</a></td>
	  <td".$ordencolor[4]."><a href=\"".php_self()."?orden=".$ord_num[4].$sesion2."\" title=\"\">".$ord_fl[4]."Fecha</a></td>
	 </tr>
	 </thead>
	 <tbody>";
    $tabindex = 1;
    do
     {
      $estado = $fila["novedad_estado"];
      $titulo = explode(" - ", $fila["titulo"]);
      $idioma_ids = explode(",", $fila["leng_ids"]);
      $idiomas = explode(" - ", $fila["idiomas"]);
      $xml_leng = explode(" - ", $fila["xml_leng"]);
      $dir = explode(" - ", $fila["dir"]);

      if($estado == 3 && $fila["novedad_fecha"] <= date("Y-m-d"))
       {
        $estado = 1;
        if(!$mysql->query("UPDATE `novedades` SET `novedad_estado` = '1' WHERE `novedad_id` = '".$fila["novedad_id"]."'")) die(mysql_error());
       }
      echo "
	 <tr";
      $clase_fila = "";
	  if($estado > 1)
	   {
		echo " class=\"".$clase_estado[$estado]."\"";
		$clase_fila = $clase_estado[$estado];
	   }
	  $clase_filas[] = $clase_fila;
      if(strlen($fila["novedad_titulo"]) > 20)
       { $novedad_titulo = substr($fila["novedad_titulo"], 0, 17)."&hellip;"; }
      else
       { $novedad_titulo = $fila["titulo"]; }
      echo ">
	  <td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"".$fila["novedad_id"]."\" tabindex=\"".$tabindex."\" onclick=\"selFila(this, '".$clase_fila."');\" /></td>
	  <td>";
	  for($n = 0; $n < count($titulo); $n++)
       {
        echo "<div lang=\"${xml_leng[$n]}\" xml:lang=\"${xml_leng[$n]}\" dir=\"${dir[$n]}\"><a href=\"".php_self()."?id=".$fila["novedad_id"]."&amp;de=".$de."&amp;leng=".$idioma_ids[$n].$sesion2."\">".$titulo[$n]."</a></div>";
       }
	  echo "</td>
	  <td><abbr style=\"cursor:help;\" title=\"${fila['idiomas_nombres']}\">${fila['idiomas']}</abbr></td>
	  <td>".$estado_arr[$estado]."</td>
	  <td>";
	  if($fila["novedad_fecha"] != "0000-00-00 00:00:00") formato_fecha($fila["novedad_fecha"], FALSE, TRUE);
	  echo "</td></tr>";
      $tabindex++;
     } while($fila = $result->fetch_array());
    $result->close();
    echo "
	 </tbody>
	</table>";
	if(count($clase_filas))
	 {
	  echo "
	<script type=\"text/javascript\">
	 var celdaClases = new Array();";
	  for($cf = 0; $cf < count($clase_filas); $cf++)
	   { echo "\r\t celdaClases[".$cf."] = '".$clase_filas[$cf]."';"; }
	  echo "
	</script>";
	 }
	echo "
  <div id=\"error_check_form\" class=\"div_error\" style=\"display:none;\">No ha seleccionado ninguna novedad.</div>
  <div style=\"padding:4px;\"><img src=\"./img/flecha_arr_der.png\" alt=\"Para los items seleccionados\" style=\"padding:0 5px;\" /><input type=\"submit\" name=\"mult_submit\" value=\"Habilitar\" />&nbsp;<input type=\"submit\" name=\"mult_submit\" value=\"Deshabilitar\" />&nbsp;<input type=\"submit\" name=\"mult_submit\" value=\"Eliminar\" onclick=\"return confBorrado('lista_item[]');\" /></div>";
    if(!$total = $mysqli->query("SELECT * FROM `novedades` GROUP BY `novedad_id`")) die("\n".__LINE__." mySql: ".$mysqli->error);
    //$num_total = $total->num_rows;
    if ($total->num_rows < $limite_pre) {$limit = $total->num_rows;} else {$limit = $limite_pre;}
    $paginas = ceil ($total->num_rows / $a);
    $ante = ($limit - ($desde + 1));
    if ($desde == 0) { $b = "1"; } else { $b = ($limit / $a); }
    $c = ceil ($b);
    echo "
	 <div id=\"listado_result\">Resultados <b>".($desde + 1)."</b> - <b>".$limit."</b> de <b>".$total->num_rows."</b><br />P&aacute;gina <b>".$c."</b> de <b>".ceil ($paginas)."</b><br />";
    if (($desde + 1) > 1) { echo "<a href=\"".php_self()."?de=".($desde - $a).$sesion2."\">&lt;&lt; Anterior</a>&nbsp;-&nbsp;"; }

    if($paginas > 1)
     {
      if($paginas > 20 && $c > 11)
       { $i = ($c - 9); }
      else
       { $i = 1; }
      $ib = $a;
      $ipg = ($c + 9);
      if($ipg > $paginas)
       { $ipg = $paginas; } 
      for($i;$i<=$ipg;$i++)
       {
        $ia = (($i - 1) * $a);
        if($i <> $c)
         { echo " <a href=\"".php_self()."?de=".$ia.$sesion2."\">".$i."</a> "; }
        else
         { echo " <b>".$i."</b> "; }
       }
     }

    if ($total->num_rows > $limit)
     { echo "&nbsp;-&nbsp;<a href=\"".php_self()."?de=".($desde + $a).$sesion2."');\">Siguiente &gt;&gt;</a>"; }
    echo "</div>
	</form>";
	$total->close();
   }
  else
   { tabla_informacion("No existen novedades en ".$tipos[$cat]."."); }
 }

include('iapie.php');

?>