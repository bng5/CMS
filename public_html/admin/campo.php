<?php

require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();
$mod = "listar";
$cons_campo = $mysqli->query("SELECT id, identificador, sugerido, unico, tipo_id, extra, et_xhtml FROM items_atributos WHERE `id` = '{$_REQUEST['id']}'");
if($fila_campo = $cons_campo->fetch_row()) {
    $id = $fila_campo[0];
    $identificador = $fila_campo[1];
    $sugerido = $fila_campo[2];
    $unico = $fila_campo[3];
    $tipo = $fila_campo[4];
    $extra = unserialize($fila_campo[5]);
    $et_xhtml = $fila_campo[6];
    //$seccion_id = $_REQUEST['seccion'];
    //$seccion = $fila_seccion[1];
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
<?php
/*
 <script type="text/javascript">

var camposC_opciones = {<?php foreach($camposC_opciones AS $c) { echo "{$sep}{$c} : true"; $sep = ", "; } ?>};
function campo_opciones(id)
 {
  //var id = selector.options(index).value;
  var campo_opciones = document.getElementById('campo_opciones');
  campo_opciones.style.display = camposC_opciones[id] ? "block" : "none";
 }

 </script>
*/

/*
  if($_POST['accion'] == "as_atributo")
   {
	$mysqli->query("DELETE FROM subitems_supatributos_a_atributos WHERE `sup_atributo_id` = '{$id}'");
	if(count($_POST['attr']))
	 {
	  foreach($_POST['attr'] AS $attr_v)
	   {
		$orden = $_POST['orden'][$attr_v] ? $_POST['orden'][$attr_v] : "NULL";
	   	$mysqli->query("INSERT INTO subitems_supatributos_a_atributos (`sup_atributo_id`, `atributo_id`, `orden`) VALUES ('{$id}', '{$attr_v}', {$orden})");
	   }
	 }
   }
*/
    if($_POST['accion'] == "modificar" && !empty($_POST['identificador'])) {
        $et_xhtml = $_POST['et_xhtml'];
        $ets_xhtml[$_POST['tipo']] = $et_xhtml ? $et_xhtml : 'p';

        $identificador = $_POST['identificador'];
        $sugerido = $_POST['sugerido'];
        $unico = $_POST['unico'];
        $tipo = $_POST['tipo'];
        $ins_extra = "NULL";
        if($_POST['tipo'] == 8 || $_POST['tipo'] == 10) {
            if(!$_POST['metodo_img']) {
                $extra_arr[] = array('recortar', 200, 200);
                $extra_arr[] = array('recortar', 40, 40);
            }
            else {
                $ancho_img = $_POST['ancho_img'] ? $_POST['ancho_img'] : false;
                $alto_img = $_POST['alto_img'] ? $_POST['alto_img'] : false;
                $minancho_img = $_POST['minancho_img'] ? $_POST['minancho_img'] : false;
                $minalto_img = $_POST['minalto_img'] ? $_POST['minalto_img'] : false;
                $ancho_imgch = $_POST['ancho_imgch'] ? $_POST['ancho_imgch'] : false;
                $alto_imgch = $_POST['alto_imgch'] ? $_POST['alto_imgch'] : false;
                $minancho_imgch = $_POST['minancho_imgch'] ? $_POST['minancho_imgch'] : false;
                $minalto_imgch = $_POST['minalto_imgch'] ? $_POST['minalto_imgch'] : false;
                if($_POST['marca'] == "1" && !empty($_POST['marca_arch'])) {
                    $posX = $_POST['posX'] ? ($_POST['posX'] == 1 ? $_POST['pxX'] : -($_POST['pxX'])) : false;
                    $posY = $_POST['posY'] ? ($_POST['posY'] == 1 ? $_POST['pxY'] : -($_POST['pxY'])) : false;
                    $marca = array($_POST['marca_arch'], $posX, $posY);
                }
                else
                    $marca = false;
                $extra_arr[] = array($_POST['metodo_img'], $ancho_img, $alto_img, $marca, $minancho_img, $minalto_img);
                $extra_arr[] = array($_POST['metodo_imgch'], $ancho_imgch, $alto_imgch);
                foreach($_POST['prot'] AS $prot_k => $prot_v)
                    $extra_arr['prot'][$prot_k] = $_POST['prottipo'][$prot_k];
                //if($_POST['obj_string'])
            }
            $extra = serialize($extra_arr);
            $ins_extra = "'".addslashes($extra)."'";
        }
        elseif($_POST['tipo'] == 9) {
            function limpia_val_extensiones(&$valor) {
                $valor = trim($valor, " .");
                //if(empty($valor))
            }
            $extra_tipo = $_POST['extra']['tipo'];
            $extra_arr[$extra_tipo] = explode(",", $_POST['extra']['extensiones']);
            array_walk($extra_arr[$extra_tipo], 'limpia_val_extensiones');
            $extra_arr[$extra_tipo] = array_filter($extra_arr[$extra_tipo]);
            $extra = serialize($extra_arr);
            $ins_extra = "'".addslashes($extra)."'";
        }
        elseif($_POST['tipo'] == 11) {
            if(!$_POST['extra_v'])
                $extra_arr = array(0 => 'No', 'Si');
            else {
                foreach($_POST['extra_v'] AS $extra_v) {
                    if(!empty($extra_v))
                        $extra_arr[] = $extra_v;
                }
            }
            $extra = serialize($extra_arr);
            $ins_extra = "'".addslashes($extra)."'";
        }
        elseif($_POST['tipo'] == 12) {
            if(!$_POST['extra_v'])
                $extra_arr = array(0 => 'No', 'Si');
            else {
                foreach($_POST['extra_v'] AS $extra_v) {
                    if(!empty($extra_v))
                        $extra_arr[] = $extra_v;
                }
            }
            $extra = serialize($extra_arr);
            //$ins_extra = "'".addslashes($extra)."'";
        }
        // precio
        elseif($_POST['tipo'] == 16 || $_POST['tipo'] == 4 || $_POST['tipo'] == 5) {
//print_r($_POST['extra_v']);
	  /*
	  if(!$_POST['extra_v']) $extra_arr = array(0 => 'No', 'Si');
	  else
	   {
		foreach($_POST['extra_v'] AS $extra_v)
		 {
		  if(!empty($extra_v)) $extra_arr[] = $extra_v;
		 }
	   }
	  */
            $ins_extra = $extra = $_POST['extra_v'];
        }
        elseif($_POST['tipo'] == 22) {
            $extra = serialize($_POST['extra']);
            $ins_extra = "'".addslashes($extra)."'";
        }

        function SureRemoveDir($dir, $DeleteMe = false) {
            if(!$dh = @opendir($dir))
                return;
            while (false !== ($obj = readdir($dh))) {
                if($obj=='.' || $obj=='..')
                    continue;
                if(!@unlink($dir.'/'.$obj))
                    SureRemoveDir($dir.'/'.$obj, $DeleteMe);
            }
            closedir($dh);
            if($DeleteMe)
                @rmdir($dir);
        }
        $mysqli->query("UPDATE items_atributos SET identificador = '{$identificador}', sugerido = {$sugerido}, unico = {$unico}, tipo_id = {$tipo}, extra = {$ins_extra}, et_xhtml = '{$ets_xhtml[$_POST['tipo']]}' WHERE id = {$id}");
        if($mysqli->affected_rows && ($_POST['tipo'] == 8 || $_POST['tipo'] == 10)) {
            $dir0 = RUTA_CARPETA.'public_html/img/0/'.$id;
            if(is_dir($dir0)) {

/*********************************************************************/

            $modificar_archivos_img = true;

/*********************************************************************/
            //SureRemoveDir($dir0);
            //SureRemoveDir('../img/1/'.$id);
        }
        else {
            mkdir(RUTA_CARPETA.'public_html/img/0/'.$id);
            mkdir(RUTA_CARPETA.'public_html/img/1/'.$id);
        }
    }
    elseif(($_POST['tipo'] != 8 && $_POST['tipo'] != 10) && ($_POST['extipo'] == 8 || $_POST['extipo'] == 10)) {
        SureRemoveDir(RUTA_CARPETA.'public_html/img/0/'.$id, true);
        SureRemoveDir(RUTA_CARPETA.'public_html/img/1/'.$id, true);
    }
	$mysqli->query("DELETE FROM items_atributos_n WHERE `id` = {$id}");
	foreach($_POST['leng'] AS $leng_id => $nombre)
        $mysqli->query("INSERT INTO items_atributos_n VALUES ({$id}, {$leng_id}, '{$nombre}')");
	if($_POST['tipo'] != $_POST['extipo']) {
	  $mysqli->query("DELETE FROM items_valores WHERE atributo_id = {$_GET['id']}");
	  $mysqli->query("DELETE FROM categorias_valores WHERE atributo_id = {$_GET['id']}");
	  $mysqli->query("DELETE FROM secciones_valores WHERE atributo_id = {$_GET['id']}");
    }
}

$titulo = "Atributo: {$identificador}";
include('inc/iaencab.php');

echo "<p><a href=\"/configuracion?seccion={$_GET['seccion']}\">Regresar</a></p>";
echo "<pre>";
print_r($_POST);
print_r($extra_arr);
echo "</pre>";

//echo "UPDATE items_atributos SET identificador = '{$identificador}', sugerido = {$sugerido}, unico = {$unico}, tipo_id = {$tipo}, extra = {$ins_extra} WHERE id = {$id}";

/*
echo "<pre>";
print_r($_POST);
print_r($_GET);
echo "</pre>";
*/

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
if(!$consulta_lengs = $mysqli->query("SELECT id, codigo, codigo, dir FROM lenguajes WHERE estado > 0 AND estado < 5 ORDER BY id"))
        die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila_lengs = $consulta_lengs->fetch_row()) {
    do {
        $lenguajes[] = $fila_lengs;
    }while($fila_lengs = $consulta_lengs->fetch_row());
}

$nombres = array();
$cons_nombres = $mysqli->query("SELECT leng_id, atributo FROM items_atributos_n WHERE id = {$id}");
if($fila_nombres = $cons_nombres->fetch_row()) {
    do {
        $nombres[$fila_nombres[0]] = $fila_nombres[1];
    }while($fila_nombres = $cons_nombres->fetch_row());
}

$sel_sugerido[$sugerido] = " checked=\"checked\"";
$sel_unico[$unico] = " checked=\"checked\"";
$sel_et_xhtml[$et_xhtml] = ' selected="selected"';
$tipos = array();
if(!$consulta = $mysqli->query("SELECT at.id, atn.nombre, at.tipo FROM atributos_tipos at JOIN atributos_tipos_nombres atn ON at.id = atn.id AND atn.leng_id = 76 ORDER BY atn.nombre"))
    die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_row()) {
    echo "
  <form action=\"campo?id={$id}&amp;seccion={$_GET['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"modificar\" />
   <input type=\"hidden\" name=\"extipo\" value=\"{$tipo}\" />
	<table class=\"tabla\">
	 <thead>
	  <tr>
	   <td colspan=\"2\">Editar atributo</td></tr>
	 </thead>
	 <tfoot>
	  <tr>
	   <td colspan=\"2\" style=\"text-align:center;\"><input type=\"submit\" value=\"Aceptar\" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for=\"identificador\">Identificador:</label></td>
	   <td><input type=\"text\" name=\"identificador\" id=\"identificador\" value=\"{$identificador}\" size=\"15\" maxlength=\"15\" /></td></tr>
	  <tr>
	   <td><label for=\"tipo\">Tipo:</label></td>
	   <td><select name=\"tipo\" id=\"tipo\">";
    // onchange=\"campo_opciones(this.options[this.selectedIndex].value);\"
	do {
	  $tipos[$fila[0]] = array($fila[1], $fila[2]);
	  $atribxtipo[$fila[2]][$fila[3]] = $fila[1];
	  echo "
	    <option value=\"{$fila[0]}\"";
	  if($fila[0] == $tipo) echo " selected=\"selected\"";
	  echo ">{$fila[1]}</option>";
     }while($fila = $consulta->fetch_row());
    $consulta->close();
	echo "
	    </select></td></tr>
	  <tr>
	   <td><label>Sugerido:</label></td>
	   <td><input type=\"radio\" name=\"sugerido\" id=\"sugerido0\" value=\"0\"{$sel_sugerido[0]} /><label for=\"sugerido0\">No</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido1\" value=\"1\"{$sel_sugerido[1]} /><label for=\"sugerido1\">Si</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido2\" value=\"2\"{$sel_sugerido[2]} /><label for=\"sugerido2\">Obligatorio</label></td></tr>
	  <tr>
	   <td><label>Único:</label></td>
	   <td><input type=\"radio\" name=\"unico\" id=\"unico0\" value=\"0\"{$sel_unico[0]} /><label for=\"unico0\">No</label> <input type=\"radio\" name=\"unico\" id=\"unico1\" value=\"1\"{$sel_unico[1]} /><label for=\"unico1\">Si</label></td></tr>
	  <tr>
	   <td><label>Etiqueta/s:</label></td>
	   <td><ul class=\"campo_lista\">";
	foreach($lenguajes AS $fila_lengs) {
	  echo "
	    <li><label for=\"leng{$fila_lengs[0]}\"><tt>({$fila_lengs[1]})</tt></label> <input type=\"text\" name=\"leng[{$fila_lengs[0]}]\" id=\"leng{$fila_lengs[0]}\" value=\"".htmlspecialchars($nombres[$fila_lengs[0]])."\" /></li>";
	 }
	echo "</ul></td></tr>
	  <tr>
	   <td><label for=\"et_xhtml\">Etiqueta xhtml:</label></td>
	   <td><select name=\"et_xhtml\" id=\"et_xhtml\">
<option value=\"\"></option>
<option value=\"a\"{$sel_et_xhtml['a']}>a</option>
<option value=\"address\"{$sel_et_xhtml['address']}>address</option>
<option value=\"b\"{$sel_et_xhtml['b']}>b</option>
<option value=\"big\"{$sel_et_xhtml['big']}>big</option>
<option value=\"blockquote\"{$sel_et_xhtml['blockquote']}>blockquote</option>
<option value=\"cite\"{$sel_et_xhtml['cite']}>cite</option>
<option value=\"code\"{$sel_et_xhtml['code']}>code</option>
<option value=\"del\"{$sel_et_xhtml['del']}>del</option>
<option value=\"dfn\"{$sel_et_xhtml['dfn']}>dfn</option>
<option value=\"em\"{$sel_et_xhtml['em']}>em</option>
<option value=\"h1\"{$sel_et_xhtml['h1']}>h1</option>
<option value=\"h2\"{$sel_et_xhtml['h2']}>h2</option>
<option value=\"h3\"{$sel_et_xhtml['h3']}>h3</option>
<option value=\"h4\"{$sel_et_xhtml['h4']}>h4</option>
<option value=\"h5\"{$sel_et_xhtml['h5']}>h5</option>
<option value=\"h6\"{$sel_et_xhtml['h6']}>h6</option>
<option value=\"i\"{$sel_et_xhtml['i']}>i</option>
<option value=\"ins\"{$sel_et_xhtml['ins']}>ins</option>
<option value=\"kbd\"{$sel_et_xhtml['kbd']}>kbd</option>
<option value=\"p\"{$sel_et_xhtml['p']}>p</option>
<option value=\"pre\"{$sel_et_xhtml['pre']}>pre</option>
<option value=\"q\"{$sel_et_xhtml['q']}>q</option>
<option value=\"samp\"{$sel_et_xhtml['samp']}>samp</option>
<option value=\"small\"{$sel_et_xhtml['small']}>small</option>
<option value=\"span\"{$sel_et_xhtml['span']}>span</option>
<option value=\"strong\"{$sel_et_xhtml['strong']}>strong</option>
<option value=\"sub\"{$sel_et_xhtml['sub']}>sub</option>
<option value=\"sup\"{$sel_et_xhtml['sup']}>sup</option>
<option value=\"tt\"{$sel_et_xhtml['tt']}>tt</option>
<option value=\"var\"{$sel_et_xhtml['var']}>var</option></select></td></tr>
	  <tr>
	   <th colspan=\"2\">Extra</th></tr>";

	if($tipo == 4 || $tipo == 5) {
	  $sel_formato[$extra] = " selected=\"selected\"";
	  echo "
	  <tr>
	   <td>Formato</td>
	   <td><select name=\"extra_v\">
	    <optgroup label=\"Formatos Predeterminados\">
	     <option value=\"1\"{$sel_formato[1]}>Corto</option>
	     <option value=\"2\"{$sel_formato[2]}>Largo</option>
	    </optgroup>
	    <!-- optgroup label=\"Fecha/hora completa\">
	     <option value=\"3\"{$sel_formato[3]}>ISO 8601</option>
	     <option value=\"4\"{$sel_formato[4]}>RFC 2822</option>
	     <option value=\"5\"{$sel_formato[5]}>Epoch</option>
	    </optgroup -->
	    </select></td></tr>";
	 }
	elseif($tipo == 7) {
	  echo "
	  <tr>
	   <td>Sección:</td>
	   <td>";
	  if(!$cons_exsec = $mysqli->query("SELECT ads.id, sn.titulo FROM admin_secciones ads LEFT JOIN secciones_nombres sn ON ads.id = sn.id AND sn.leng_id = 1 WHERE items = 1 ORDER BY ads.orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila_exsec = $cons_exsec->fetch_row()) {
		echo "<select name=\"_a\" onchange=\"atributosDeSecciones(this.options[this.selectedIndex].value)\">";
		do {
		  echo "<option value=\"{$fila_exsec[0]}\">{$fila_exsec[1]}</option>";
		 }while($fila_exsec = $cons_exsec->fetch_row());
		$cons_exsec->close();
		echo "</select>
<script type=\"text/javascript\">
function atributosDeSecciones(id)
 {
  alert(id);
 }
var atributosSecciones = {};\n";
		if(!$cons_exsec = $mysqli->query("SELECT seccion_id, atributo_id, atributo FROM items_secciones_a_atributos isaa, admin_secciones ads, items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = 1 WHERE ia.tipo_id = 1 AND isaa.atributo_id = ia.id AND isaa.seccion_id = ads.id AND ads.items = 1 ORDER BY seccion_id, isaa.orden"))
            die("\n".__LINE__." mySql: ".$mysqli->error);
		if($fila_exsec = $cons_exsec->fetch_row()) {
		  $s_id = 0;
		  do {
			if($fila_exsec[0] != $s_id) {
			  $s_id = $fila_exsec[0];
			  echo "atributosSecciones[{$fila_exsec[0]}] = {};\n";
			 }
			echo "atributosSecciones[{$fila_exsec[0]}][{$fila_exsec[1]}] = '{$fila_exsec[2]}';\n";
		   }while($fila_exsec = $cons_exsec->fetch_row());
		  $cons_exsec->close();
		  echo "</script>";
		 }
	   }
	  echo "</td></tr>
	  <tr>
	   <td>Campo:</td>
	   <td><select id=\"asdfg\"><option> </option></select></td></tr>";
	 }
	elseif($tipo == 8 || $tipo == 10) {
	  if(!$extra_arr && $extra) $extra_arr = $extra;
	  $sel_metodo_img[$extra_arr[0][0]] = " selected=\"selected\"";
	  $sel_metodo_imgch[$extra_arr[1][0]] = " selected=\"selected\"";

	  $sel_posX_k = $extra_arr[0][3][1] ? (($extra_arr[0][3][1] < 0) ? 3 : 1) : 0;
	  $sel_posY_k = $extra_arr[0][3][2] ? (($extra_arr[0][3][2] < 0) ? 3 : 1) : 0;
	  $sel_posX[$sel_posX_k] = " selected=\"selected\"";
	  $sel_posY[$sel_posY_k] = " selected=\"selected\"";
	  echo "

	  <tr>
	   <td><input type=\"checkbox\" name=\"prot[string]\" value=\"1\" /> <select name=\"prottipo[string]\">";
foreach($atribxtipo['string'] AS $string_id => $string)
  echo "<option value=\"{$string_id}\">{$string}</option>";
echo "</select></td>
	   <td></td></tr>
	  <tr>
	   <td><input type=\"checkbox\" name=\"prot[date]\" value=\"1\" /> <select name=\"prottipo[date]\">";
foreach($atribxtipo['date'] AS $string_id => $string)
  echo "<option value=\"{$string_id}\">{$string}</option>";
echo "</select></td>
	   <td></td></tr>
	  <tr>
	   <td><input type=\"checkbox\" name=\"prot[text]\" value=\"1\" /> <select name=\"prottipo[text]\">";
foreach($atribxtipo['text'] AS $string_id => $string)
  echo "<option value=\"{$string_id}\">{$string}</option>";
echo "</select></td>
	   <td></td></tr>
	  <tr>
	   <td><input type=\"checkbox\" name=\"prot[num]\" value=\"1\" /> <select name=\"prottipo[num]\">";
foreach($atribxtipo['num'] AS $string_id => $string)
  echo "<option value=\"{$string_id}\">{$string}</option>";
echo "</select></td>
	   <td></td></tr>
	  <tr>
	   <td>Imagen</td>
	   <td>";
	    //$marcas = array();
	  if(!$cons_marcas = $mysqli->query("SELECT id, archivo FROM imagenes_marcas ORDER BY 1"))
          die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila_marcas = $cons_marcas->fetch_row()) {
		echo "<div id=\"img_mustraOp\" style=\"position:absolute;width:250px;height:200px;background-color:#eee;overflow:auto;display:none;\"><span onclick=\"this.parentNode.style.display='none'\">X</span><ul style=\"width:250px;height:180px;overflow:auto;\">";
		do {
		  echo "<li><img src=\"marcas/{$fila_marcas[1]}\" onclick=\"selMarca(this)\" alt=\"{$fila_marcas[1]}\" /></li>";
		  //$marcas[$fila_marcas[0]] = $fila_marcas[1];
		 }while($fila_marcas = $cons_marcas->fetch_row());
		$cons_marcas->close();
		echo "</ul></div>";
	   }
	  $min_deshab = ($extra_arr[0][0] == 'escalar') ? false : ' disabled="disabled"';
	  echo "
	    <ul>
	     <li><label for=\"metodo_img\">Método</label> <select name=\"metodo_img\" id=\"metodo_img\" onchange=\"imgHabMinimo(this)\"><option value=\"escalar\"{$sel_metodo_img['escalar']}>Escalar</option><option value=\"recortar\"{$sel_metodo_img['recortar']}>Recortar</option></select></li>
	     <li><label for=\"ancho_img\">Ancho</label> <input type=\"text\" name=\"ancho_img\" id=\"ancho_img\" value=\"{$extra_arr[0][1]}\" size=\"4\" maxlength=\"4\" /> <label for=\"minancho_img\">mínimo</label> <input type=\"text\" name=\"minancho_img\" id=\"minancho_img\" value=\"{$extra_arr[0][4]}\" size=\"4\" maxlength=\"4\"{$min_deshab} /></li>
	     <li><label for=\"alto_img\">Alto</label> <input type=\"text\" name=\"alto_img\" id=\"alto_img\" value=\"{$extra_arr[0][2]}\" size=\"4\" maxlength=\"4\" /> <label for=\"minalto_img\">mínimo</label> <input type=\"text\" name=\"minalto_img\" id=\"minalto_img\" value=\"{$extra_arr[0][5]}\" size=\"4\" maxlength=\"4\"{$min_deshab} /></li>
	     <li><label for=\"marca\">Usar marca de agua</label> <input type=\"checkbox\" name=\"marca\" id=\"marca\" value=\"1\" ".(is_array($extra_arr[0][3]) ? "checked=\"checked\" " : " ")." onclick=\"mostrarMarcas(this)\" /><input type=\"hidden\" name=\"marca_arch\" value=\"{$extra_arr[0][3][0]}\" /> <img src=\"".($extra_arr[0][3] ? 'marcas/'.$extra_arr[0][3][0] : 'img/trans')."\" id=\"img_marca\" alt=\"gnome-gmush.png\" onclick=\"document.getElementById('img_mustraOp').style.display='block'\" /><ul>
<li>Posición horizontal: <select name=\"posX\"><option value=\"1\"{$sel_posX[1]}>desde la izquierda</option><option value=\"0\"{$sel_posX[0]}>al centro</option><option value=\"3\"{$sel_posX[3]}>desde la derecha</option></select> <input type=\"text\" name=\"pxX\" size=\"3\" /></li>
<li>Posición vertical: <select name=\"posY\"><option value=\"1\"{$sel_posY[1]}>desde arriba</option><option value=\"0\"{$sel_posY[0]}>al centro</option><option value=\"3\"{$sel_posY[3]}>desde abajo</option></select> <input type=\"text\" name=\"pxY\" size=\"3\" /></li></ul></li>
	    </ul></td></tr>
	  <tr>
	   <td>Miniatura</td>
	   <td>
	    <ul>
	     <li><label for=\"metodo_imgch\">Método</label> <select name=\"metodo_imgch\" id=\"metodo_imgch\"><option value=\"escalar\"{$sel_metodo_imgch['escalar']}>Escalar</option><option value=\"recortar\"{$sel_metodo_imgch['recortar']}>Recortar</option></select></li>
	     <li><label for=\"ancho_imgch\">Ancho</label> <input type=\"text\" name=\"ancho_imgch\" id=\"ancho_imgch\" value=\"{$extra_arr[1][1]}\" size=\"4\" maxlength=\"4\" /></li>
	     <li><label for=\"alto_imgch\">Alto</label> <input type=\"text\" name=\"alto_imgch\" id=\"alto_imgch\" value=\"{$extra_arr[1][2]}\" size=\"4\" maxlength=\"4\" /></li>
	    </ul></td></tr>";
		//if($modificar_archivos_img)
		// {
	  echo "
	  <tr>
	   <td colspan=\"2\" id=\"regImagenes\"><a onclick=\"iniciarRegImgs({$id})\">Regenerar imágenes</a> <span> </span></td></tr>";
		// }
	 }
	elseif($tipo == 9) {
	  if(!$extra_arr && !empty($extra)) eval('$extra_arr = '.$extra.';');
	  if($extra_arr['permitidos']) {
		$sel_tipo['permitidos'] = " selected=\"selected\"";
	   	$extensiones = implode(", ", $extra_arr['permitidos']);
	   }
	  elseif($extra_arr['negados']) {
		$sel_tipo['negados'] = " selected=\"selected\"";
		$extensiones = implode(", ", $extra_arr['negados']);
	   }
	  echo "
	  <tr>
	   <td><label>Extensiones <select name=\"extra[tipo]\"><option value=\"permitidos\"{$sel_tipo['permitidos']}>permitidas</option><option value=\"negados\"{$sel_tipo['negados']}>denegadas</option></select>:</label></td>
	   <td><input type=\"text\" name=\"extra[extensiones]\" value=\"{$extensiones}\" size=\"30\" maxlength=\"30\" title=\"Ingrese extensiones separadas por comas (,)\" /></td></tr>";
	 }
	elseif($tipo == 11) {
	  if(!$extra_arr) eval('$extra_arr = '.$extra.';');
	  echo "
	  <tr>
	   <td>Imagen</td>
	   <td>
	    <ul>";
	  $extra_indice = 0;
	  foreach($extra_arr AS $extra_k => $extra_v)
          echo "<li><input type=\"text\" name=\"extra_v[".$extra_indice++."]\" value=\"{$extra_v}\" /></li>";
	  for($i = 0; $i < 2; $i++)
        echo "<li><input type=\"text\" name=\"extra_v[".$extra_indice++."]\" /></li>";
	  echo "
	    </ul></td></tr>
	  <tr>
	   <td colspan=\"2\">".var_export($extra_arr, true)."</td></tr>";
	 }
	elseif($tipo == 12) {
	  echo "
	  <tr>
	   <td>Opciones</td>
	   <td>
	    <ul>";
	  $extra_indice = 0;
	  if(!$consulta = $mysqli->query("SELECT id FROM campos_opciones WHERE campo_id = {$id}"))
        die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila = $consulta->fetch_row()) {
		do {
		  echo "<li><input type=\"text\" name=\"extra_v[".$extra_indice++."]\" value=\"{$extra_v}\" /></li>";
		 }while($fila = $consulta->fetch_row());
	   }
	  //foreach($extra_arr AS $extra_k => $extra_v) echo "<li><input type=\"text\" name=\"extra_v[".$extra_indice++."]\" value=\"{$extra_v}\" /></li>";
	  for($i = 0; $i < 2; $i++)
        echo "<li><input type=\"text\" name=\"extra_v[".$extra_indice++."]\" /></li>";
	  echo "
	    </ul></td></tr>";

	 }
	elseif($tipo == 16) {
	  echo "
	  <tr>
	   <td>Moneda</td>
	   <td>";

	  if(!$consulta = $mysqli->query("SELECT id, nombre FROM monedas_nombres WHERE leng_id = 1 ORDER BY nombre"))
          die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila = $consulta->fetch_row()) {
		echo "<select name=\"extra_v\">";
		do {
		  echo "<option value=\"{$fila[0]}\"";
		  if($extra == $fila[0])
              echo " selected=\"selected\"";
		  echo ">{$fila[1]}</option>";
		 }while($fila = $consulta->fetch_row());
		echo "</select>";
		$consulta->close();
	   }

	  echo "</td></tr>";

	 }
/*
	elseif($tipo == 19)
	 {
*********************************************************************
	  if(!$consulta = $mysqli->query("SELECT ia.id, ia.identificador, ian.atributo, ia.sugerido, ia.unico, ia.tipo_id, isaa.por_omision, isaa.sup_atributo_id, isaa.orden, at.op_listado, at.op_oculto, isaa.orden IS NULL AS ordennull FROM items_atributos ia LEFT JOIN subitems_supatributos_a_atributos isaa ON ia.id = isaa.atributo_id AND isaa.sup_atributo_id = '{$id}', items_atributos_n ian, atributos_tipos at WHERE ia.id = ian.id AND ia.tipo_id != 19 AND ia.tipo_id = at.id AND ian.leng_id = '1' ORDER BY isaa.sup_atributo_id DESC, ordennull, orden, ia.id")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila = $consulta->fetch_assoc())
	   {
		echo "
    </tbody>
	</table>
	</form>
  <h3>Atributos</h3>
  <form action=\"campo?id={$id}&amp;seccion={$_REQUEST['seccion']}\" method=\"post\">
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
		if($id)
		 {
		  echo "
     ><td>Valor por omisión</td
     ><td>Orden</td";
		 }
		echo "
    ></tr
   ></thead
   ><tfoot
    ><tr
     ><td colspan=\"8\"><input type=\"submit\" value=\"Aceptar\" /></td></tr
   ></tfoot
   ><tbody";

		$sel = array(false, " checked=\"checked\"");
		$selfila = array(false, " class=\"sel_fila\"");
		$sugerido_ops = array("No", "Si", "Obligatorio");
		$n_orden = 0;
		do
	     {
		  $check = $fila['sup_atributo_id'] ? 1 : 0;
		  if($check)
		   {
			$n_orden++;
			$mysqli->query("UPDATE items_secciones_a_atributos SET orden = {$n_orden} WHERE seccion_id = '{$fila['sup_atributo_id']}' AND atributo_id = '{$fila['id']}'");
			$orden = $n_orden;
			$campo_seccion = "&amp;seccion={$_REQUEST['seccion']}";
		   }
		  else
		   {
			$salida = 0;
			$campo_seccion = false;
			$orden = false;
		   }
		  echo "
	><tr{$selfila[$check]}
     ><td><input type=\"checkbox\" name=\"attr[]\" value=\"{$fila['id']}\"{$sel[$check]} /></td
	 ><td><a href=\"".APU."campo?id={$fila['id']}{$campo_seccion}\">{$fila['identificador']}</a></td
	 ><td>{$fila['atributo']}</td
	 ><td>{$sugerido_ops[$fila['sugerido']]}</td
	 ><td>{$sugerido_ops[$fila['unico']]}</td
	 ><td>{$tipos[$fila['tipo_id']][0]}</td";
	 // if($seccion_id)
	 //  {
		  echo "
	 ><td>{$poromision[$fila['id']]}</td
	 ><td><input type=\"text\" name=\"orden[{$fila['id']}]\" value=\"{$orden}\" size=\"2\" maxlength=\"2\" /></td
	></tr";
		 }while($fila = $consulta->fetch_assoc());
		$consulta->close();
		echo ">";
*/
/*
	></tbody
   ></table>
  </form>";
*/
//	   }
/*********************************************************************/
//	 }
	elseif($tipo == 22) {
	  if($extra)
          eval('$extra_arr = '.$extra.';');
	  echo "
	  <tr>
	   <td>Protocolos</td>
	   <td>
	    <ul>
	     <li><input type=\"checkbox\" name=\"extra[1]\" value=\"1\" id=\"ex1\"".($extra_arr[1] ? ' checked="checked"' : '')." /> <label for=\"ex1\">http</label></li>
	     <li><input type=\"checkbox\" name=\"extra[2]\" value=\"2\" id=\"ex2\"".($extra_arr[2] ? ' checked="checked"' : '')." /> <label for=\"ex2\">https</label></li>
	     <li><input type=\"checkbox\" name=\"extra[3]\" value=\"3\" id=\"ex3\"".($extra_arr[3] ? ' checked="checked"' : '')." /> <label for=\"ex3\">ftp</label></li>
	     <li><input type=\"checkbox\" name=\"extra[4]\" value=\"4\" id=\"ex4\"".($extra_arr[4] ? ' checked="checked"' : '')." /> <label for=\"ex4\">gopher</label></li>
	     <li><input type=\"checkbox\" name=\"extra[5]\" value=\"5\" id=\"ex5\"".($extra_arr[5] ? ' checked="checked"' : '')." /> <label for=\"ex5\">mailto</label></li>
	    </ul></td></tr>";
	 }
	echo "
	 </tbody>
	</table>
	</form>";
   }

/*
$extra[] = array($_POST['metodo_img'], $_POST['ancho_img'], $_POST['alto_img']);
$extra[] = array($_POST['metodo_imgch'], $_POST['ancho_imgch'], $_POST['alto_imgch']);
$elarray = str_replace(array("\n", "  "), "", var_export($unarray, true));
$extra2 = array(0 => array (0 => 'recortar',1 => 496,2 => 218,),1 => array (0 => 'recortar',1 => 40,2 => 40,));
echo "
<pre>

".var_export($extra, true)."
".var_export($extra2, true)."


</pre>

";
*/

/*



    [metodo_img] => escalar
    [ancho_img] => 456
    [alto_img] => 456
    [metodo_imgch] => escalar
    [ancho_imgch] => 456
    [alto_imgch] => 456
array (0 => array (0 => 'recortar',1 => 496,2 => 218,),1 => array (0 => 'recortar',1 => 40,2 => 40,),)





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

    $asociaciones = array('secciones_a_atributos' => "Secciones", 'items_secciones_a_atributos' => "Ítems", 'categorias_secciones_a_atributos' => "Categorías");
    foreach($asociaciones AS $tabla => $tipo) {
        if(!$consulta = $mysqli->query("SELECT ads.nombre FROM {$tabla} saa JOIN admin_secciones ads ON saa.seccion_id = ads.id WHERE saa.atributo_id = {$id} ORDER BY ads.orden"))
            die("\n".__LINE__." mySql: ".$mysqli->error);
        if($fila = $consulta->fetch_row()) {
            echo "
            <h4>{$tipo}</h4>
            <ul>";
            do {
                echo "<li>{$fila[0]}</li>";
            }while($fila = $consulta->fetch_row());
            echo "</ul>";
        }
    }




//   }
//  else echo "no se encontró nada";
    include('inc/iapie.php');
}
else
    include('./error/404.php');

?>