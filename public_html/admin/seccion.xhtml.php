<?php

//header("Content-type: application/xhtml+xml; charset=utf-8");


$titulo = "Galerías";
$seccion = "galerias";
$seccion_id = $_REQUEST['id'];
/*
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
*/

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

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

/* por omision */
if(!$no_poromision)
 {
  //$cat_superior = current($secciones->actual_superior);
  include('inc/iaencab.php');
  echo "  <div id=\"div_mensaje\"";
  if(!$div_mensaje) echo " style=\"display:none;\"";
  echo ">$div_mensaje</div>\n";



  if($cat != 9) echo "  <div><a href=\"./".php_self()."?ia=agregar&amp;cat=${cat}\">Agregar galer&iacute;a</a></div>";
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 5) echo "<div><a href=\"conf_${seccion}\">Configuraci&oacute;n</a></div>\n";
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
include('inc/iapie.php');

?>