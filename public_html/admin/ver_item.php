<?php

$mod = "listar";
require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();

$consulta_seccion = $_REQUEST['id'] ? "SELECT ase.id, ase.`nombre`, identificador, categorias FROM `admin_secciones` ase LEFT JOIN items i ON ase.id = i.seccion_id WHERE i.`id` = '{$_REQUEST['id']}' AND ase.`link` = '${mod}' LIMIT 1" : "SELECT id, `nombre`, identificador, categorias FROM `admin_secciones` WHERE `id` = '{$_REQUEST['seccion']}' AND `link` = '${mod}' LIMIT 1";
$cons_seccion = $mysqli->query($consulta_seccion);
if($fila_seccion = $cons_seccion->fetch_row())
 {
  $seccion_id = $fila_seccion[0];
  $titulo = $fila_seccion[1];
  $seccion = "editar";
  $t_categorias = $fila_seccion[3];
  //$secciones = new adminsecciones();

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
 <link rel="stylesheet" type="text/css" href="/css/ia.css" />

<?php

  $no_poromision = TRUE;
  $transaccion = "Agregar";
  if(!$consulta_lengs = $mysqli->query("SELECT l.id, nombre, nombre_nativo, xml_lang, dir FROM `lenguajes` l LEFT JOIN `lenguajes_nombres` ln ON l.id = ln.id AND ln.leng_id_nombre = '1' ORDER BY iso_639_3")) die("\n".__LINE__." mySql: ".$mysqli->error); // WHERE leng_habilitado = '1'
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

  if(!empty($_REQUEST['id']))
   {
	if(!$consulta_item = $mysqli->query("SELECT seccion_id, estado_id, orden, f_creado FROM `items` WHERE id = '".$_REQUEST['id']."'")) die("\n".__LINE__." mySql: ".$mysqli->error);
	if($fila_item = $consulta_item->fetch_assoc())
	 {
	  $mysqli->query("UPDATE items SET tiempoedicion = '".date("Y-m-d H:i:s", time()+480)."', uidedicion = '{$_SERVER['UNIQUE_ID']}', usuarioedicion = {$_SESSION['usuario_id']} WHERE id = '{$_REQUEST['id']}'");
	  $transaccion = "Editar";
	  $estado = $fila_item['estado_id'];
	  $creada = $fila_item['f_creado'];
	  $seccion_id = $fila_item['seccion_id'];
	  $consulta_item->close();
	  $id = $_REQUEST['id'];

	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, `date`, `text`, `int`, `num`, leng_id FROM items_valores WHERE item_id = '${id}'")) echo __LINE__." - ".$mysqli->error;
	  if($fila_valores = $cons_valores->fetch_row())
	   {
	    do
	     {
		  $valor = $fila_valores[0];
		  if($fila_valores[7]) $valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
		  else $valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
	     }while($fila_valores = $cons_valores->fetch_row());
	 	$cons_valores->close();
	   }
	 }
   }


//  include('inc/iaencab.php');
  $seccion = $fila_seccion[2];




echo "</head>
<body id=\"ventanadialogo\">
 <div><div>";


  $atributos = array();
  if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, items_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND seccion_id = '{$seccion_id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
  if($fila_at = $atributos_tipos->fetch_assoc())
   {
	do
	 {
	  $attr_id = array_shift($fila_at);
	  $atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
	 }while($fila_at = $atributos_tipos->fetch_assoc());
	$atributos_tipos->close();
   }

  function subcategoria($subcat, $n, $seleccionado = false, $exclusion = false)
   {
	global $mysqli, $seccion_id;
	$separador_niv = "&nbsp;&nbsp;&nbsp;&nbsp;";
	if($exclusion)
	 { $excluir = "c.`id` != '$exclusion' AND"; }

	if(!$tbsubcat = $mysqli->query("SELECT c.`id`, c.`superior`, cn.`nombre`, c.orden FROM `items_categorias` c JOIN `items_categorias_nombres` cn ON c.`id` = cn.`id` AND cn.leng_id = 1 WHERE ${excluir} `superior` = '${subcat}' AND seccion_id = ${seccion_id} ORDER BY cn.`nombre`")) die(__LINE__." - ".$mysqli->error);
	if($row_subcat = $tbsubcat->fetch_row())
	 {
	  $i = 0;
	  do
	   {
	   	$i++;
		echo "<option value=\"".$row_subcat[0]."\"";
		if ($seleccionado == $row_subcat[0])
		 { echo " selected=\"selected\""; }
		echo ">".str_repeat($separador_niv, $n).$row_subcat[2]."</option>\n";

		$subcat = $row_subcat[0];
		subcategoria($subcat, ++$n , $seleccionado, $exclusion);
		$n--;
	   } while($row_subcat = $tbsubcat->fetch_row());
	 }
   }

  if($t_categorias)
   {
	echo "<fieldset><legend>Categorías</legend>



<!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->



";
	if(@include('iacache/categorias_'.$seccion_id.'.php'))
	 {
	  echo "


<!-- bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb -->


<ul id=\"as_categorias\"></ul>
<script type=\"text/javascript\">";
?>

var asCats = {};
function asignarCat(id, ordenN)
 {
  if(asCats[id] != null) return false;
  asCats[id] = id;
  var sup = categorias[id][0];
  var ruta = rutas[sup] ? rutas[sup]+' > ' : '';

  var lista = document.getElementById('as_categorias');
  var li = document.createElement('li');
  var span = document.createElement('span');
  span.appendChild(document.createTextNode(ruta+categorias[id][1]));
  li.appendChild(span);
  lista.appendChild(li);
 }

<?php


	  if($id)
	   {
	    if(!$resultcats = $mysqli->query("SELECT categoria_id, orden FROM items_a_categorias WHERE item_id = ${id}")) die(__LINE__." - ".$mysqli->error);
	    if($row_cats = $resultcats->fetch_row())
	     {
		  do
		   {
		    echo "\nasignarCat({$row_cats[0]}, ".($row_cats[1] ? $row_cats[1] : 'null').");";
		   }while($row_cats = $resultcats->fetch_row());
	     }
	   }
//	  elseif(!empty($_REQUEST['cat']))
//		echo "\nasignarCat({$_REQUEST['cat']}, null);";
	  echo "
</script>\n";
	 }
	//else echo "<p>No hay categorías disponibles.</p>";
	echo "</fieldset>";
   }

?>

	<table class="tabla">
	 <tbody>

<?php

	$formcampo = new verCampo();
	foreach($atributos AS $k => $a)
	 {
	  $formcampo->id = $k;
	  $formcampo->sugerido = $a['sugerido'];
	  $formcampo->unico = $a['unico'];
	  $formcampo->tipo = $a['tipo'];
	  $formcampo->subtipo = $a['subtipo'];
	  $formcampo->nombre = $a['nombre'];
	  $formcampo->poromision = $a['poromision'];
	  $formcampo->extra = $a['extra'];
	  //$formcampo->identificador = $a['identificador'];
	  $formcampo->valores = $valores[$k];
	  echo "
	  <tr>".$formcampo->imprimir()."</tr>";
	 }

?>
	 </tbody>
	</table>

<?php

   //}
  include('inc/iapie.php');
 }
else include('./error/404.php');

?>