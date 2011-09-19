<?php

require('inc/iniciar.php');
$mysqli = BaseDatos::Conectar();
require('inc/ad_sesiones.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <link rel="stylesheet" type="text/css" media="all" href="/css/campos.css" />
</head>
<body>
<?php

if($_POST['accion'] == "ag_atributo")
 {
  if(!empty($_POST['identificador']) && !empty($_POST['leng'][1]))
   {
	$mysqli->query("INSERT INTO items_atributos (`identificador`, `sugerido`, `unico`, `tipo_id`) VALUES ('{$_POST['identificador']}', '{$_POST['sugerido']}', '{$_POST['unico']}', '{$_POST['tipo']}')");
    if($id = $mysqli->insert_id)
     {
      foreach($_POST['leng'] AS $idioma_k => $idioma_v) $mysqli->query("INSERT INTO items_atributos_n (`id`, `leng_id`, `atributo`) VALUES ('${id}', '${idioma_k}', '${idioma_v}')");
     }
   }
 }
elseif($_POST['accion'] == "as_atributo")
 {
  $mysqli->query("DELETE FROM items_categorias_a_atributos WHERE `categoria_id` = '{$_REQUEST['seccion']}'");
  if(count($_POST['attr']))
   {
	foreach($_POST['attr'] AS $attr_v) $mysqli->query("INSERT INTO items_categorias_a_atributos (`categoria_id`, `atributo_id`, `orden`) VALUES ('{$_REQUEST['seccion']}', '${attr_v}', '{$_POST['orden'][$attr_v]}')");
   }
 }

if(!$consulta = $mysqli->query("SELECT id, nombre FROM admin_secciones WHERE `link` = 'listar' ORDER BY orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_row())
 {
  echo "
  <h3>Secciones</h3>
	<ol";
  do
   {
	echo "
	 ><li>";
	if($fila[0] == $_REQUEST['seccion'])
	 {
	  $seccion = $_REQUEST['seccion'];
	  echo "<b>{$fila[1]}</b>";
	 }
	else echo "<a href=\"".$_SERVER['PHP_SELF']."?seccion={$fila[0]}\">{$fila[1]}</a>";
	echo "</li";
   }while($fila = $consulta->fetch_row());
  $consulta->close();
  echo "
	></ol>";
  if($seccion) echo "<a href=\"".$_SERVER['PHP_SELF']."\">Volver</a>";
 }

$tipos = array();
if(!$consulta = $mysqli->query("SELECT at.id, atn.nombre, at.tipo FROM atributos_tipos at JOIN atributos_tipos_nombres atn ON at.id = atn.id AND atn.leng_id = '1' ORDER BY atn.nombre")) die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_row())
 {
  echo "
  <form action=\"".$_SERVER['PHP_SELF']."?seccion={$_REQUEST['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"ag_atributo\" />
  <fieldset>
   <legend>Agregar atributo</legend>
   <ol>
    <li><label for=\"identificador\">Identificador:</label> <span><input type=\"text\" name=\"identificador\" id=\"identificador\" size=\"15\" maxlength=\"15\" /></span></li>
    <li><label for=\"tipo\">Tipo:</label> <span><select name=\"tipo\" id=\"tipo\">";
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
	</select></span></li>
	<li><label>Sugerido:</label> <span><input type=\"radio\" name=\"sugerido\" id=\"sugerido0\" value=\"0\" disabled=\"disabled\" /><label for=\"sugerido0\">No</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido1\" value=\"1\" checked=\"checked\" /><label for=\"sugerido1\">Si</label> <input type=\"radio\" name=\"sugerido\" id=\"sugerido2\" value=\"2\" /><label for=\"sugerido2\">Obligatorio</label></span></li>
	<li><label>Único:</label> <span><input type=\"radio\" name=\"unico\" id=\"unico0\" value=\"0\" disabled=\"disabled\" /><label for=\"unico0\">No</label> <input type=\"radio\" name=\"unico\" id=\"unico1\" value=\"1\" checked=\"checked\" /><label for=\"unico1\">Si</label></span></li>";


  if(!$consulta_lengs = $mysqli->query("SELECT id, leng_cod, xml_lang, dir FROM lenguajes ORDER BY id")) die("\n".__LINE__." mySql: ".$mysqli->error);
  if($fila_lengs = $consulta_lengs->fetch_row())
   {
	echo "
	<li><label>Etiqueta/s:</label> <div><ul>";
	do
	 {
	  echo "
	 <li><label for=\"leng{$fila_lengs[0]}\">({$fila_lengs[1]})</label> <input type=\"text\" name=\"leng[{$fila_lengs[0]}]\" id=\"leng{$fila_lengs[0]}\" /></li>";
	 }while($fila_lengs = $consulta_lengs->fetch_row());
	echo "</ul></div></li>";
   }
  echo "
   </ol>
   <div><input type=\"submit\" value=\"Aceptar\" /></div>
  </fieldset>
  </form>";
 }


if(!$consulta = $mysqli->query("SELECT iv.`atributo_id`, iv.`string`, iv.`date`, iv.`text`, iv.`int` FROM items_valores iv WHERE iv.item_id IS NULL")) die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_assoc())
 {
  do
   {
	$poromision[$fila['atributo_id']] = $fila[$tipos[$fila['atributo_id']][1]];
   }while($fila = $consulta->fetch_assoc());
  $consulta->close();
 }

//SELECT atributo_id, orden, orden IS NULL AS ordennull FROM items_categorias_a_atributos i WHERE categoria_id = '5' ORDER BY ordennull, orden

if(!$consulta = $mysqli->query("SELECT ia.id, ia.identificador, ian.atributo, ia.sugerido, ia.unico, ia.tipo_id, ia.poromision, icaa.categoria_id, icaa.orden, icaa.orden IS NULL AS ordennull FROM items_atributos ia LEFT JOIN items_categorias_a_atributos icaa ON ia.id = icaa.atributo_id AND icaa.categoria_id = '{$_REQUEST['seccion']}', items_atributos_n ian WHERE ia.id = ian.id AND ian.leng_id = '1' ORDER BY ordennull, orden, ia.id")) die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_assoc())
 {
  echo "
  <h3>Atributos</h3>
  <form action=\"".$_SERVER['PHP_SELF']."?seccion={$_REQUEST['seccion']}\" method=\"post\">
   <input type=\"hidden\" name=\"accion\" value=\"as_atributo\" />
  <table
   ><thead
    ><tr
     ><td style=\"width:20px;\"></td
     ><td>identificador</td
     ><td>Nombre</td
     ><td>sugerido</td
     ><td>único</td
     ><td>tipo_id</td";
  if($seccion)
   {
	echo "
     ><td>valor por omisión</td
     ><td>orden</td";
   }
  echo "
    ></tr
   ></thead
   ><tbody";

  $sel = array(false, " checked=\"checked\"");
  $selfila =array(false, " class=\"sel_fila\""); 
  do
   {
	$check = $fila['categoria_id'] ? 1: 0;
	echo "
	><tr{$selfila[$check]}";
//foreach($fila AS $k => $v) echo "<td>({$k}) ${v}</td>";
/*
(id) 4
(identificador) fecha_inicio
(atributo) Fecha de inicio
(sugerido) 1
(unico) 1
(tipo_id) 5
(poromision) 
(string) 
(date) 1981-02-24 00:00:00
(text) 
(int)
*/


	echo "
     ><td><input type=\"checkbox\" name=\"attr[]\" value=\"{$fila['id']}\"{$sel[$check]} /></td
	 ><td>{$fila['identificador']}</td
	 ><td>{$fila['atributo']}</td
	 ><td>{$fila['sugerido']}</td
	 ><td>{$fila['unico']}</td
	 ><td>{$tipos[$fila['tipo_id']][0]}</td";
	if($seccion)
	 {
	  echo "
	 ><td>{$poromision[$fila['id']]}</td
	 ><td><input type=\"text\" name=\"orden[{$fila['id']}]\" value=\"{$fila['orden']}\" size=\"2\" maxlength=\"2\" /></td";
	 }
	echo "
	></tr";
//print_r($fila);
//	echo "
//	<li></li>";
//	if($fila[0] == 1) echo " selected=\"selected\"";
//	echo ">{$fila[1]}</option>";
   }while($fila = $consulta->fetch_assoc());
  $consulta->close();
  echo "
	></tbody
   ></table>\n";
  echo $seccion ? " <input type=\"submit\" value=\"Aceptar\" />": " <input type=\"submit\" value=\"Eliminar\" />";
  echo "
  </form>";
 }

?>
</body>
</html>