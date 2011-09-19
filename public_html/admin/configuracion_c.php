<?php

require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();
$mod = "listar";
$cons_seccion = $mysqli->query("SELECT `nombre`, `identificador` FROM `admin_secciones` WHERE `id` = '{$_REQUEST['seccion']}' AND `link` = '${mod}' LIMIT 1");
if($fila_seccion = $cons_seccion->fetch_row())
 {
  $titulo = $fila_seccion[0];
  $seccion_id = $_REQUEST['seccion'];
  $seccion = $fila_seccion[1];
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <!-- link rel="stylesheet" type="text/css" media="all" href="/css/campos.css" / -->
 <script type="application/x-javascript" src="/js/editar.js"></script>
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
 
 </script>
*/

  if($_POST['accion'] == "ag_atributo")
   {
    if(!empty($_POST['identificador']))
     {
	  setlocale(LC_CTYPE, 'es_UY.UTF-8');
	  $unarray = array();
	  if($_POST['tipo'] == 8 || $_POST['tipo'] == 10) $extra = "'array (0 => array (0 => \\'recortar\\',1 => 200,2 => 200,),1 => array (0 => \\'recortar\\',1 => 40,2 => 40,),)'";
	  elseif($_POST['tipo'] == 11) $extra = "'array(0 => \\'No\\', \\'Si\\')'";
	  else $extra = "NULL";
	  $mysqli->query("INSERT INTO items_atributos (`identificador`, `sugerido`, `unico`, `tipo_id`, `extra`) VALUES ('".str_replace(" ", "_", strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $_POST['identificador'])))."', '{$_POST['sugerido']}', '{$_POST['unico']}', '{$_POST['tipo']}', ${extra})");
      if($id = $mysqli->insert_id)
       {
     	if(($_POST['tipo'] == 8 || $_POST['tipo'] == 10) && !@is_dir('../img/0/'.$id))
       	 {
		  @mkdir('../img/0/'.$id);
		  @mkdir('../img/1/'.$id);
       	 }
       	$etiqueta = current($_POST['leng']) ? current($_POST['leng']) : $_POST['identificador'];
		$en_listado = $_POST['en_listado'] ? $_POST['en_listado'] : 0;
		$salida = ($_POST['salida'] == 1) ? 0 : 1;
		$mysqli->query("INSERT INTO categorias_secciones_a_atributos (`seccion_id`, `atributo_id`) VALUES ('{$_REQUEST['seccion']}', '${id}')");
        foreach($_POST['leng'] AS $idioma_k => $idioma_v) $mysqli->query("INSERT INTO items_atributos_n (`id`, `leng_id`, `atributo`) VALUES ('${id}', '${idioma_k}', '${idioma_v}')");
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
   	$mysqli->query("DROP TABLE `pubcats__${seccion}`");
	//@unlink("../menuXml/${seccion}.sqlite");
	$div_mensaje = "Se ha borrado la publicación del listado para esta sección. Debe publicar las categorías nuevamente.";
	$mysqli->query("UPDATE items_categorias SET estado_id = 0 WHERE `seccion_id` = '{$seccion_id}'");




	if(!empty($_POST['preset']))
	 {
	  if($_POST['preset'] == 'nuevo')
	   {
	    $mysqli->query("INSERT INTO config_perfiles (nombre) VALUES ('{$_POST['n_preset']}')");
		$preset = $mysqli->insert_id;
	   }
	  else
	   {
	    $preset = $_POST['preset'];
	    $mysqli->query("DELETE FROM config_perfiles_cats WHERE `perfil_id` = '{$_POST['preset']}'");
	   }
	 }




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
	$mysqli->query("DELETE FROM categorias_secciones_a_atributos WHERE `seccion_id` = '{$_REQUEST['seccion']}'");
	if(count($_POST['attr']))
	 {
	  foreach($_POST['attr'] AS $attr_v)
	   {
		$en_listado = $_POST['en_listado'][$attr_v] ? $_POST['en_listado'][$attr_v] : 0;
		$salida = $_POST['salida'][$attr_v] ? $_POST['salida'][$attr_v] : 0;
		$orden = $_POST['orden'][$attr_v] ? $_POST['orden'][$attr_v] : "NULL";
	   	$mysqli->query("INSERT INTO categorias_secciones_a_atributos (`seccion_id`, `atributo_id`, `orden`) VALUES ('{$_REQUEST['seccion']}', '${attr_v}', {$orden})");
		if($preset)
		  $mysqli->query("INSERT INTO config_perfiles_cats (`perfil_id`, `atributo_id`, `orden`) VALUES ({$preset}, ${attr_v}, {$orden})");
	   }
	 }
	
   }

  include('inc/iaencab.php');

/*********************************************************************

echo "<pre>";
print_r($array3);
print_r($array4);
echo (count($array3) == 0 && count($array4) == 0) ? "Sin cambios" : "Hay cambios <b>Borrar todo</b>";
echo "</pre>";

  if(!$consulta = $mysqli->query("SELECT id, nombre FROM admin_secciones WHERE `link` = 'listar' ORDER BY orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
//    echo "
//  <h3>Secciones</h3>
//	<ol";
    do
     {
//	  echo "
//	 ><li>";
	  if($fila[0] == $_REQUEST['seccion'])
	   {
	    $seccion = $_REQUEST['seccion'];
//	    echo "<b>{$fila[1]}</b>";
	   }
//	  else echo "<a href=\"".$_SERVER['PHP_SELF']."?seccion={$fila[0]}\">{$fila[1]}</a>";
//	  echo "</li";
     }while($fila = $consulta->fetch_row());
    $consulta->close();
//    echo "
//	></ol>";
//	if($seccion) echo "<a href=\"".$_SERVER['PHP_SELF']."\">Volver</a>";
   }
********************************************************************/

  $lenguajes = array();
  if(!$consulta_lengs = $mysqli->query("SELECT id, codigo, dir FROM lenguajes WHERE habilitado = 1 ORDER BY id")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_lengs = $consulta_lengs->fetch_row())
   {
	do
	 {
	  $lenguajes[] = $fila_lengs;
	 }while($fila_lengs = $consulta_lengs->fetch_row());
   }

  $tipos = array();
  if(!$consulta = $mysqli->query("SELECT at.id, atn.nombre, at.tipo FROM atributos_tipos at JOIN atributos_tipos_nombres atn ON at.id = atn.id AND atn.leng_id = '1' ORDER BY atn.nombre")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
    echo "
  <form action=\"configuracion_c?seccion={$_REQUEST['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"ag_atributo\" />
	<table class=\"tabla\">
	 <thead>
	  <tr>
	   <td colspan=\"2\">Agregar atributo</td></tr>
	 </thead>
	 <tfoot>
	  <tr>
	   <td colspan=\"2\" style=\"text-align:center;\"><input type=\"submit\" value=\"Aceptar\" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for=\"identificador\">Identificador:</label></td>
	   <td><input type=\"text\" name=\"identificador\" id=\"identificador\" size=\"15\" maxlength=\"15\" /></td></tr>
	  <tr>
	   <td><label for=\"tipo\">Tipo:</label></td>
	   <td><select name=\"tipo\" id=\"tipo\">";
    // onchange=\"campo_opciones(this.options[this.selectedIndex].value);\"
    do
     {
	  $tipos[$fila[0]] = array($fila[1], $fila[2]);
	  echo "
	    <option value=\"{$fila[0]}\"";
	  if($fila[0] == 1) echo " selected=\"selected\"";
	  echo ">{$fila[1]}</option>";
     }while($fila = $consulta->fetch_row());
    $consulta->close();
    echo "
	    </select></td></tr>
	  <tr>
	   <td><label>Sugerido:</label></td>
	   <td><input type=\"radio\" name=\"sugerido\" id=\"sugerido0\" value=\"0\" /><label for=\"sugerido0\">No</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido1\" value=\"1\" checked=\"checked\" /><label for=\"sugerido1\">Si</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido2\" value=\"2\" /><label for=\"sugerido2\">Obligatorio</label></td></tr>
	  <tr>
	   <td><label>Único:</label></td>
	   <td><input type=\"radio\" name=\"unico\" id=\"unico0\" value=\"0\" /><label for=\"unico0\">No</label> <input type=\"radio\" name=\"unico\" id=\"unico1\" value=\"1\" checked=\"checked\" /><label for=\"unico1\">Si</label></td></tr>
	  <tr>
	   <td><label>Etiqueta/s:</label></td>
	   <td><ul class=\"campo_lista\">";
	  foreach($lenguajes AS $fila_lengs)
	   {
	    echo "
	    <li><label for=\"leng{$fila_lengs[0]}\">({$fila_lengs[1]})</label> <input type=\"text\" name=\"leng[{$fila_lengs[0]}]\" id=\"leng{$fila_lengs[0]}\" /></li>";
	   }
	  echo "</ul></td></tr>
	 </tbody>
	</table>
  </form>";
   }
/*
  if(!$consulta = $mysqli->query("SELECT iv.`atributo_id`, iv.`string`, iv.`date`, iv.`text`, iv.`int` FROM items_valores iv WHERE iv.item_id IS NULL")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_assoc())
   {
    do
     {
	  $poromision[$fila['atributo_id']] = $fila[$tipos[$fila['atributo_id']][1]];
     }while($fila = $consulta->fetch_assoc());
    $consulta->close();
   }
*/
  //SELECT atributo_id, orden, orden IS NULL AS ordennull FROM items_categorias_a_atributos i WHERE categoria_id = '5' ORDER BY ordennull, orden
  if(!$consulta = $mysqli->query("SELECT ia.id, ia.identificador, ian.atributo, ia.sugerido, ia.unico, ia.tipo_id, isaa.por_omision, isaa.seccion_id, isaa.orden, isaa.orden IS NULL AS ordennull FROM items_atributos ia LEFT JOIN categorias_secciones_a_atributos isaa ON ia.id = isaa.atributo_id AND isaa.seccion_id = '{$_REQUEST['seccion']}', items_atributos_n ian WHERE ia.tipo_id != 10 AND ia.id = ian.id AND ian.leng_id = '1' ORDER BY isaa.seccion_id DESC, ordennull, orden, ia.id")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila = $consulta->fetch_assoc())
   {
    echo "
  <h3>Atributos</h3>
  <form action=\"".APU."configuracion_c?seccion={$_REQUEST['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"as_atributo\" />
  <table class=\"tabla\"
   ><thead
    ><tr
     ><td style=\"width:20px;\"></td
     ><td>Identificador</td
     ><td>Nombre</td
     ><td>Sugerido</td
     ><td>Único</td
     ><td>Tipo</td";
    if($seccion_id)
     {
	  echo "
     ><td>Valor por omisión</td
     ><td>Orden</td";
     }
    echo "
    ></tr
   ></thead
   ><tbody";

	$sel = array(false, " checked=\"checked\"");
	$selfila =array(false, " class=\"sel_fila\"");
	$sugerido_ops = array("No", "Si", "Obligatorio");
	$n_orden = 0;
    do
     {
	  $check = $fila['seccion_id'] ? 1 : 0;
	  if($check)
	   {
	   	$n_orden++;
	   	$mysqli->query("UPDATE categorias_secciones_a_atributos SET orden = ${n_orden} WHERE seccion_id = '{$fila['seccion_id']}' AND atributo_id = '{$fila['id']}'");
		$orden = $n_orden;
		$campo_seccion = "&amp;seccion={$_REQUEST['seccion']}";
	   }
	  else
	   {
	   	$campo_seccion = false;
	   	$orden = false;
	   }
	  echo "
	><tr{$selfila[$check]}
     ><td><input type=\"checkbox\" name=\"attr[]\" value=\"{$fila['id']}\"{$sel[$check]} /></td
	 ><td><a href=\"".APU."campo?id={$fila['id']}${campo_seccion}\">{$fila['identificador']}</a></td
	 ><td>{$fila['atributo']}</td
	 ><td>{$sugerido_ops[$fila['sugerido']]}</td
	 ><td>{$sugerido_ops[$fila['unico']]}</td
	 ><td>{$tipos[$fila['tipo_id']][0]}</td";
	 // if($seccion_id)
	 //  {
	    echo "
	 ><td>{$poromision[$fila['id']]}</td
	 ><td><input type=\"text\" name=\"orden[{$fila['id']}]\" value=\"{$orden}\" size=\"2\" maxlength=\"2\" /></td";
	 //  }
		echo "
	></tr";
//print_r($fila);
//	echo "
//	<li></li>";
//	if($fila[0] == 1) echo " selected=\"selected\"";
//	echo ">{$fila[1]}</option>";
     }while($fila = $consulta->fetch_assoc());
    $consulta->close();
    echo '
	></tbody
   ></table>
<div style="padding:10px;">
<label for="preset">Guardar configuración en:</label> <select name="preset" id="preset" onchange="nuevoPreset(this)" style="width:200px;">
  <option></option>
  <option value="nuevo">Nueva configuración…</option>';
	if(!$cons_perfiles = $mysqli->query("SELECT id, nombre FROM config_perfiles ORDER BY nombre")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila_perfiles = $cons_perfiles->fetch_row())
	 {
	  do
	   {
		echo "<option value=\"{$fila_perfiles[0]}\">{$fila_perfiles[1]}</option>";
	   }while($fila_perfiles = $cons_perfiles->fetch_row());
	 }
echo '
 </select><input type="text" name="n_preset" maxlength="30" onblur="agNuevoPreset(this)" onkeypress="return capturaEnter(event, this)" style="width:200px;display:none;" />
';
    echo $seccion_id ? " <input type=\"submit\" value=\"Aceptar\" />": " <input type=\"submit\" value=\"Eliminar\" />";
    echo "
	</div>
  </form>";
   }

//   }
//  else echo "no se encontró nada";
  include('inc/iapie.php');	
 }
else include('./error/404.php');

?>