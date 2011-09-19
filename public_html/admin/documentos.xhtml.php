<?php

if(empty($_REQUEST['obra'])) header("Location: /obras");

$titulo = "Documentos";
$seccion = "documentos";
$seccion_id = 15;

require('../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../inc/ad_sesiones.php');

$superior = $_REQUEST['superior'] ? $_REQUEST['superior'] : 0;
$obra = $_REQUEST['obra'];

function subcategoria($subcat, $n, $seleccionado, $exclusion)
 {
  global $mysqli;

/*
  $categorias = array();
  if(!$tbsubcat = $mysqli->query("SELECT aca.id, superior, titulo FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '${leng}' ORDER BY `superior`, `orden`")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
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

/* por omision */
//if(!$no_poromision)
$consulta = $mysqli->query("SELECT titulo FROM archivos_categorias_textos WHERE id = '${superior}'");// or die("955 ".mysql_error());
if($fila = $consulta->fetch_row()) $cat_nombre = " ({$fila[0]})";

  $pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
  $a = is_numeric($_REQUEST["resultados"]) ? floor($_REQUEST["resultados"]): 25;
  $desde = ($pagina-1)*$a;
  $superior = is_numeric($_REQUEST["superior"]) ? floor($_REQUEST["superior"]): 0;

  if(!$consulta_obra = $mysqli->query("SELECT titulo FROM galerias_textos WHERE galeria_id = '".$_REQUEST['obra']."' LIMIT 1")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_obra = $consulta_obra->fetch_row()) $obra_nombre = $fila_obra[0];
  echo "<h4>${obra_nombre}${cat_nombre}</h4>";
  //echo "<div><a href=\"".php_self()."?ia=agregar&amp;superior=${superior}\">Agregar categor&iacute;a</a></div>";
  if(!$consulta = $mysqli->query("SELECT aca.id, titulo, estado FROM `archivos_categorias` aca LEFT JOIN `archivos_categorias_textos` act ON aca.id = act.id AND leng_id = '${leng}' WHERE `superior` = '$superior' ORDER BY `orden` LIMIT ${desde}, ${a}")) die("<br />\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
    echo "
		<form action=\"".php_self()."\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\"
		 ><thead
		  ><tr class=\"orden\"
		   ><!-- td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
		   --><td>Categorías</td
		   ><!-- td style=\"width:20px;\">&nbsp;</td
		  --></tr
		 ></thead
		 ><tbody";
    do
     {
	  echo "
		  ><tr
		   ><!-- td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"${fila[0]}\"/></td
		   --><td><b><a href=\"".php_self()."?obra=".$_REQUEST['obra']."&amp;superior=${fila[0]}\">${fila[1]}</a></b></td
		   ><!-- td style=\"text-align:center;\"><a href=\"".php_self()."?cat=${fila[0]}\"><img src=\"./img/b_edit\" alt=\"Editar\" /></a></td
		  --></tr";
     }while($fila = $consulta->fetch_row());
?>
		 ></tbody
		></table>
  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ninguna novedad.</div>
  <div id="listado_opciones" style="padding:4px;display:none;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado('lista_item[]');" /></div>
  <div id="listado_result"></div>
	 </form>
<?php
   }

  
  // los archivos
  if(!empty($_REQUEST['superior']))
   {
	echo "<a href=\"".php_self()."?obra=${obra}\">Subir un nivel</a>";

?>
	<table id="tablasubcategorias" class="tabla">
	 <colgroup>
	  <col width="330"></col>
	  <col width="20"></col>
	  <col width="20"></col>
	 </colgroup>
	 <thead
	  ><tr
	   ><th colspan="3">Documentos</th
	  ></tr
	 ></thead
	 ><tbody id="subcategorias"
<?php

	$resultdoc = $mysqli->query("SELECT id, nombre_simbolico, archivo FROM archivos ar LEFT JOIN archivos_a_categorias aac ON ar.id = aac.archivo_id WHERE aac.categoria_id= '${superior}' AND aac.obra_id = '${obra}' ORDER BY 2");// or die("955 ".mysql_error());
	if($fila_doc = $resultdoc->fetch_row())
	 {
	  do
	   {
	    echo "
	  ><tr id=\"filadoc".$fila_doc[0]."\"
	   ><td
	    ><div id=\"nombredoc".$fila_doc[0]."\" style=\"display:none;\"><input type=\"text\" name=\"archivo_nombre\" value=\"".$fila_doc[1]."\" onblur=\"renombrarDoc(this.value, ".$fila_doc[0].",${superior}, ${obra});\" /></div
	    ><div id=\"linkdoc".$fila_doc[0]."\"><a href=\"/archivo?n=".$fila_doc[2]."\">".$fila_doc[1]."</a></div></td
	   ><!-- td>";
/*	  if(!empty($fila_doc[2]))
	   { echo "<a href=\"#\" onclick=\"verLogo('".$fila_doc[2]."');\"><img src=\"./img/imagen.png\" border=\"0\" width=\"14\" height=\"14\" alt=\"\" /></a>"; }
	  else
	   { echo "&nbsp;"; }
*/
	    echo "</td
	   --><td style=\"text-align:center;\"><a href=\"#\" onclick=\"return editarDoc('".$fila_doc[0]."', true);\"><img src=\"./img/b_edit\" border=\"0\" alt=\"Renombrar\" /></a></td
	   ><td style=\"text-align:center;\"><a onclick=\"return eliminarDoc(".$fila_doc[0].", '".$fila_doc[1]."', ${superior}, ${obra})\" href=\"#\"><img src=\"./img/b_drop.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Eliminar\" /></a></td
	  ></tr";
	   }while($fila_doc = $resultdoc->fetch_row());
	 }
     
?>
	 ></tbody>
	</table>
	<!--  onsubmit="return validar(this);" -->
	<form action="./archivos_subir" method="post" name="formdoc" enctype="multipart/form-data" target="frguardar">
	 <input type="hidden" name="cat" value="<?php echo $_REQUEST['superior']; ?>" />
	 <input type="hidden" name="obra" value="<?php echo $_REQUEST['obra']; ?>" />
	<table class="tabla" style="width:390px;"
	 ><tbody
	  ><tr class="orden"
	   ><th>Agregar documento</th></tr
	  ><tr
	   ><td><label for="campo_archivo">Documento:</label> <input type="file" name="archivo" id="campo_archivo" onchange="subirDoc(this);" /></td></tr
	  ><tr style="display:none;" id="filadocestado"
	   ><td colspan="2" style="text-align:center;"><span id="docestado"></span><!-- input type="submit" name="subir" value="Subir" / --></td></tr
	 ></tbody
	></table>
	</form>
<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>
<?php

   }
  //$root->setAttribute("pagina", $pagina);

//$root->setAttribute("paginas", ceil($total/$a));

// }
include('iapie.php');

?>