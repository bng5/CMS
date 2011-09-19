<?php

$seccion_id = 1;
require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$titulo = "Idiomas";
$seccion = "idiomas";

$mysqli = BaseDatos::Conectar();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
<!-- script type="application/x-javascript" src="/js/json2.js" charset="utf-8"></script -->

<?php

/*
// modificar
if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
 {
  $modificar = $_POST['lista_item'];
  $modificadas = 0;
  $mysqli = BaseDatos::Conectar();
  // borrar
  if($_POST['mult_submit'] == "Eliminar")
   {
    $modificacion_tipo_accion = "eliminados";
    for($i = 0; $i < count($modificar); $i++)
     {
	  $mysqli->query("DELETE FROM `lenguajes` WHERE `id` = '".$modificar[$i]."' AND leng_poromision IS NULL LIMIT 1");
      if($mysqli->affected_rows == 1)
       {
        $modificadas++;
		// (trigger)
		$mysqli->query("DELETE FROM `lenguajes_nombres` WHERE `id` = '".$modificar[$i]."' OR `leng_id_nombre` = '".$modificar[$i]."'");// or die (__LINE__." - ".mysql_error());
       }
     }
   }
  // habilitar
  elseif($_POST['mult_submit'] == "Habilitar")
   {
    $modificacion_tipo_accion = "habilitados";
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("UPDATE `lenguajes` SET `leng_habilitado` = '1' WHERE `id` = '".$modificar[$i]."' LIMIT 1");
      if($mysqli->affected_rows)
       { $modificadas++; }
     }
   }
  // deshabilitar
  elseif($_POST['mult_submit'] == "Deshabilitar")
   {
    $modificacion_tipo_accion = "deshabilitados";
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("UPDATE `lenguajes` SET `estado` = '0' WHERE `id` = '".$modificar[$i]."' LIMIT 1");
      if($mysqli->affected_rows)
       { $modificadas++; }
     }
   }

  if($modificadas > 0)
   {
    $div_mensaje = "Idiomas ".$modificacion_tipo_accion.": ".$modificadas;
    include('./idiomas_const.php');
   }
 }
*/

include('inc/iaencab.php');

/*
// agregar / editar
if($_REQUEST["ia"] == "editar" || !empty($_REQUEST['id']))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  $estado = 0;
  $dir = "ltr";
  if(!empty($_REQUEST['id']))
   {
	$mysqli = BaseDatos::Conectar();
    if(!$result = $mysqli->query("SELECT * FROM `lenguajes` l JOIN `lenguajes_nombres` ln ON l.`id` = ln.`id` AND `leng_id` = 65 WHERE l.`id` = '".$_REQUEST['id']."'")) echo "<br />".__LINE__." mySql: ".$mysqli->error;
	if($fila = $result->fetch_assoc())
	 {
	  $id = $_REQUEST['id'];
	  $transaccion = "Editar";
	  $dir = $fila['dir'];
	  $estado = $fila['estado'];
     }
   }
  $estado_arr[$estado] = " checked=\"checked\"";
  $dir_arr[$dir] = " checked=\"checked\"";
  echo "
	<form name=\"formedicion\" method=\"post\" action=\"/idiomas_guardar\" target=\"frguardar\">
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"id\" value=\"".$fila['id']."\" />";

  $leng = 1;

?>

	<table class="tabla"
	 ><thead
	  ><tr
	   ><th colspan="2"><?php echo $transaccion; ?> idioma</th></tr
	 ></thead
	 ><tfoot
	  ><tr id="avisoguardar" style="display:none;"
	   ><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="/idiomas?de=<?php echo $_REQUEST['de']; ?>">Regresar</a>&nbsp;-&nbsp;<a href="javascript:resetearForm('formedicion');">Agregar idioma</a></div></td></tr
	  ><tr
	   ><td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/idiomas?de=<?php echo $_REQUEST['de']."&amp;".SID; ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar" id="guardar" value="Guardar" /></td></tr
	 ></tfoot
	 ><tbody
	  ><tr
	   ><td><label for="nombre">Nombre (spa)</label>:</td
	   ><td><input type="text" name="leng[<?php echo $leng; ?>]" id="nombre" value="<?php echo $fila['nombre']; ?>" size="30" maxlength="30" /></td></tr
	  ><tr
	   ><td><label for="nombre_nativo">Nombre nativo</label>:</td
	   ><td><input type="text" name="nombre_nativo" id="nombre_nativo" value="<?php echo $fila['nombre_nativo']; ?>" size="30" maxlength="30" dir="<?php echo $dir; ?>" /></td></tr
	  ><tr
	   ><td><label for="iso_639_1">C&oacute;digo (ISO 639-1)</label>:</td
	   ><td><input type="text" name="iso_639_1" id="iso_639_1" value="<?php echo $fila['leng_cod']; ?>" size="2" maxlength="2" /></td></tr
	  ><tr
	   ><td><label for="iso_639_3">C&oacute;digo (ISO 639-3)</label>:</td
	   ><td><input type="text" name="iso_639_3" id="iso_639_3" value="<?php echo $fila['iso_639_3']; ?>" size="3" maxlength="3" /></td></tr
	  ><tr
	   ><td><label>Direcci&oacute;n del texto</label>:</td
	   ><td><input type="radio" name="dir" id="dirltr" value="ltr"<?php echo $dir_arr['ltr']; ?> onchange="this.form.nombre_nativo.dir=this.value" /><label for="dirltr">&nbsp;Izquierda a derecha</label><br /><input type="radio" name="dir" id="dirrtl" value="rtl"<?php echo $dir_arr['rtl']; ?> onchange="this.form.nombre_nativo.dir=this.value" /><label for="dirrtl">&nbsp;Derecha a izquierda</label></td></tr
	  ><tr
	   ><td><label>Estado</label>:</td
	   ><td><input type="radio" name="estado" id="estado0" value="0"<?php echo $estado_arr[0]; ?> /><label for="estado0">&nbsp;Inhabilitado</label><br /><input type="radio" name="estado" id="estado1" value="1"<?php echo $estado_arr[1]; ?> /><label for="estado1">&nbsp;Habilitado</label></td></tr
<?php
//	  <tr>
//	   <td><label for="charset">Juego de caracteres</label>:</td>
//	   <td><input type="text" name="charset" id="charset" value="<?php echo $fila['charset']; ?>" size="11" maxlength="11" /></td></tr>
//
?>
	  ><tr
	   ><td><label for="xml_lang">Lenguaje XML</label>:</td
	   ><td><input type="text" name="xml_lang" id="xml_lang" value="<?php echo $fila['xml_lang']; ?>" size="8" maxlength="8" /></td></tr
	 ></tbody
	></table>
	</form>
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
<?php

  if($id)
   {
	echo "
    <div>
     <a href=\"./idiomas_fecha?id=${id}\">Formato de fecha</a>
    </div>";
   }
 }
*/

/* por omision */
if(!$no_poromision)
 {

  echo "<div id=\"cargando\"> </div>";
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo "
    <div id=\"opciones\"><a href=\"/idiomas?ia=editar\" id=\"administ_idiomas\" onclick=\"return mostrarListaIdiomas()\">Agregar idiomas &gt;&gt;</a></div>";

	$superior = -1;
	if(!$result = $mysqli->query("SELECT l.id, l.codigo, l.superior, ln.nombre, l.estado, l.nombre_nativo, l.dir, l.leng_poromision FROM lenguajes l JOIN lenguajes_nombres ln ON l.id = ln.id AND ln.leng_id = 65 ORDER BY l.codigo, l.id")) echo "<br />\n".__LINE__." mySql: ".$mysqli->error;
	if($fila = $result->fetch_array())
	 {
	  do
	   {
	    $jsIdiomas[$fila['codigo']] = array('nombre' => $fila['nombre'], 'nombre_nativo' => $fila['nombre_nativo'], 'dir' => $fila['dir'], 'estado' => (int) $fila['estado']);
	   }while($fila = $result->fetch_array());
	 }
   }

  $estado_arr = array("Deshabilitado", "Habilitado");
  //$clase_estado = array("inactivo", "", "inactivo");
/*
0	 no está -	 no está
1	 está	 -	 está
2	 está	 -	 para publicar
3	 está	 -	 está para quitar
4	 está	 -	 no está
5	 no está -	 está para quitar
*/
  $clase_estado = array(1 => "", "inactivo", "", "inactivo", "suspendido");
  $iconos_pub = array(1 => 'inactive', 'add', 'delete', 'inactive', 'delete');
  $orden = empty($_REQUEST["orden"]) ? 1 : $_REQUEST["orden"];
  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("`codigo`", "`nombre`", "`nombre_nativo`");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));




  $cons_total = $mysqli->query("SELECT id FROM `lenguajes`");// or die("<br />Error de total: ".mysql_error());
  $total = $cons_total->num_rows;
  $a = 25;
  $paginas = ceil($total / $a);
  $pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
  if($pagina > $paginas) $pagina = $paginas;
  $desde = ($pagina - 1) * $a;



  echo "
<div id=\"idiomas_cont\" style=\"display:none;\"></div>
<script type=\"text/javascript\">
var celdaClases = {};
var IDIOMAS = ".($jsIdiomas ? json_encode($jsIdiomas) : '{}').";
var Pred = ".($jsPred ?  "'${jsPred}'" : 'null').";
var Pred_prov = ".($jsPred_prov ?  "'${jsPred_prov}'" : 'null').";\n";

unset($jsIdiomas);

    $pred_consulta = $mysqli->query("SELECT id FROM `lenguajes` WHERE `leng_poromision` = 1 LIMIT 1");
	if($pred = $pred_consulta->fetch_row()) echo " idPred = ".current($pred).";";
	else echo " document.getElementById('div_mensaje').innerHTML = 'Por favor seleccione un idioma predeterminado.';\n document.getElementById('div_mensaje').style.display = '';";
	echo "
</script>
	<form action=\"/idiomas?orden=".$ord_num[1]."&amp;pagina=${pagina}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	<table id=\"idiomas_disponibles\" class=\"tabla\"
	 ><thead
	  ><tr class=\"orden\"";
//	if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 3)
//	 {
//	  echo "
//	   ><!-- td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td";
//	 }
	echo "
	   ><td".$ordencolor[1].">{$ord_fl[1]}C&oacute;d.</td
	   ><td".$ordencolor[2].">".$ord_fl[2]."Idioma</td
	   ><td colspan=\"2\" style=\"width:40px;\"></td
	   ><td>Borrar</td
	  ></tr
	 ></thead
	 ><tfoot><tr><td colspan=\"5\" style=\"text-align:right;\"><input type=\"button\" value=\"Publicar cambios\" onclick=\"publicarCambios();\" /></td></tr></tfoot
	 ><tbody";

  //if(!$result = $mysqli->query("SELECT l.id, leng_cod, xml_lang, dir, leng_poromision, leng_habilitado, nombre_nativo, leng_id_nombre, nombre, l.bloqueado FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.`id` = ln.`id` AND `leng_id_nombre` = 1 ORDER BY $db_orden LIMIT $desde,$a")) echo "<br />\n".__LINE__." mySql: ".$mysqli->error;
  // LIMIT $desde,$a
  if(!$result = $mysqli->query("SELECT l.id, codigo, dir, leng_poromision, estado, nombre_nativo, ln.leng_id, ln.nombre FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.`id` = ln.`id` AND ln.`leng_id` = 65 WHERE l.estado != 0 ORDER BY $db_orden")) echo "<br />\n".__LINE__." mySql: ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
    $pasa_consulta = true;
    $clase_filas = array();

    $tabindex = 1;
    do
     {

//echo "><tr><td colspan=\"4\"><pre>".var_export($fila, true)."</pre></td></tr";
      $estado = $fila[4];
      echo "
	  ><tr id=\"fila{$fila[1]}\"";
      $clase_fila = "";
	  //if($estado == 0)
	  // {
		echo " class=\"".$clase_estado[$estado]."\"";
		$clase_fila = $clase_estado[$estado];
	  // }
	  $clase_filas[$fila[0]] = $clase_fila;
	  //href=\"/idiomas?id=".$fila[0]."\"
	  echo "
	   ><td><a onclick=\"return idiomaEditar('{$fila[1]}')\">".$fila[1]."</a></td
	   ><td id=\"idiomaLabel".$fila[0]."\"";
	  //if($fila[0] == 1)
	  // { echo " colspan=\"2\">".htmlspecialchars($fila[8]); }
	  //else
	  // {
	    echo ">".htmlspecialchars($fila[7])."</td
	   ><!-- td lang=\"".$fila[1]."\" xml:lang=\"".$fila[1]."\" dir=\"".$fila[2]."\">".htmlspecialchars($fila[6]);
	  // }
	  echo "</td
	   --><td>";
	  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 5)
	   {
		echo "<img src=\"/img/silk/{$iconos_pub[$estado]}\" onclick=\"conmSubEst('{$fila[1]}')\" alt=\"{$iconos_pub[$estado]}\" /></td><td>";
		$pred_llaves = array(1 => 'key', 'key_add', 'key_delete');
		echo "<img src=\"/img/silk/".($fila[3] ? $pred_llaves[$fila[3]] : (($estado == 1) ? 'bullet_green' : 'bullet_white'))."\" id=\"poromision".$fila[1]."\" onclick=\"predeterminado('{$fila[1]}')\" alt=\"\" title=\"Establecer idioma predeterminado\"/>";
	   }
	  elseif($fila[4] == 1)
	   { echo "<img src=\"./img/tic_bien\" width=\"10\" height=\"10\" alt=\"S\" />"; }
	  echo "</td
	   ><td";
	  //echo ($fila[9] == 1) ? " style=\"text-align:center;\"><img src=\"img/bloqueado\" alt=\"Bloqueado\" title=\"Bloqueado\" />" : ">";
	  echo ">";
	  if($estado != 5)
	    echo "<a onclick=\"quitarIdioma('{$fila[1]}')\">borrar</a>";
	  echo "</td
	  ></tr"; // ".$estado_arr[$estado]."
      $tabindex++;
     } while($fila = $result->fetch_row());

	if(count($clase_filas))
	 {

	 }
   }
?>
	 ></tbody
	></table>
	</form>
	<script type="text/javascript">

<?php

  if(!$pasa_consulta)
   {
    echo "mostrarListaIdiomas();";
   }//echo "<div id=\"div_mensaje\">No se encontr&oacute; ning&uacute;n idioma en la base de datos.</div>";
  else
   {
  	  //var celdaClases = new Array();";
	  foreach($clase_filas AS $cfk => $cfv)
	   { echo "\r\t celdaClases[${cfk}] = '${cfv}';"; }
   }
?>

</script>

<fieldset id="fs_idiomas"><legend>IDIOMAS</legend><pre></pre></fieldset>
<fieldset id="fs_idiomas_disp"><legend>IDIOMAS_DISP</legend><pre></pre></fieldset>
<?php

 }

include('inc/iapie.php');

?>