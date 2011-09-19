<?php

require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();
$mod = "listar";
//$cons_seccion = $mysqli->query("SELECT `nombre`, `identificador` FROM `secciones` WHERE `id` = '{$_REQUEST['seccion']}' LIMIT 1");

// AND `link` = '${mod}'
if($seccion = Seccion::carga($_REQUEST['seccion']))//$fila_seccion = $cons_seccion->fetch_row())
 {
  $titulo = $seccion->getNombre();//$fila_seccion[0];
  $seccion_id = (int) $_REQUEST['seccion'];
  $seccion_identificador = $seccion->getIdentificador();//$fila_seccion[1];
  require('inc/ad_sesiones.php');

/*

	+----+--------+---------+-----------------------+---------------------------+
	| id | tipo   | subtipo | nombre                | tabla valores por omisión |
	+----+--------+---------+-----------------------+---------------------------+
	|  1 | string |    NULL | Campo de texto        | items_valores             |
	|  2 | string |       1 | Color                 | items_valores             |
	|  3 | string |       2 | Contraseña            | NO                        |
	| 13 | string |       3 | Selector múltiple     | campos_opciones           |
	| 12 | string |       4 | Checkbox              | campos_opciones           |
	|  4 | date   |    NULL | Fecha y hora          | items_valores             |
	|  5 | date   |       1 | Fecha                 | items_valores             |
	| 15 | text   |    NULL | Texto                 | items_valores             |
	|  6 | int    |    NULL | Número natural (ℕ)    |                           |
	|  7 | int    |       1 | Dato externo          |                           |
	|  8 | int    |       2 | Imagen                | imagenes                  |
	|  9 | int    |       3 | Archivo               | archivos                  |
	| 10 | int    |       4 | Set de imágenes       |                           |
	| 14 | int    |       5 | Selector              | campos_opciones           |
	| 11 | int    |       8 | Radio                 | campos_opciones           |
	| 16 | num    |    NULL | Precio                | items_valores             |
	| 17 | num    |       1 | Número entero (ℤ)     | items_valores             |
	+----+--------+---------+-----------------------+---------------------------+

*/

//$camposC_opciones = array("11", "12", "13", "14");

$vista = new Vista_XHTML($seccion);
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <!-- link rel="stylesheet" type="text/css" media="all" href="/css/campos.css" / -->
*/
?>
 <style type="text/css">


div#arrastre_cont, div#arrastre_head {
	min-width:786px;
}
div#arrastre_cont div {
	padding-left:20px;
}

div#arrastre_cont_desact hr {
	visibility:hidden;
}

div#arrastre_cont hr {
	margin:0 0 0 47px;
	height:6px;
	border:2px solid #fff;
	color:#efefef;
	background-color:#efefef;
	clear:both;
}

div#arrastre_cont hr.act {
	color:#ffd799;
	background-color:#ffd799;
	border-color:#ffd799;

}


div.linea {
	clear:both;
}


div.colapsada div, div.colapsada hr {
	display:none;
}

div.linea span {
	background-color:#ffffff;
	float:right;
	margin-right:1px;
	padding:.5em;
	overflow:hidden;
	white-space:nowrap;
	width:100px;
	border-left:1px solid #cccccc;
	/*display:block;table-cell;*/
	/*border:1px solid red;*/
}

div#arrastre_head {
	padding-left:40px;
}

div#arrastre_head div.linea {
	background-color:#1B1C4A;
	float:left;
}

div#arrastre_head div.linea span {
	background-color:#1B1C4A;
	color:#D2D7DD;
}

div.linea span.celda1 {
	background-color:#FFFFFF;
	width:15px;
	float:left;
	border-left:none;
}

div.linea span.celda2 {
	width:auto;
	float:left;
	cursor:move;
}

div.linea span.celda10 {
	width:auto;
}
div.linea span.celda9, div.linea span.celda8 {
	width:20px;
	text-align:center;
}

div.linea span.celda4, div.linea span.celda5 {
width:40px;
}



table.tabla thead td {
	width:70px;
}

ul.listattr li {
	margin:1em .3em;
}


/*
#extra fieldset {
	display:none;
}
*/

tbody.conf_extra {
	display:none;
}
 </style>

 <script type="text/javascript" src="/js/configuracion.js" charset="utf-8"></script>

<?php
/*
 <script type="text/javascript">

var camposC_opciones = {<?php foreach($camposC_opciones AS $c) { echo "${sep}${c} : true"; $sep = ", "; } ?>};
function campo_opciones(id)
 {
  //var id = selector.options(index).value;
  var campo_opciones = document.getElementById('campo_opciones');
  campo_opciones.style.display = camposC_opciones[id] ? "block" : "none";
 }

 </script>element will be displayed as block-level or inline element depending on context
*/

  if($_POST['accion'] == "ag_atributo")
   {
    if(!empty($_POST['identificador']))
     {
	  setlocale(LC_CTYPE, 'es_UY.UTF-8');
	  $unarray = array();
	  if($_POST['tipo'] == 8 || $_POST['tipo'] == 10) $extra = "'array (0 => array (0 => \\'recortar\\',1 => 200,2 => 200,),1 => array (0 => \\'recortar\\',1 => 40,2 => 40,),)'";
	  elseif($_POST['tipo'] == 11) $extra = "'array(0 => \\'No\\', \\'Si\\')'";
	  elseif($_POST['tipo'] == 22) $extra = "'array(1 => 1,2,3,4,5)'";
	  else $extra = "NULL";
/*
 si es imagen se guarda este array en `extra`
$unarray[] = array("escalar", false, 225);
$unarray[] = array("recortar", 20, 20);
$elarray = str_replace(array("\n", "  "), "", var_export($unarray, true));
echo $elarray;
*/

	  $mysqli->query("INSERT INTO items_atributos (`identificador`, `sugerido`, `unico`, `tipo_id`, `extra`) VALUES ('".str_replace(" ", "_", strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $_POST['identificador'])))."', '{$_POST['sugerido']}', '{$_POST['unico']}', '{$_POST['tipo']}', ${extra})");
      if($id = $mysqli->insert_id)
       {
       	if(($_POST['tipo'] == 8 || $_POST['tipo'] == 10) && !@is_dir(RUTA_CARPETA.'public_html/img/0/'.$id))
       	 {
		  @mkdir(RUTA_CARPETA.'public_html/img/0/'.$id);
		  @mkdir(RUTA_CARPETA.'public_html/img/1/'.$id);
       	 }
       	$etiqueta = current($_POST['leng']) ? current($_POST['leng']) : $_POST['identificador'];
		$en_listado = 0;//$_POST['en_listado'] ? $_POST['en_listado'] : 0;
		$salida = 0;//($_POST['salida'] == 1) ? 0 : 1;
		$mysqli->query("INSERT INTO items_secciones_a_atributos (`seccion_id`, `atributo_id`, `en_listado`, `salida`) VALUES ('{$_REQUEST['seccion']}', '${id}', ${en_listado}, ${salida})");
		$eliminar_pub = true;
        foreach($_POST['leng'] AS $idioma_k => $idioma_v)
		  $mysqli->query("INSERT INTO items_atributos_n (`id`, `leng_id`, `atributo`) VALUES ('${id}', '${idioma_k}', '${idioma_v}')");
		/*
		if(in_array($_POST['tipo'], $camposC_opciones))
		 {
		  foreach($_POST['opciones'] AS $co)
		   {
		   	$primer = current($co);
		   	if(empty($primer)) continue;
			$mysqli->query("INSERT INTO campos_opciones (`campo_id`) VALUES (${id})");
			$co_id = $mysqli->insert_id;
			foreach($co AS $co_k => $co_v)
			 {
			  if(empty($co_v)) continue;
			  $mysqli->query("INSERT INTO campos_opciones_textos (`id`, `leng_id`, `texto`) VALUES (${co_id}, '${co_k}', '${co_v}')");
			 }
		   }
		 }
		*/
	/*
	| 13 | string |       3 | Selector múltiple     | campos_opciones           |
	| 12 | string |       4 | Checkbox              | campos_opciones           |
	| 14 | int    |       5 | Selector              | campos_opciones           |
	| 11 | int    |       8 | Radio                 | campos_opciones           |
	*/
       }
      //echo "No fue posible ingresar el atributo.";
     }
   }
  elseif($_POST['accion'] == "as_atributo")
   {
   	$eliminar_pub = true;
/*
 *    	$en_listado = array();
	if(!$consulta = $mysqli->query("SELECT atributo_id FROM items_secciones_a_atributos WHERE `seccion_id` = '{$_REQUEST['seccion']}' AND `en_listado` = 1")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila = $consulta->fetch_row())
	 {
	  do
	   {
		$en_listado[$fila[0]] = 1;
	   }while($fila = $consulta->fetch_row());
	  $consulta->close();
	 }
$array3 = array_diff_key($en_listado, $_POST['en_listado']);
$array4 = array_diff_key($_POST['en_listado'], $en_listado);
*/
	$mysqli->query("DELETE FROM items_secciones_a_atributos WHERE `seccion_id` = '{$_REQUEST['seccion']}'");
	if(count($_POST['attr']))
	 {
$xml = '';
	  $en_listado_cuenta = 0;
	  $orden = 0;
	  foreach($_POST['attr'] AS $attr_v)
	   {
	   	$orden++;
	   	if($_POST['en_listado'][$attr_v] == 1)
	   	 {
		  $en_listado_cuenta++;
		  $en_listado = ($en_listado_cuenta < 8) ? 1 : 0;
		 }
		else $en_listado = 0;
		$salida = ($_POST['salida'][$attr_v] == 1) ? 0 : 1;
		//$orden = $_POST['orden'][$attr_v] ? $_POST['orden'][$attr_v] : "NULL";
		$superior = $_POST['superior'][$attr_v] ? $_POST['superior'][$attr_v] : 0;

	   	$mysqli->query("INSERT INTO items_secciones_a_atributos (`seccion_id`, `atributo_id`, `orden`, `en_listado`, `salida`, `superior`) VALUES ('{$_REQUEST['seccion']}', '${attr_v}', {$orden}, ${en_listado}, ${salida}, ${superior})");
$xml .= "<a atributo_id='${attr_v}' orden='{$orden}' en_listado='${en_listado}' salida='${salida}' superior='{$_POST['superior'][$attr_v]}' />\n";
	   }
file_put_contents(RUTA_CARPETA."public_html/inc_xhtml/modelo_${seccion}", $xml);
	  $et = array();
	  if(!$consulta_etiq = $mysqli->query("SELECT l.codigo, ia.identificador, ian.atributo FROM items_secciones_a_atributos isaa JOIN items_atributos ia ON isaa.atributo_id = ia.id, items_atributos_n ian JOIN lenguajes l ON ian.leng_id = l.id WHERE isaa.atributo_id = ian.id AND isaa.seccion_id = ${seccion_id} AND isaa.en_listado = 1 ORDER BY l.leng_poromision DESC, ia.id")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila_etiq = $consulta_etiq->fetch_row())
	   {
	   	$poromision = $fila_etiq[0];
	   	$et[$fila_etiq[0]] = array();
		do
		 {
		  if(!$et[$fila_etiq[0]]) $et[$fila_etiq[0]] = $et[$poromision];
		  if(!empty($fila_etiq[2])) $et[$fila_etiq[0]][$fila_etiq[1]] = $fila_etiq[2];
		 }while($fila_etiq = $consulta_etiq->fetch_row());
		$consulta_etiq->close();
		foreach($et AS $et_leng => $et_arr)
		 {
		  //print_r($et_arr);
		  file_put_contents(RUTA_CARPETA."public_html/inc_xhtml/etiquetas_${seccion}.${et_leng}.php", "<?php\n\$etiquetas = ".var_export($et_arr, true).";\n?>");
		 }
	   }
	 }
   }
  if($eliminar_pub)
   {
	$mysqli->query("DROP TABLE `pub__${seccion}`");
	$div_mensaje = "Se ha borrado la publicación del listado para esta sección. Debe publicar los items nuevamente.";
	$mysqli->query("UPDATE items SET estado_id = 0 WHERE `seccion_id` = '{$seccion_id}'");
	if($en_listado_cuenta > 7) $div_mensaje .= "<br />\nSe asignaron al listado sólo los primeros 7 atributos seleccionados.";
   }
  include('./vistas/iaencab.php');

  $lenguajes = array();
  if(!$consulta_lengs = $mysqli->query("SELECT id, codigo, dir FROM lenguajes WHERE estado > 0 AND estado < 5 ORDER BY leng_poromision DESC, codigo")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_lengs = $consulta_lengs->fetch_assoc())
   {
	do
	 {
	  $lenguajes[] = $fila_lengs;
	 }while($fila_lengs = $consulta_lengs->fetch_assoc());
   }

  $tipos = array();
  if(!$consulta = $mysqli->query("SELECT at.id, atn.nombre, at.tipo FROM atributos_tipos at JOIN atributos_tipos_nombres atn ON at.id = atn.id AND atn.leng_id = '1' ORDER BY atn.nombre")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
    do
     {
	  $tipos[$fila[0]] = array($fila[1], $fila[2]);
	  /*
      echo "
	    <option value=\"{$fila[0]}\"";
	  if($fila[0] == 1) echo " selected=\"selected\"";
	  echo ">{$fila[1]}</option>";
       */
     }while($fila = $consulta->fetch_row());
    $consulta->close();
   }
    echo "
  <p>&#8203;</p><form action=\"/conf_nuevo_campo\" target=\"_blank\" onsubmit=\"return enviarPost(this, '/conf_nuevo_campo', 'nodosAttr', this.previousSibling)\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"ag_atributo\" />
   <input type=\"hidden\" name=\"seccion\" value=\"{$_REQUEST['seccion']}\" />";
?>

	<table class="tabla">
	 <thead>
	  <tr>
	   <td colspan="2">Agregar atributo</td></tr>
	 </thead>
	 <tfoot>
	  <tr>
	   <td colspan="2" style="text-align:center;"><input type="submit" value="Aceptar" /></td></tr>
	 </tfoot>


<tbody style="border:1px solid green;">
	  <tr>
	   <td><label for="identificador">Identificador:</label></td>
	   <td><input type="text" name="identificador" id="identificador" size="15" maxlength="15" /></td></tr>
	  <tr>
	   <td><label for="tipo">Tipo:</label></td>
	   <td>
	  <select name="tipo" id="tipo" onchange="mostrarExtra(this.options[this.selectedIndex].value)">
	  	<option value=""> -- Seleccione -- </option>
	    <option value="1">Campo de texto</option>
		<option value="15">Área de texto</option>
		<option value="6">Número</option>
		<option value="2">Color</option>
		<option value="16">Precio</option>
		<option value="11">Lista de opciones</option>
	    <option value="4">Fecha y hora</option>
		<option>Duración</option>
	    <option value="22">Enlace externo (dato)</option>
		<option value="7">Dato externo</option>
	   <optgroup label="Objetos">
	    <option value="9">Archivo</option>
	    <option value="8">Imagen</option>
	    <option value="10">Galería de imágenes</option>
		<option value="26">Enlace</option>
	   </optgroup>
	   <optgroup label="Grupos">
	    <option value="19">Área</option>
		<option value="23">Formulario</option>
	   </optgroup>
	   <optgroup label="Ingreso de datos">
		<option>Campo de texto</option>
		<option>Contraseña</option>
		<option>Área de texto</option>
		<option>Selector</option>
		<option>Selector múltiple</option>
	   </optgroup>
	   <optgroup label="Servicios">
	    <option>Fuente web</option>
	    <option>YouTube Video</option>
	   </optgroup>
	   <optgroup label="Obsoletos">
	    <option value="3">Contraseña</option>
	   </optgroup>
	  </select></td></tr>
<?php

echo "
	  <tr>
	   <td><label>Obligatorio:</label></td>
	   <td><input type=\"radio\" name=\"sugerido\" id=\"sugerido2\" value=\"2\" /><label for=\"sugerido2\">Si</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido1\" value=\"1\" checked=\"checked\" /><label for=\"sugerido1\">No</label></td></tr>
	  <!-- tr>
	   <td><label>Único:</label></td>
	   <td><input type=\"radio\" name=\"unico\" id=\"unico1\" value=\"1\" checked=\"checked\" /><label for=\"unico1\">Si</label> <input type=\"radio\" name=\"unico\" id=\"unico0\" value=\"0\" /><label for=\"unico0\">No</label></td></tr-->
	  <!-- tr>
	   <td><label>Publicación:</label></td>
	   <td><ul class=\"campo_lista\"><li><input type=\"checkbox\" name=\"en_listado\" id=\"en_listado\" value=\"1\" /> <label for=\"en_listado\">En listado</label></li><li><input type=\"checkbox\" name=\"salida\" id=\"salida\" value=\"1\" /> <label for=\"salida\">Sin salida</label></li></ul></td></tr -->
	  <tr>
	   <td><label>Etiqueta/s:</label></td>
	   <td><ul class=\"campo_lista\">";
	  foreach($lenguajes AS $fila_lengs)
	   {
	    echo "
	    <li><label for=\"leng{$fila_lengs['id']}\"><tt>({$fila_lengs['codigo']})</tt></label> <input type=\"text\" name=\"leng[{$fila_lengs['id']}]\" id=\"leng{$fila_lengs['id']}\" /></li>";
	   }
	  echo "</ul></td></tr>
	 </tbody>";
?>

<tbody id="extra1" class="conf_extra">
 <tr><th colspan="2"><input type="hidden" name="conf[1][nodo_tipo]" value="1" />
 <span>Campo de texto</span>
 <p>1 , 21 </p>
</th></tr>
  <tr>
   <td>Multilíngüe</td>
   <td><input type="radio" name="conf[1][idmodif]" id="conf_1_idm0" value="0" checked="checked" /><label for="conf_1_idm0">Si</label> <input type="radio" name="conf[1][idmodif]" id="conf_1_idm20" value="20" /><label for="conf_1_idm20">No</label> </td>
  </tr>
  <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="conf[1][unico]" id="conf_1_unico1" value="1" checked="checked" disabled="disabled" /><label for="conf_1_unico1">único</label> <input type="radio" name="conf[1][unico]" id="conf_1_unico0" value="0" disabled="disabled" /><label for="conf_1_unico0">lista</label></td></tr>
  <tr>
   <td><label for="conf_1_maxl">Largo máximo</label></td>
   <td><input type="text" name="conf[1][extra][maxl]" id="conf_1_maxl" value="200" size="3" /></td>
  </tr>
  <tr>
   <td>Con formato</td>
   <td><input type="radio" name="conf[1][cformato]" id="conf_1_cformato1" value="1" /><label for="conf_1_cformato1">Si</label> <input type="radio" name="conf[1][cformato]" id="conf_1_cformato0" value="0" checked="checked" /><label for="conf_1_cformato0">No</label> </td>
  </tr>
  <tr>
   <td>Valor por omisión</td>
   <td><ul class="campo_lista">
<?php
	  foreach($lenguajes AS $fila_lengs)
	   {
	    echo "
	    <li><label for=\"conf1_leng{$fila_lengs['id']}\"><tt>({$fila_lengs['codigo']})</tt></label> <input type=\"text\" name=\"conf[1][leng][{$fila_lengs['id']}]\" id=\"conf1_leng{$fila_lengs['id']}\" /></li>";
	   }
?></ul></td>
  </tr>
 </tbody>

<tbody id="extra15" class="conf_extra">
<tr><th colspan="2">
 <span>Área de texto</span>
 <p>15</p>
</th></tr>
  <tr>
   <td>Multilíngüe</td>
   <td><input type="radio" name="extra[15][mlingue]" checked="checked" />Si <input type="radio" name="extra[15][mlingue]" />No </td>
  </tr>
  <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[15][unico]" id="unico1" value="1" checked="checked" /><label for="unico1">Si</label> <input type="radio" name="extra[15][unico]" id="unico0" value="0" /><label for="unico0">No</label></td></tr>
  <tr>
   <td>Largo máximo</td>
   <td><input type="text" name="extra[15][largomax]" size="3" /></td>
  </tr>
  <tr>
   <td>Con formato</td>
   <td><input type="radio" name="extra[15][cformato]" />Si <input type="radio" name="extra[15][cformato]" checked="checked" />No </td>
  </tr>
</tbody>

<tbody id="extra6" class="conf_extra">
<tr><th colspan="2">
 <p>Número</p>
 <p>6 , 17 , 25</p>
 </th></tr>
  <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[6][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[6][unico]" value="0" /><label>Lista</label></td></tr>
  <tr>
   <td>Tipo</td>
   <td><input type="radio" name="extra[6][numtipo]" />Natural <input type="radio" name="extra[6][numtipo]" />Entero <input type="radio" name="extra[6][numtipo]" />Decimal </td>
  </tr>
 </tbody>


<tbody id="extra2" class="conf_extra">
<tr><th colspan="2"> <p>Color</p>
 <p>2</p>
</th></tr>
  <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[2][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[2][unico]" value="0" /><label>Lista</label></td></tr>
 </tbody>


<tbody id="extra9" class="conf_extra">
<tr><th colspan="2">Archivo</th></tr>
   <tr>
   <td>Multilíngüe</td>
   <td><input type="radio" name="extra[9][mlingue]" checked="checked" />Si <input type="radio" name="extra[9][mlingue]" />No </td>
  </tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[9][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[9][unico]" value="0" /><label>Lista</label></td></tr>
	  <tr>
	   <td><label>Extensiones <select name="extra[9][tipo]"><option value="permitidos">permitidas</option><option value="negados">denegadas</option></select>:</label></td>
	   <td><input type="text" name="extra[9][extensiones]" value="" size="30" maxlength="30" title="Ingrese extensiones separadas por comas (,)" /></td></tr>
 </tbody>

<tbody id="extra8" class="conf_extra">
<tr><th colspan="2">
 <p>Imagen / Galería de imágenes</p>
 <p>8 , 10</p>
</th></tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Único</label> <input type="radio" name="unico" value="0" /><label>Lista</label></td></tr>
	  <tr>
	   <td>Imagen</td>
	   <td>
	    <ul>
	     <li><label for="metodo_img">Método</label> <select name="metodo_img" id="metodo_img" onchange="imgHabMinimo(this)"><option value="escalar">Escalar</option><option value="recortar" selected="selected">Recortar</option></select></li>
	     <li><label for="ancho_img">Ancho</label> <input type="text" name="ancho_img" id="ancho_img" value="300" size="4" maxlength="4" /> <label for="minancho_img">mínimo</label> <input type="text" name="minancho_img" id="minancho_img" value="" size="4" maxlength="4" disabled="disabled" /></li>
	     <li><label for="alto_img">Alto</label> <input type="text" name="alto_img" id="alto_img" value="200" size="4" maxlength="4" /> <label for="minalto_img">mínimo</label> <input type="text" name="minalto_img" id="minalto_img" value="" size="4" maxlength="4" disabled="disabled" /></li>
	     <li><label for="marca">Usar marca de agua</label> <input type="checkbox" name="marca" id="marca" value="1"   onclick="mostrarMarcas(this)" /><input type="hidden" name="marca_arch" value="" /> <img src="img/trans" id="img_marca" alt="gnome-gmush.png" onclick="document.getElementById('img_mustraOp').style.display='block'" /><ul>
		 <li>Posición horizontal: <select name="posX"><option value="1">desde la izquierda</option><option value="0" selected="selected">al centro</option><option value="3">desde la derecha</option></select> <input type="text" name="pxX" size="3" /></li>
		 <li>Posición vertical: <select name="posY"><option value="1">desde arriba</option><option value="0" selected="selected">al centro</option><option value="3">desde abajo</option></select> <input type="text" name="pxY" size="3" /></li></ul></li>
	    </ul></td></tr>
	  <tr>
	   <td>Miniatura</td>
	   <td>
	    <ul>
	     <li><label for="metodo_imgch">Método</label> <select name="metodo_imgch" id="metodo_imgch"><option value="escalar">Escalar</option><option value="recortar" selected="selected">Recortar</option></select></li>
	     <li><label for="ancho_imgch">Ancho</label> <input type="text" name="ancho_imgch" id="ancho_imgch" value="40" size="4" maxlength="4" /></li>
	     <li><label for="alto_imgch">Alto</label> <input type="text" name="alto_imgch" id="alto_imgch" value="40" size="4" maxlength="4" /></li>
	    </ul></td></tr>
	  <tr>
	   <td colspan="2" id="regImagenes"><a onclick="iniciarRegImgs(9)">Regenerar imágenes</a> <span>&#8203;</span></td></tr>
 </tbody>


<tbody id="extra11" class="conf_extra">
<tr><th colspan="2">Selector de opciones
 <p>11 , 12 , 13 , 14 , </p>
</th></tr>
   <tr>
   <td>Multilíngüe</td>
   <td><input type="radio" name="mlingue" checked="checked" />Si <input type="radio" name="mlingue" />No </td>
  </tr>
  <tr>
	<td><label>Múltiple:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Si</label> <input type="radio" name="unico" value="0" /><label>No</label></td></tr>
  <tr>
   <td>Visualización:</td>
   <td><input type="radio" name="vis" />Lista <input type="radio" name="vis" />Compacto</td>
  </tr>
	  <tr>
	   <td>Opciones</td>
	   <td>
	    <ul><li><input type="text" name="extra_v[0]" value="" /></li><li><input type="text" name="extra_v[1]" value="" /></li><li><input type="text" name="extra_v[2]" /></li><li><input type="text" name="extra_v[3]" /></li>
	    </ul></td></tr>
 </tbody>

<tbody id="extra4" class="conf_extra">
<tr><th colspan="2">
 Fecha y hora
 <p>4 , 5</p>
</th></tr>
  <tr>
   <td>Hora</td>
   <td><input type="radio" name="hora" value="1" checked="checked" /><label>Si</label> <input type="radio" name="hora" value="0" /><label>No</label></td>
  </tr>
 	  <tr>
	   <td>Formato</td>
	   <td><select name="extra_v">
	    <optgroup label="Formatos Predeterminados">
	     <option value="1">Corto</option>
	     <option value="2">Largo</option>
	    </optgroup>
	    <!-- optgroup label="Fecha/hora completa">
	     <option value="3">ISO 8601</option>
	     <option value="4">RFC 2822</option>
	     <option value="5">Epoch</option>
	    </optgroup -->
	    </select></td></tr>
</tbody>


<tbody id="extra22" class="conf_extra">
<tr><th colspan="2">
Enlace
</th></tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Único</label> <input type="radio" name="unico" value="0" /><label>Lista</label></td></tr>
	  <tr>
	   <td>Protocolos</td>
	   <td>
	    <ul>
	     <li><input type="checkbox" name="extra[1]" value="1" id="ex1" /> <label for="ex1">http</label></li>
	     <li><input type="checkbox" name="extra[2]" value="2" id="ex2" /> <label for="ex2">https</label></li>
	     <li><input type="checkbox" name="extra[3]" value="3" id="ex3" /> <label for="ex3">ftp</label></li>
	     <li><input type="checkbox" name="extra[4]" value="4" id="ex4" /> <label for="ex4">gopher</label></li>
	     <li><input type="checkbox" name="extra[5]" value="5" id="ex5" /> <label for="ex5">mailto</label></li>
	    </ul></td></tr>
 </tbody>



<tbody id="extra7" class="conf_extra">
<tr><th colspan="2">Dato externo
 <p>7</p>
</th></tr>
	  <tr>
	   <td>Sección:</td>
	   <td><select name="_a"><option value="10">Productos</option><option value="11">Todos los atributos</option></select>

</td></tr>
	  <tr>
	   <td>Campo:</td>
	   <td><select id="asdfg"><option>&#8203;</option></select></td></tr>
</tbody>


<tbody id="extra23" class="conf_extra">
<tr><th colspan="2">Formulario
 <p>23</p>
</th></tr>
	  <tr>
	   <td>Destino</td>
	   <td><input type="text" /></td>
	  </tr>
	  <tr>
	   <td>Método</td>
	   <td><select><option>post</option><option>multipart-post</option><option>form-data-post</option><option>put</option><option>get</option></select></td>
	  </tr>
</tbody>



<tbody id="extra16" class="conf_extra">
<tr><th colspan="2">
 Precio
16
 </th></tr>
	  <tr>
	   <td>Moneda</td>
	   <td><select name="extra_v"><option value="1">Pesos Uruguayos</option></select></td></tr>
</tbody>
	</table>
  <!-- /form -->

 </form>

<?php


  $niveles = array(0);
  $superior_niv = 0;
  if(!$consulta = $mysqli->query("SELECT ia.id, ia.identificador, ian.atributo, ia.sugerido, ia.unico, ia.tipo_id, isaa.por_omision, isaa.seccion_id, isaa.orden, isaa.en_listado, isaa.salida, at.op_listado, at.op_oculto, isaa.superior, isaa.orden IS NULL AS ordennull FROM items_atributos ia LEFT JOIN items_secciones_a_atributos isaa ON ia.id = isaa.atributo_id AND isaa.seccion_id = '{$_REQUEST['seccion']}', items_atributos_n ian, atributos_tipos at WHERE ia.id = ian.id AND ia.tipo_id = at.id AND ian.leng_id = (SELECT id FROM lenguajes WHERE leng_poromision = 1 LIMIT 1) ORDER BY isaa.seccion_id DESC, ordennull, orden, ia.id")) die("\n".__LINE__." mySql: ".$mysqli->error);
  //if($fila = $consulta->fetch_assoc())
//   {
    echo "
  <h3>Atributos</h3>
  <form action=\"/configuracion?seccion={$_REQUEST['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"as_atributo\" />
";

?>

<div id="arrastre_head">
 <div class="linea">
  <span class="celda2" title="Identificador">Identificador</span>
  <span class="celda10" title="Editar">Editar</span>
  <span class="celda9"><abbr title="Oculto">O</abbr></span>
  <span class="celda8"><abbr title="En listado">L</abbr></span>
  <span class="celda7" title="Valor por omisión">Valor por omisión</span>
  <span class="celda6" title="Tipo">Tipo</span>
  <span class="celda5" title="Único">Único</span>
  <span class="celda4" title="Sugerido">Sugerido</span>
  <span class="celda3" title="Nombre">Nombre</span>
 </div>
</div>

<?php

echo "
<div id=\"arrastre_cont\">
";

	$sel = array(false, " checked=\"checked\"");
	$selfila = array(false, " sel_fila");
	$sugerido_ops = array("No", "Si", "Obligatorio");
	$n_orden = 0;
    while($fila = $consulta->fetch_assoc())
     {
	  $check = $fila['seccion_id'] ? 1 : 0;
	  $superior = $fila['superior'] ? $fila['superior'] : 0;
	  if(in_array($superior, $niveles))
	   {
		$pop = end($niveles);
		while($pop != $superior)
		 {
		  array_pop($niveles);
		  echo "<hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\" /></div>";
		  $pop = end($niveles);
		 }
	   }

	  if(!$fila['seccion_id'] && !$form_cerrado)
	   {
	   	$form_cerrado = true;
		echo "
		<hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\" /></div>
		<br /><br />
		<input type=\"submit\" value=\"Aceptar\" />
		</form>

		<img src=\"img/papelera\" id=\"eliminarImg\" alt=\"Eliminar\" title=\"Arrastre hasta aquí para eliminar\" style=\"background:none;border:none;\" />
		<div id=\"arrastre_cont_desact\">";
	   }

	  if($check)
	   {
	   	$salida = $fila['salida'] ? 0 : 1;
	   	$n_orden++;
	   	$mysqli->query("UPDATE items_secciones_a_atributos SET orden = ${n_orden} WHERE seccion_id = '{$fila['seccion_id']}' AND atributo_id = '{$fila['id']}'");
		$orden = $n_orden;
	   }
	  else
	   {
	   	$salida = 0;
	   	$orden = false;
	   }
	  echo "
<hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\"
 /><div class=\"linea\"
    ><input type=\"hidden\" name=\"attr[]\" value=\"{$fila['id']}\"
   /><input type=\"hidden\" name=\"superior[{$fila['id']}]\" value=\"{$fila['superior']}\"
   /><span class=\"celda1\"><img src=\"/img/".(($fila['nodo_tipo'] != 1) ? 'e" onclick="listaColapsar(this)' : 'trans')."\" alt=\"\" /></span
    ><span class=\"celda2\" onmousedown=\"return arrastrar(this, event)\">{$fila['identificador']}</span
    ><span class=\"celda10\"><a href=\"".APU."campo?id={$fila['id']}&amp;seccion={$_REQUEST['seccion']}\">Editar</a></span
    ><span class=\"celda9\"><input type=\"checkbox\" name=\"salida[{$fila['id']}]\" value=\"1\"".($fila['op_oculto'] ? $sel[$salida] : ' disabled="disabled" title="Este atributo no se puede ocultar."')." /></span
    ><span class=\"celda8\"><input type=\"checkbox\" name=\"en_listado[{$fila['id']}]\" value=\"1\"".($fila['op_listado'] ? $sel[$fila['en_listado']] : ' disabled="disabled" title="Este atributo no se puede ocultar."')." /></span
    ><span class=\"celda7\">{$poromision[$fila['id']]}&nbsp;</span
    ><span class=\"celda6\" title=\"{$tipos[$fila['tipo_id']][0]}\">{$tipos[$fila['tipo_id']][0]} - {$fila['tipo_id']}&nbsp;</span
    ><span class=\"celda5\">{$sugerido_ops[$fila['unico']]}&nbsp;</span
    ><span class=\"celda4\">{$sugerido_ops[$fila['sugerido']]}&nbsp;</span
    ><span class=\"celda3\">{$fila['atributo']}&nbsp;</span
    >";

if($fila['nodo_tipo'] != 1)
 {
//echo "<div>";
  array_push($niveles, $fila['id']);
 }
else
 echo "</div>";
     }
    $consulta->close();
if(!$form_cerrado)
 {

	  $superior = 0;
	  if(in_array($superior, $niveles))
	   {
		$pop = end($niveles);
		while($pop != $superior)
		 {
		  array_pop($niveles);
		  echo "<hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\" /></div>";
		  $pop = end($niveles);
		 }
	   }

    //echo $seccion_id ? " <input type=\"submit\" value=\"Aceptar\" />": " <input type=\"submit\" value=\"Eliminar\" />";
    echo "
    <hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\" /></div>
		<input type=\"submit\" value=\"Aceptar\" />
		</form>
		<img src=\"img/papelera\" id=\"eliminarImg\" alt=\"Eliminar\" title=\"Arrastre hasta aquí para eliminar\" style=\"background:none;border:none;\" />
		<div id=\"arrastre_cont_desact\"></div>";
 }
else
  echo "</div>";

//   }

?>

<pre id="resp"></pre>
<script type="text/javascript">
function scrollWindow()
  {
  window.scrollTo(0,70)
  }
</script>
<input type="button" onclick="scrollWindow()" value="Scroll" />
</div>
<?php


//   }
//  else echo "no se encontró nada";
  unset($vista);//include('inc/iapie.php');
 }
else include('./error/404.php');

?>