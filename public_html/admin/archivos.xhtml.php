<?php

$titulo = "Documentos";
$seccion = "documentos";
$seccion_id = 15;

require('../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../inc/ad_sesiones.php');


function subcategoria($subcat, $n, $seleccionado, $exclusion)
 {
  global $mysqli;

/*
  $categorias = array();
  if(!$tbsubcat = $mysqli->query("SELECT aca.id, superior, titulo FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '{$leng}' ORDER BY `superior`, `orden`")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
  if ($row_subcat = $tbsubcat->fetch_row())
   {
    do
     {
      $categorias[$row_subcat[1]][$row_subcat[0]] ;
	  echo "\n<br />\n";
     } while($row_subcat = $tbsubcat->fetch_row());
echo "<pre>";

echo "</pre>";
   }
*/


  $separador_niv = "&nbsp;&nbsp;&nbsp;&nbsp;";
//  if($exclusion)
//   { $excluir = "`cat_id` != '$exclusion' AND"; }
  if(!$tbsubcat = $mysqli->query("SELECT `categoria_id`, `categoria_superior`, `categoria` FROM `archivos_categorias` WHERE $excluir `categoria_superior` = '$subcat' ORDER BY `categoria`")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
  if ($row_subcat = $tbsubcat->fetch_row())
   {
    do
     {
      if ($exclusion == $row_subcat["cat_id"])
       { echo "<optgroup disabled=\"disabled\">"; }
      echo "<option value=\"".$row_subcat["cat_id"]."\"";
      if ($seleccionado == $row_subcat["cat_id"])
       { echo " selected=\"selected\""; }

      echo ">".str_repeat($separador_niv, $n).$row_subcat["cat_nombre"]."</option>\n";

      $subcat = $row_subcat["cat_id"];
      subcategoria($subcat, ++$n , $seleccionado, $exclusion);
      $n--;
      if ($exclusion == $row_subcat["cat_id"])
       { echo "</optgroup>"; }

     } while($row_subcat = $tbsubcat->fetch_row());
   }

 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('iaencab.php');

// agregar / editar
if($_REQUEST['ia'] == "agregarcat" || !empty($_REQUEST['cat']))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  $ubicacion = $_REQUEST['superior'] ? $_REQUEST['superior']: 0;
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

  if(!empty($_REQUEST['cat']))
   {
	//$sel_idioma = $_REQUEST['leng'];
	if(!$consulta_item = $mysqli->query("SELECT aca.id, titulo, texto, estado, superior FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '{$leng}' WHERE aca.`id` = '".$_REQUEST['cat']."' LIMIT 1")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila_item = $consulta_item->fetch_row())
	 {
	  $transaccion = "Editar";
	  $id = $fila_item[0];
	  $nombre = $fila_item[1];
	  $descripcion = $fila_item[2];
	  $ubicacion = $fila_item[4];
	  $consulta_item->close();
	  //$leng = $_REQUEST['leng'];
	 }
   }

  echo "
	<form name=\"formedicion\" id=\"formedicion\" action=\"".php_self()."_guardar\" target=\"frguardar\" method=\"post\">
	 <br />id <input type=\"text\" name=\"id\" value=\"{$id}\" />
	 <br />idioma <input type=\"text\" name=\"idioma\" value=\"{$leng}\" />
	 <br />ia <input type=\"text\" name=\"ia\" value=\"modificarcat\" />
	 <br />publicar <input type=\"text\" name=\"publicar\" value=\"0\" />\n";
  if($lengs_tot == 1) echo " <input type=\"hidden\" name=\"leng[]\" value=\"{$leng}\" />\n";
	 
?>
	<table class="tabla" <?php if($lengs_tot > 1) echo "style=\"width:100%;\""; ?>>
	 <thead>
	  <tr>
	   <th colspan="2"><?php echo $transaccion; ?> Galer&iacute;a</th></tr>
	 </thead>
	 <tfoot>
	  <tr>
	   <td><label for="ubicacion">Ubicaci&oacute;n</label>:</td>
	   <td>
<input type="text" name="ubicacion" id="ubicacion" value="<?php echo $ubicacion; ?>" size="2" />
<?php
//<select name="superior" id="superior">
//subcategoria(0, 0, $p_id, NULL);
//</select>
?>
</td></tr>
	 <tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']; ?>">Regresar</a></div></td></tr>
	  <tr>
	   <td colspan="2" align="center"><input type="button" value="Cancelar" onclick="document.location.href='<?php echo php_self(); ?>'" />&nbsp;&nbsp;<input type="button" name="btGuardar" id="guardar" value="Guardar" onclick="aceptarForm(document.forms['formedicion'], false);this.value='Guardando...'" />&nbsp;&nbsp;<input type="button" name="btPublicar" value="Guardar/Publicar" onclick="aceptarFormGal(document.forms['formedicion'], true);this.value='Guardando/Publicando...'" />&nbsp;&nbsp;<input type="button" name="btElimPublic" value="Eliminar Publicaci&oacute;n" onclick="eliminarPublicacion();" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for="nombre1">Nombre</label>:</td>
	   <td><input type="text" name="nombre[]" id="nombre1" value="<?php echo $nombre; ?>" size="30" maxlength="30" /></td></tr>
	  <tr>
	   <td><label for="descripcion1">Descripci&oacute;n</label>:</td>
	   <td><textarea name="descripcion[]" id="descripcion1" cols="30" rows="10"><?php echo $descripcion; ?></textarea></td></tr>
	 </tbody>
	</table>
	</form>
	<iframe id="frguardar" name="frguardar"></iframe><!--  style="display:none;" -->
<?php

 }


/* por omision */
if(!$no_poromision)
 {
  $pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
  $a = is_numeric($_REQUEST["resultados"]) ? floor($_REQUEST["resultados"]): 25;
  $desde = ($pagina-1)*$a;
  $superior = is_numeric($_REQUEST["superior"]) ? floor($_REQUEST["superior"]): 0;

echo "<p>{$pagina} {$a} {$desde}</p>";


echo "<div><a href=\"".php_self()."?ia=agregarcat&amp;superior={$superior}\">Agregar categor&iacute;a</a></div>";
  if(!$consulta = $mysqli->query("SELECT aca.id, titulo, estado FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '{$leng}' WHERE `superior` = '$superior' ORDER BY `orden` LIMIT {$desde}, {$a}")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
    echo "
		<form action=\"".php_self()."\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\"><thead><tr class=\"orden\"><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td>
<td>T&iacute;tulo</td>
<td style=\"width:20px;\">&nbsp;</td>
</tr></thead><tbody>";
    do
     {
	  echo "
 <tr>
  <td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\"/></td>
  <td><b><a href=\"".php_self()."?superior={$fila[0]}\">{$fila[1]}</a></b></td>
  <td style=\"text-align:center;\"><a href=\"".php_self()."?cat={$fila[0]}\"><img src=\"./img/b_edit\" alt=\"Editar\" /></a></td>
</tr>";
     }while($fila = $consulta->fetch_row());
?>

</tbody></table>
  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ninguna novedad.</div>
  <div id="listado_opciones" style="padding:4px;display:none;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado('lista_item[]');" /></div>
  <div id="listado_result"></div>
	 </form>
<?php
 }

/*
  if(!$consulta = $mysqli->query("SELECT aca.id, titulo, estado FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '{$leng}' WHERE `superior` = '$superior' ORDER BY `orden` LIMIT {$desde}, {$a}")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
    echo "
		<form action=\"".php_self()."\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\"><thead><tr class=\"orden\"><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td>
<td>T&iacute;tulo</td>
<td>Archivo</td>
<td>Fecha</td>
</tr></thead><tbody>";
    do
     {
	  echo "
 <tr>
  <td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\"/></td>
  <td>{$fila[1]}</td>
  <td>{$fila[2]}</td>
  <td>{$fila[3]}</td></tr>";
     }while($fila = $consulta->fetch_row());
?>

</tbody></table>
  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ninguna novedad.</div>
  <div id="listado_opciones" style="padding:4px;display:none;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado('lista_item[]');" /></div>
  <div id="listado_result"></div>
	 </form>
<?php
    }



  //$root->setAttribute("pagina", $pagina);
   }
//$root->setAttribute("paginas", ceil($total/$a));
*/
  echo "
		<!-- style=\"\" display:none; -->
";




 }
include('iapie.php');

?>