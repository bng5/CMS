<?php

$seccion_id = 6;
require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$titulo = "Monedas";
$seccion = "monedas";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

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
	  $mysqli->query("DELETE FROM `monedas` WHERE `id` = '{$modificar[$i]}' LIMIT 1");
      if($mysqli->affected_rows == 1)
       {
        $modificadas++;
		// (trigger)
		$mysqli->query("DELETE FROM `monedas_nombres` WHERE `id` = '{$modificar[$i]}'");// or die (__LINE__." - ".mysql_error());
       }
     }
   }

  if($modificadas > 0)
   {
    $div_mensaje = "Monedas ".$modificacion_tipo_accion.": ".$modificadas;
   }
 }

include('inc/iaencab.php');

// agregar / editar
if($_REQUEST["ia"] == "editar" || !empty($_REQUEST['id']))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  $estado = 0;
  $mysqli = BaseDatos::Conectar();
  if(!empty($_REQUEST['id']))
   {
    if(!$result = $mysqli->query("SELECT * FROM `monedas` m JOIN `monedas_nombres` mn ON m.`id` = mn.`id` AND `leng_id` = 1 WHERE m.`id` = '".$_REQUEST['id']."'")) echo "<br />".__LINE__." mySql: ".$mysqli->error;
	if($fila = $result->fetch_assoc())
	 {
	  $id = $fila['id'];
	  $transaccion = "Editar";
     }
   }
  $estado_arr[$estado] = " checked=\"checked\"";
  $dir_arr[$dir] = " checked=\"checked\"";
  echo "
	<form name=\"formedicion\" method=\"post\" action=\"".php_self()."_guardar\" target=\"frguardar\">
	 <input type=\"hidden\" name=\"ia\" value=\"modificar\" />
	 <input type=\"hidden\" name=\"id\" value=\"".$fila['id']."\" />";

  $lengs = array();
  $titulo = array();
  $cons_lengs = $mysqli->query("SELECT l.id, l.leng_cod, mn.nombre FROM lenguajes l LEFT JOIN monedas_nombres mn ON l.id = mn.leng_id AND mn.id = '${id}' ORDER BY leng_poromision DESC");
  if($fila_lengs = $cons_lengs->fetch_row())
   {
	$leng_poromision = $fila_lengs[0];
	do
	 {
	  $lengs[$fila_lengs[0]] = $fila_lengs[1];
	  $titulo[$fila_lengs[0]] = $fila_lengs[2];
	 }while($fila_lengs = $cons_lengs->fetch_row());
	$cons_lengs->close();
   }

?>

	<table class="tabla"
	 ><thead
	  ><tr
	   ><th colspan="2"><?php echo $transaccion; ?> moneda</th></tr
	 ></thead
	 ><tfoot
	  ><tr id="avisoguardar" style="display:none;"
	   ><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']; ?>">Regresar</a>&nbsp;-&nbsp;<a href="javascript:resetearForm('formedicion');">Agregar moneda</a></div></td></tr
	  ><tr
	   ><td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='<?php echo php_self()."?de=".$_REQUEST['de']."&amp;".SID; ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar" id="guardar" value="Guardar" /></td></tr
	 ></tfoot
	 ><tbody
	  ><tr
	   ><td><label>Nombre</label>:</td
	   ><td><ul class="campo_lista">
<?php

	foreach($lengs AS $leng_id => $leng_cod) echo "<li><label for=\"nombre${leng_cod}\" class=\"etiqueta_idioma\"><tt>(${leng_cod})</tt></label>&nbsp;<input type=\"text\" name=\"nombre[{$leng_id}]\" id=\"nombre{$leng_cod}\" value=\"".htmlspecialchars($titulo[$leng_id])."\" size=\"30\" maxlength=\"30\" /></li>";

?></ul></td></tr
	  ><tr
	   ><td><label for="codigo">C&oacute;digo internacional</label>:</td
	   ><td><input type="text" name="codigo" id="codigo" value="<?php echo $fila['codigo']; ?>" onkeyup="this.value=this.value.toUpperCase()" size="3" maxlength="3" /></td></tr
	  ><tr
	   ><td><label for="simbolo_izq">Símbolo a la izquierda</label>:</td
	   ><td><input type="text" name="simbolo_izq" id="simbolo_izq" value="<?php echo $fila['simbolo_izq']; ?>" size="3" maxlength="3" /></td></tr
	  ><tr
	   ><td><label for="simbolo_der">Símbolo a la derecha</label>:</td
	   ><td><input type="text" name="simbolo_der" id="simbolo_der" value="<?php echo $fila['simbolo_der']; ?>" size="3" maxlength="3" /></td></tr
	  ><tr
	   ><td><label for="decimales">Cantidad de decimales</label>:</td
	   ><td><input type="text" name="decimales" id="decimales" value="<?php echo $fila['decimales']; ?>" size="2" maxlength="2" /></td></tr
	  ><tr
	   ><td><label for="sep_decimales">Separador de decimales</label>:</td
	   ><td><input type="text" name="sep_decimales" id="sep_decimales" value="<?php echo $fila['sep_decimales']; ?>" size="1" maxlength="1" /></td></tr
	  ><tr
	   ><td><label for="sep_miles">Separador de miles</label>:</td
	   ><td><input type="text" name="sep_miles" id="sep_miles" value="<?php echo $fila['sep_miles']; ?>" size="1" maxlength="1" /></td></tr
	 ></tbody
	></table>
	</form>
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>

<?php

 }

/* por omision */
if(!$no_poromision)
 {
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo "
    <div id=\"opciones\"><a href=\"".php_self()."?ia=editar\">Agregar moneda</a></div>";
   }

  $orden = empty($_REQUEST["orden"]) ? 1 : $_REQUEST["orden"];
  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("m.`codigo`", "mn.`nombre`");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));
  $mysqli = BaseDatos::Conectar();
  $cons_total = $mysqli->query("SELECT id FROM `monedas`");// or die("<br />Error de total: ".mysql_error());
  $total = $cons_total->num_rows;
  if($total > 0)
   {
	$a = 25;
	$paginas = ceil($total / $a);
	$pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
	if($pagina > $paginas) $pagina = $paginas;
	$desde = ($pagina - 1) * $a;
	if(!$result = $mysqli->query("SELECT m.id, m.codigo, mn.nombre FROM `monedas` m LEFT JOIN `monedas_nombres` mn ON m.`id` = mn.`id` AND `leng_id` = 1 ORDER BY $db_orden LIMIT $desde,$a")) echo "<br />\n".__LINE__." mySql: ".$mysqli->error;
	if($fila = $result->fetch_array())
	 {
	  echo "
	<form action=\"".php_self()."?orden=".$ord_num[1]."&amp;pagina=${pagina}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	<table class=\"tabla\"
	 ><thead
	  ><tr class=\"orden\"";
	  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 3)
	   {
	    echo "
	   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td";
	   }
	  echo "
	   ><td".$ordencolor[1]."><a href=\"".php_self()."?orden=".$ord_num[1]."\" title=\"C&oacute;digo ISO 639-3\">".$ord_fl[1]."C&oacute;digo</a></td
	   ><td".$ordencolor[2]."><a href=\"".php_self()."?orden=".$ord_num[2]."\">".$ord_fl[2]."Nombre</a></td
	  ></tr
	 ></thead
	 ><tbody";
      $tabindex = 1;
      do
       {
        echo "
	  ><tr
	   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" id=\"lista_item".$fila[0]."\" value=\"".$fila[0]."\" tabindex=\"".$tabindex."\" onclick=\"selFila(this, '');\" /></td";

	    echo "
	   ><td><a href=\"".php_self()."?id=".$fila[0]."\">".$fila[1]."</a></td
	   ><td id=\"idiomaLabel".$fila[0]."\">".htmlspecialchars($fila[2])."</td
	  ></tr";
        $tabindex++;
       }while($fila = $result->fetch_array());
      echo "
	 ></tbody
	></table>";
	  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 3)
	   {
	    echo "
  <div id=\"error_check_form\" class=\"div_error\" style=\"display:none;\">No ha seleccionado ninguna moneda.</div>
  <div style=\"padding:4px;\"><img src=\"./img/flecha_arr_der\" alt=\"Para los items seleccionados\" style=\"padding:0 5px;\" />";
	    if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 4) echo "&nbsp;<input type=\"submit\" name=\"mult_submit\" value=\"Eliminar\" onclick=\"return confBorrado('lista_item[]', 'moneda(s)');\" />";
	    echo "</div>";
	   }
	 }  

    echo "
	 <div id=\"listado_result\">Resultados <b>${total}</b> en <b>${paginas}</b> p&aacute;gina/s";
	if($paginas > 1)
	 {
	  echo "<br />P&aacute;gina <b>${pagina}</b> de <b>${paginas}</b><br />";
      if($pagina > 1) echo "<a href=\"".php_self()."?pagina=".($pagina - 1)."\">&lt;&lt; Anterior</a>&nbsp;-";
	  $min_pagina = ($pagina > 9) ? ($pagina - 9) : 1;
	  $max_pagina = (($paginas - $pagina) > 9) ? ($pagina + 9) : $paginas;
	  for($pags = $min_pagina; $pags <= $max_pagina; $pags++) echo ($pags == $pagina) ? " <b>".$pags."</b> " : " <a href=\"".php_self()."?pagina=${pags}\">${pags}</a> ";
      if($paginas > $pagina) echo "-&nbsp;<a href=\"".php_self()."?pagina=".($pagina + 1)."\">Siguiente &gt;&gt;</a>";
     }
    echo "</div>
	</form>";
   }
  else echo "<div class=\"div_mensaje\">No se encontraron monedas en la base de datos.</div>";
 }

include('inc/iapie.php');

?>