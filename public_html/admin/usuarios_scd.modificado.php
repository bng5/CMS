<?php

//header("Content-type: text/html");

$titulo = "Usuarios";
$seccion = "usuarios";
$seccion_id = 4;

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$id = $_REQUEST['id'];
$ia = $_REQUEST['ia'];

$mysqli = BaseDatos::Conectar();

$atributos = array();
//if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra, isaa.superior, at.nodo_tipo FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, items_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND seccion_id = '{$seccion_id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo AS nombre, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra, isaa.superior FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, items_secciones_a_atributos isaa LEFT JOIN usuarios_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`usuario_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND seccion_id = '{$seccion_id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
//$atributos_all = $atributos_tipos->fetch_all(MYSQLI_ASSOC);
if($fila_at = $atributos_tipos->fetch_assoc())
 {
  do
   {
	$atributos_all[] = $fila_at;
	$attr_id = array_shift($fila_at);
	$atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
   }while($fila_at = $atributos_tipos->fetch_assoc());
  $atributos_tipos->close();
 }

if($_POST['ia'] == "agregar" || $_POST['ia'] == "modificar")
 {
  if($_POST['ia'] == "modificar")
    $cond = "AND id != '{$_POST['id']}'";
  $resultado = $mysqli->query("SELECT `usuario`, `email` FROM `usuarios` WHERE (`usuario` = '{$_POST['usuario']}' OR `email` = '{$_POST['email']}') ${cond} LIMIT 1");
  $fila = $resultado->fetch_row();
  $resultado->close();
  if(empty($_POST['usuario']))
   { $mensaje_error[] = "Debe completar el campo 'Nombre de usuario'."; }
  else
   {
    if(strlen($_POST['usuario']) < 5 || strlen($_POST['usuario']) > 22)
     { $mensaje_error[] = "El nombre de usuario debe contener entre 5 y 22 caracteres."; }
    else
     {
	  if($fila[0] == strtolower($_POST['usuario']))
	   { $mensaje_error[] = "El nombre de usuario '".$_POST['usuario']."' no est&aacute; disponible."; }
	 }
   }

  if(empty($_POST['email']))
   { $mensaje_error[] = "Debe ingresar una casilla de correos."; }
  else
   {
    if(!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $_POST['email']))
	 { $mensaje_error[] = "La casilla ingresada no parece ser una casilla v&aacute;lida."; }
	else
	 {
	  if($fila[1] == strtolower($_POST['email']))
        $mensaje_error[] = "La casilla de correo ya se encuentra registrada en otro usuario.";
	  else
        $casilla = $_POST['email'];
	 }
   }

  if(($_POST['ia'] == "agregar" && (empty($_POST['adclave']) || empty($_POST['adclave2']))) || ($_POST['ia'] == "modificar" && (empty($_POST['adclave']) xor empty($_POST['adclave2']))))
   { $mensaje_error[] = "Ingrese la nueva contrase&ntilde;a dos veces."; }
  elseif($_POST['ia'] == "agregar" || ($_POST['ia'] == "modificar" && (!empty($_POST['adclave']) && !empty($_POST['adclave2']))))
   {
	if(strlen($_POST['adclave']) < 6)
	 { $mensaje_error[] = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres."; }
	else
	 {
	  if($_POST['adclave'] !== $_POST['adclave2'])
        $mensaje_error[] = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden.";
	  else $clave = $_POST['adclave'];
	 }
   }

  if($mensaje_error && $_POST['ia'] == "agregar")
   {
    $ia = "editar";
    $fila = $_POST;
   }
  elseif($_POST['ia'] == "agregar")
   {
	$nombre = $_POST['nombre'] ? "'".$_POST['nombre']."'" : "null";
	$apellido = $_POST['apellido'] ? "'".$_POST['apellido']."'" : "null";
	$nombre_mostrar = $_POST['nombre_mostrar'] ? $_POST['nombre_mostrar'] : $_POST['usuario'];
	$mysqli->query("INSERT INTO `usuarios` (`usuario`, `nombre_mostrar`, `clave`, `email`, `creado`, `creado_por`, `admin`) VALUES ('".$_POST['usuario']."', '${nombre_mostrar}', SHA1('".$_POST['adclave']."'), LCASE('".$_POST['email']."'), now(), {$_SESSION['usuario_id']}, 0)");
	if($id = $mysqli->insert_id)
	 {
/**************************
Parche para lucalorenzini
*
* BORRAR
**************************
	  //$mysqli->query("INSERT INTO usuarios_permisos (usuario_id, area_id, item_id, permiso_id) VALUES (${id}, 1, 11, 1), (${id}, 1, 19, 1)");
*/
	  //$mysqli->query("INSERT INTO usuarios_datos (id, nombre, apellido, pais_id, estado, ciudad, direccion, telefono, celular) VALUES (${id}, '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['pais']}', '{$_POST['estado']}', '{$_POST['ciudad']}', '{$_POST['direccion']}', '{$_POST['telefono']}', '{$_POST['celular']}')");
	  $ia = "editar";
	  $fila = $_POST;
	  header("Location: /usuarios?id=".$id);
	  exit;
	 }
	else
	  $mensaje_error[] = "Debido a un error en el servidor no fue posible guardar el usuario.";
   }
  elseif($_POST['ia'] == "modificar")
   {
    $id = $_POST['id'];
	if($casilla)
      $mod[] = "`email` = LCASE('${casilla}')";
	if($clave)
      $mod[] = "`clave` = SHA1('${clave}')";

	if($mod)
	  $mysqli->query("UPDATE `usuarios` SET ".implode(",", $mod)." WHERE `usuario` = '{$_POST['usuario']}' AND id = '{$_POST['id']}'");


	/* Grupos */
	$mysqli->query("DELETE FROM usuarios_a_grupos WHERE usuario_id = ${id}");
	if(is_array($_POST['grupo']))
	 {
	  foreach($_POST['grupo'] AS $grupo)
	   {
	    if(!intval($grupo))
		 {
		  $mysqli->query("INSERT INTO usuarios_grupos (`grupo`) VALUES ('${grupo}')");
		  $grupo = $mysqli->insert_id;
		 }
	    $mysqli->query("INSERT INTO usuarios_a_grupos VALUES (${id}, ${grupo})");
	   }
	 }

	$datos_modif = array();
	if(isset($_POST['nombre'])) $datos_modif[] = "nombre = '{$_POST['nombre']}'";
	if(isset($_POST['apellido'])) $datos_modif[] = "apellido = '{$_POST['apellido']}'";
	if(isset($_POST['pais'])) $datos_modif[] = "pais = '{$_POST['pais']}'";
	if(isset($_POST['departamento'])) $datos_modif[] = "estado = '{$_POST['departamento']}'";
	if(isset($_POST['ciudad'])) $datos_modif[] = "ciudad = '{$_POST['ciudad']}'";
	if(isset($_POST['direccion'])) $datos_modif[] = "direccion = '{$_POST['direccion']}'";
	if(isset($_POST['telefono'])) $datos_modif[] = "telefono = '{$_POST['telefono']}'";
	if(isset($_POST['celular'])) $datos_modif[] = "celular = '{$_POST['celular']}'";
	//if(count($datos_modif))
	//  $mysqli->query("UPDATE `usuarios_datos` SET ".implode(",", $datos_modif)." WHERE id = {$id}");
   }


	if(is_array($_POST['dato']['m']))
	 {
	  foreach($_POST['dato']['m'] AS $mod_atributo_id => $mod_atributo_arr)
	   {
	   	foreach($mod_atributo_arr AS $mod_valor_id => $mod_valor)
		 {
		  if(empty($mod_valor) || !$_POST['dato']['m'][$mod_atributo_id])
		   {
			if($atributos[$mod_atributo_id]['sugerido'] == 2)
			 {
			  // error de campo obligatorio
			 }
			else
			 {
			  $mysqli->query("DELETE FROM usuarios_valores WHERE id = ${mod_valor_id}");

			 }
		   }
		  else
		   {
			if($atributos[$mod_atributo_id]['tipo'] == 'int' && $atributos[$mod_atributo_id]['subtipo'] == 4)
			 {
			  $galeria_attr = $mod_atributo_id;
			  $galeria = $mod_valor;
			  $mysqli->query("DELETE FROM galerias_imagenes WHERE galeria_id = ${mod_valor}");
			  $in = 1;
			  if(is_array($_POST['img']))
			   {
				foreach($_POST['img'] AS $imagenes)
				 {
				  $mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${mod_valor}, ${imagenes}, ${in})");
				  $in++;
				 }
			   }
			 }
			else
			 {
		   	  //if($atributos[$mod_atributo_id]['en_listado'] == 1 && !$listado[$mod_atributo_id])
			  if($atributos[$mod_atributo_id]['tipo'] == "text" && $atributos[$mod_atributo_id]['subtipo'] == 1)
				$mod_valor .= "', `int` = '".$_POST['prot'][$mod_atributo_id];
			  elseif($atributos[$mod_atributo_id]['tipo_id'] == 12 xor $atributos[$mod_atributo_id]['tipo_id'] == 13)
			   {
				$mod_valor = implode(";", current($_POST['dato']['m'][$mod_atributo_id]));
				unset($_POST['dato']['m'][$mod_atributo_id]);
			   }
			  $mysqli->query("UPDATE usuarios_valores SET `{$atributos[$mod_atributo_id]['tipo']}` = '${mod_valor}' WHERE id = ${mod_valor_id}");
			  if($mysqli->affected_rows && $atributos[$mod_atributo_id]['tipo_id'] == 16)
				$mysqli->query("INSERT INTO precios_historial (item_id, fecha, atributo_id, precio) VALUES (${id}, NOW(), ${mod_atributo_id}, '${mod_valor}')");
			  $modif += $mysqli->affected_rows;
			 }
		   }
		 }
	   }
	 }

	if(is_array($_POST['dato']['n']))
	 {
	  foreach($_POST['dato']['n'] AS $ins_atributo_id => $ins_atributo_arr)
	   {
	   	foreach($ins_atributo_arr AS $ins_leng_id => $ins_leng)
		 {
		  if($atributos[$ins_atributo_id]['tipo'] == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 4)
		   {
			$mysqli->query("INSERT INTO galerias (`creada`) VALUES (now())");
			$ins_leng = $mysqli->insert_id;
			$galeria_attr = $ins_atributo_id;
			$galeria = $ins_leng;
			$in = 1;
			if(is_array($_POST['img']))
			 {
			  foreach($_POST['img'] AS $imagenes)
			   {
if($_SESSION['usuario'] == 'etdp')
  echo "\n1
INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${ins_leng}, ${imagenes}, ${in})
\n";
				$mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (${ins_leng}, ${imagenes}, ${in})");
				$in++;
			   }
			 }
			//$mysqli->query("INSERT INTO items_valores (`atributo_id`, `item_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_atributo_id}')");
		   }

		  if(empty($ins_leng) || !$_POST['dato']['n'][$ins_atributo_id])
		    continue;
		  if($atributos[$ins_atributo_id]['tipo_id'] == 12 xor $atributos[$ins_atributo_id]['tipo_id'] == 13)
		   {
			$ins_leng = implode(";", $_POST['dato']['n'][$ins_atributo_id]);
			unset($_POST['dato']['n'][$ins_atributo_id]);
		   }
		  if(is_array($ins_leng))
		   {
			foreach($ins_leng AS $ins_valor)
			 {
			  if(empty($ins_valor)) continue;
			  $mysqli->query("INSERT INTO usuarios_valores (`atributo_id`, `usuario_id`, `leng_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, ${ins_leng_id}, '${ins_valor}')");
			  $modif += $mysqli->affected_rows;
			 }
		   }
		  else
		   {
			if($atributos[$ins_atributo_id]['tipo'] == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 6)
			 {
			  $rango = str_replace(" ", "", $ins_leng);
			  $rango = explode(",", $rango);
			  $pares = $_POST['extra'][$ins_atributo_id];
			  foreach($rango AS $numeros)
			   {
				$numeros = explode("-", $numeros);
				for($i = $numeros[0]; $i <= $numeros[1]; $i++)
				 {
				  if($pares == 1 && ($i%2) != 1) continue;
				  elseif($pares == 2 && ($i%2) == 1) continue;
				  $mysqli->query("INSERT INTO subitems (`item_id`, `atributo_id`, `codigo`) VALUES (${id}, ${ins_atributo_id}, '${i}')");
				  //echo $i." (".($i%2).")\n";
				 }
			   }
			  $mysqli->query("INSERT INTO usuarios_valores (`atributo_id`, `usuario_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_atributo_id}')");
			 }
			else
			 {
			  if($atributos[$ins_atributo_id]['tipo'] == "text" && $atributos[$ins_atributo_id]['subtipo'] == 1)
			   {
				$atributos[$ins_atributo_id]['tipo'] .= "`, `int";
				$ins_leng .= "', '".$_POST['prot'][$ins_atributo_id];
			   }
			  elseif($atributos[$ins_atributo_id]['tipo_id'] == 16)
				$mysqli->query("INSERT INTO precios_historial (item_id, fecha, atributo_id, precio) VALUES (${id}, NOW(), ${ins_atributo_id}, '${ins_leng}')");
//echo "\n\n\n2
//INSERT INTO items_valores (`atributo_id`, `item_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_leng}')
//\n\n\n";
			  $mysqli->query("INSERT INTO usuarios_valores (`atributo_id`, `usuario_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_leng}')");
			 }
			$modif += $mysqli->affected_rows;
		   }
		 }
	   }
	 }

 }


// modificar
if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
 {
  $modificar = $_POST['lista_item'];
  $modificadas = 0;

  // borrar
  if($_POST['mult_submit'] == "Eliminar")
   {
    $modificacion_tipo_accion = "eliminados";
    //$borrar = new Item_borrar($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
	  $mysqli->query("DELETE FROM usuarios WHERE `id` = '{$modificar[$i]}'");
	  $mysqli->query("DELETE FROM usuarios_permisos WHERE `usuario_id` = '{$modificar[$i]}'");
	  $mysqli->query("DELETE FROM usuarios_valores WHERE `usuario_id` = '{$modificar[$i]}'");
	  $modificadas++;
     }
    // = $borrar->modificadas;
   }
  // habilitar
  elseif($_POST['mult_submit'] == "Ascender a usuario-cliente" && $_SESSION['permisos'][$seccion_id] >= 4)
   {
    $modificacion_tipo_accion = "ascendidos";
    //$publicar = new Item_publicarBarriola($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
      //$publicar->Item($modificar[$i]);
	  $mysqli->query("UPDATE `usuarios` SET `admin` = '1' WHERE `id` = '{$modificar[$i]}' LIMIT 1");
	  $mysqli->query("INSERT INTO admin_permisos VALUES({$modificar[$i]},15,2), ({$modificar[$i]},8,3)");
	  $modificadas++;
     }
    // = $publicar->modificadas;
   }
  if($modificadas > 0)
   {
    $div_mensaje = "Usuarios ".$modificacion_tipo_accion.": ".$modificadas;
    //tabla_informacion("Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas);
   }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
 <script type="text/javascript" src="/js/editar.js" charset="utf-8"></script>

<?php

include('inc/iaencab.php');

if(count($mensaje_error))
 {
  echo "
<div><ul><li>".implode("</li><li>", $mensaje_error)."</li></ul></div>";
 }


// pedidos
if($ia == "pedidos")
 {
?>



<script type="text/javascript">
//<![CDATA[

obtenerPos = function(el)
 {
  var SL = 0, ST = 0;
  var is_div = /^div$/i.test(el.tagName);
  if (is_div && el.scrollLeft)
    SL = el.scrollLeft;
  if (is_div && el.scrollTop)
    ST = el.scrollTop;
  var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
  if (el.offsetParent)
   {
    var tmp = obtenerPosAbsoluta(el.offsetParent);
    r.x += tmp.x;
    r.y += tmp.y;
   }
  return r;
 };


var tablaRef;
function tablaReferencia(devMuestra, devCampo)
 {
  if(tablaRef)
   {
    return tablaRef;
   }
  tablaRef = new function()
   {
	this.tabla = document.getElementById(devCampo);
	this.posMuestra = obtenerPos(devMuestra);
	this.posTabla = false;
	this.tabla.style.top = (this.posMuestra['y'] + 17) + "px";
	this.tabla.style.visibility = 'hidden';
	this.tabla.style.display = 'block';
	this.tabla.style.left = (this.posMuestra['x'] + 17 - this.tabla.offsetWidth) + "px";
	this.tabla.style.visibility = 'visible';
	this.tabla.style.display = 'none';
	var self = this;
	this.mostrar = function(visualizar)
	 {
	  //var vis = self.tabla.style.display;
	  self.tabla.style.display = visualizar ? 'block' : 'none';
	 }
   }
  return tablaRef;
 }

//]]>
</script>




<!-- div class="solapas"><ul><li><a href="/usuarios?id=<?php echo $id ?>">Información de usuario</a></li><li><span>Pedidos</span></li></ul></div -->
<?php
  $no_poromision = true;
  $pedidos_estados = array('Pendiente', 'En proceso', 'Entregado parcialmente', 'Entregado', 'Cancelado');
  $clase_estado = array('sinverificar', 'actual', 'enproceso', '', 'suspendido');

  $orden = empty($_REQUEST["orden"]) ? 1 : $_REQUEST["orden"];
  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("id", "fecha", "estado_id", "valornull ASC, fecha_estado");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));
  if(!$result = $mysqli->query("SELECT id, fecha, estado_id, fecha_estado, fecha_estado IS NULL AS valornull FROM `carrito_pedidos` WHERE usuario_id = {$_GET['id']} ORDER BY ${db_orden}")) echo __LINE__." - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
//		<caption><img src=\"/img/pregunta_inactivo\" onmouseover=\"this.src='/img/pregunta_activo';tablaReferencia(this, 'tablaReferencia').mostrar()\" onmouseout=\"this.src='/img/pregunta_inactivo';document.getElementById('tablaReferencia').style.display='none'\" alt=\"Referencia\" /></caption>
   	echo "
	<table class=\"tabla\">
	 <thead>
	  <tr class=\"orden\">
	   <td{$ordencolor[1]}><a href=\"/usuarios?id=${id}&amp;ia=pedidos&amp;orden={$ord_num[1]}\">{$ord_fl[1]}Código</a></td>
	   <td{$ordencolor[2]}><a href=\"/usuarios?id=${id}&amp;ia=pedidos&amp;orden={$ord_num[2]}\">{$ord_fl[2]}Fecha realizado</a></td>
	   <td{$ordencolor[3]}><a href=\"/usuarios?id=${id}&amp;ia=pedidos&amp;orden={$ord_num[3]}\">{$ord_fl[3]}Estado</a></td>
	   <td{$ordencolor[4]}><a href=\"/usuarios?id=${id}&amp;ia=pedidos&amp;orden={$ord_num[4]}\">{$ord_fl[4]}Actualizado</a></td>
	  </tr>
	 </thead>";
	echo '
	 <tfoot>
	  <tr>
	   <td colspan="4" style="background-color:#fff;text-align:right;"><img src="/img/pregunta_inactivo" onmouseover="this.src=\'/img/pregunta_activo\';tablaReferencia(this, \'tablaReferencia\').mostrar(true)" onmouseout="this.src=\'/img/pregunta_inactivo\';tablaReferencia(this, \'tablaReferencia\').mostrar(false)" alt="Referencia" /></td></tr>
	 </tfoot>
	 <tbody>';
	do
	 {
	  echo "
	  <tr class=\"{$clase_estado[$fila[2]]}\">
	   <td><a href=\"/pedidos?id={$fila[0]}\">".sprintf("%06s", $fila[0])."</a></td>
	   <td>".formato_fecha($fila[1], false)."</td>
	   <td>{$pedidos_estados[$fila[2]]}</td>
	   <td>".($fila[3] ? formato_fecha($fila[3], false) : '')."</td></tr>";
	 }while($fila = $result->fetch_row());
	echo "
	 </tbody>
	</table>";
	echo '
		<table class="tabla" id="tablaReferencia"
		 ><thead
		  ><tr class="orden"
		   ><td>Referencia</td
		  ></tr
		 ></thead
		 ><tbody';
	for($i = 0; $i <= 4; $i++)
	 {
	  echo "
		  ><tr class=\"{$clase_estado[$i]}\"
		   ><td>{$pedidos_estados[$i]}</td></tr";
	 }
	echo '
		 ></tbody
		></table>';
   }
 }
// agregar / editar
elseif($ia == "editar" || !empty($id))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  if(!$mensaje_error || $_POST['ia'] == "modificar")
   {
	if(!empty($_REQUEST['id']))
	 {
	  //SELECT u.`id`, u.`usuario`, UNIX_TIMESTAMP(u.`creado`) AS creado, UNIX_TIMESTAMP(ua.`tiempo`) AS ultimo_login, u.`email`, up.cliente_id IS NOT NULL AS cliente FROM (`usuarios` u LEFT JOIN usuarios_permisos up ON u.id = up.usuario_id AND up.cliente_id IS NOT NULL) LEFT JOIN usuarios_accesos ua ON u.id = ua.usuario_id WHERE u.`id` = '".$_REQUEST['id']."' LIMIT 1
	  //if(!$result = $mysqli->query("SELECT u.`id`, u.`usuario`, u.nombre_mostrar, u.`creado`, ua.`tiempo` AS ultimo_login, u.`email`, up.* FROM (`usuarios` u LEFT JOIN usuarios_permisos up ON u.id = up.usuario_id) LEFT JOIN usuarios_accesos ua ON u.id = ua.usuario_id WHERE u.`id` = '".$_REQUEST['id']."' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
	  //, ud.nombre, ud.apellido, ud.estado, ud.ciudad, ud.direccion, ud.telefono, ud.celular FROM `usuarios` u LEFT JOIN usuarios_datos ud ON u.id = ud.id
	  if(!$result = $mysqli->query("SELECT u.`id`, u.`usuario`, u.nombre_mostrar, u.`creado`, u.`email` FROM `usuarios` u WHERE u.`id` = {$_REQUEST['id']} LIMIT 1")) echo __LINE__." - ".$mysqli->error;
	  else
	   {
?>
<!-- div class="solapas"><ul><li><span>Información de usuario</span></li><li><a href="/usuarios?id=<?php echo $id ?>&amp;ia=pedidos">Pedidos</a></li></ul></div -->
<?php
	  	if($fila = $result->fetch_assoc())
	     {
	      $filaDB = true;
	      $transaccion = "Editar";
	      $p_cliente = $fila['cliente'];
	// <!-- form name=\"edicion\" method=\"post\" action=\"".php_self()."_guardar\" target=\"frguardar\" -->
		  //_guardar
		  echo "
	<div class=\"solapas\"><ul><li><span>Información de usuario</span></li><li><a href=\"/usuarios_archivos?usuario=".$id."\">Archivos</a></li></ul></div>
	<form name=\"edicion\" method=\"post\" action=\"/usuarios?id=${id}\" onsubmit=\"selectAll(this['grupo[]'])\">
	 <input type=\"hidden\" name=\"id\" value=\"".$fila['id']."\" />";
		  $v_ia = "modificar";
	 	  $result->close();
		 }
	   }
	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, `date`, `text`, `int`, `num`, leng_id FROM usuarios_valores WHERE usuario_id = '${id}'")) echo __LINE__." - ".$mysqli->error;
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
  if(!$filaDB)
  //else //if(!$fila || $mensaje_error)
   {
	echo "
	<form name=\"edicion\" method=\"post\" action=\"/usuarios\" onsubmit=\"selectAll(this['grupo[]']);\">";//<!-- _guardar -->
	$v_ia = "agregar";
   }
  echo "
	 <input type=\"hidden\" name=\"ia\" value=\"${v_ia}\" />";

?>

	<table class="tabla">
	 <thead>
	 <tr>
	  <th colspan="2"><?php echo $transaccion; ?> usuario</th></tr>
<?php

  if($mensaje_error)
   {
	echo "
	 <tr>
	  <td colspan=\"2\"><ul>";
	foreach($mensaje_error as $error)
	 { echo "<li>".$error."</li>"; }
	echo "</ul></td></tr>";
   }
  $req = $filaDB ? false : "<span>*</span>";

?>
	 </thead>
	 <tfoot>
	  <!-- tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']; ?>">Regresar</a></div></td></tr -->
	  <tr>
	   <td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/usuarios?de=<?php echo $_REQUEST['de'] ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar" id="guardar" value="Guardar" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for="usuario">Nombre de usuario</label></td>
	   <td><?php

  if(empty($fila['id']))
    echo "<input type=\"text\" name=\"usuario\" id=\"usuario\" value=\"".$fila['usuario']."\" size=\"22\" maxlength=\"22\" />*";
  else
   {
	//$ultimo_login = $fila['ultimo_login'] ? formato_fecha($fila['ultimo_login'], false) : "nunca";
    echo $fila['usuario']."<input type=\"hidden\" name=\"usuario\" id=\"usuario\" value=\"".$fila['usuario']."\" /></td></tr>
	  <tr>
	   <td>Creado</td>
	   <td>".formato_fecha($fila['creado'], false);
	  /*<tr>
	   <td>&Uacute;ltimo login</td>
	   <td>".$ultimo_login;
	  */
   }

?>
</td></tr>

	  <tr>
	   <td><label for="nombre_mostrar">Nombre para mostrar</label></td>
	   <td><input type="text" name="nombre_mostrar" id="nombre_mostrar" value="<?php echo $fila['nombre_mostrar'] ?>" size="22" maxlength="22" /></td></tr>
	  <tr>
	   <td><label for="adclave">Contrase&ntilde;a</label></td>
	   <td><input type="password" name="adclave" id="adclave" size="22" maxlength="30" /><?php echo $req; ?></td></tr>
	   <tr>
	   <td><label for="adclave2">Repita contrase&ntilde;a</label></td>
	   <td><input type="password" name="adclave2" id="adclave2" size="22" maxlength="30" /><?php echo $req; ?></td></tr>
	  <tr>
	   <td><label for="email">E-mail</label></td>
	   <td><input type="text" name="email" id="email" value="<?php echo $fila['email']; ?>" size="22" maxlength="70" />*</td></tr>
<?php

	if(count($atributos_all))
	 {
	  $formcampo = new formCampo2($id);
	  foreach($atributos_all AS $a)
	   {
		$formcampo->id = $a['id'];
		$formcampo->sugerido = $a['sugerido'];
		$formcampo->unico = $a['unico'];
		$formcampo->tipo = $a['tipo'];
		$formcampo->subtipo = $a['subtipo'];
		$formcampo->nombre = $a['nombre'];
		//$formcampo->identificador = $a['identificador'];
		$formcampo->poromision = $a['poromision'];
		$formcampo->string = $a['string'];
		$formcampo->date = $a['date'];
		$formcampo->text = $a['text'];
		$formcampo->int = $a['int'];
		$formcampo->num = $a['num'];
		$formcampo->extra = unserialize($a['extra']);
		$formcampo->superior = $a['superior'];
		//$formcampo->nodo_tipo = $a['nodo_tipo'];
		$formcampo->valores = $valores[$a['id']];// ? $valores[$a['id']] : array($a['tipo'] => $a[$a['tipo']]);
		echo $formcampo->imprimir();
	   }
	  unset($formcampo);
	 }

echo "	 </tbody>
	</table>
	</form>";

 }

/* por omision */
if(!$no_poromision)
 {
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo '
    <div id="opciones"><a href="/usuarios?ia=editar">Agregar usuario</a></div>';
   }
echo '<div><a href="/configuracion?seccion=4">Configuraci&oacute;n de usuarios</a></div>';
  $estado_arr = array("Deshabilitado", "Habilitado");
  $clase_estado = array("inactivo", "");
  $a = "25";
  $de = $_REQUEST["de"];
  if(empty($de))
   {
    $desde = "0";
    $limite_pre = $a;
   }
  else
   {
    $desde = $de;
    $limite_pre = ($de + $a);
   }
  if(empty($_REQUEST["orden"]))
   { $orden = "1"; }
  else
   { $orden = $_REQUEST["orden"]; }

  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("`usuario`", "`nombre_mostrar`", "`email`", "`ultimo_login`");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));

  //echo "SELECT * FROM ver_listado_usuarios WHERE `admin` = '1' ORDER BY $db_orden LIMIT $desde,$a";
  // SELECT * FROM ver_listado_usuarios ORDER BY $db_orden LIMIT $desde,$a
  $filtro_admin = ($_SESSION['usuario'] == 'admin') ? false : "WHERE creado_por = '".$_SESSION['usuario_id']."'";
  //if(!$result = $mysqli->query("SELECT `us`.`id`,`us`.`usuario`, group_concat(`uv`.`string` order by `uv`.`atributo_id` DESC separator ', ') AS `nombre_mostrar`, `us`.`email`, ua.tiempo AS `ultimo_login`, `us`.`admin` FROM (`usuarios` `us` LEFT JOIN `usuarios_valores` `uv` ON(((`us`.`id` = `uv`.`usuario_id`) AND ((uv.`atributo_id` = 1) OR (uv.`atributo_id` = 2))))) LEFT JOIN usuarios_accesos ua ON us.id = ua.usuario_id WHERE `admin` = '0' ${filtro_admin} GROUP BY `us`.`id` ORDER BY ${db_orden} LIMIT ${desde}, ${a}")) echo basename(__FILE__)."[".__LINE__."] - ".$mysqli->error;
  //if(!$result = $mysqli->query("SELECT u.id, u.usuario, u.nombre_mostrar, u.email, MAX(tiempo) FROM usuarios u LEFT JOIN usuarios_accesos ua ON u.id = ua.usuario_id ${filtro_admin} GROUP BY u.id ORDER BY ${db_orden} LIMIT ${desde}, ${a}")) echo basename(__FILE__)."[".__LINE__."] - ".$mysqli->error;
  if(!$result = $mysqli->query("SELECT id, usuario, nombre_mostrar, email FROM `usuarios` WHERE admin = 0 ORDER BY ${db_orden} LIMIT ${desde}, ${a}")) echo basename(__FILE__)."[".__LINE__."] - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
    echo "
	<form action=\"/usuarios\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	<table class=\"tabla\" style=\"width:auto;\"
	 ><thead
	  ><tr class=\"orden\"
	   ><td style=\"width: 20px; text-align: center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
	   ><td".$ordencolor[1]."><a href=\"/usuarios?orden=".$ord_num[1].$sesion2."\">".$ord_fl[1]."Usuario</a></td
	   ><td".$ordencolor[2]."><a href=\"/usuarios?orden=".$ord_num[2].$sesion2."\">".$ord_fl[2]."Nombre</a></td
	   ><td".$ordencolor[3]."><a href=\"/usuarios?orden=".$ord_num[3].$sesion2."\">".$ord_fl[3]."E-mail</a></td>
	   <td style=\"width:20px;\"> </td>
	  </tr
	 ></thead
	 ><tbody";
    do
     {
      echo "
	  ><tr
	   ><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\" onclick=\"selFila(this, 1)\" /></td
	   ><td><a href=\"/usuarios?id=".$fila[0]."\">".$fila[1]."</a></td
	   ><td>".$fila[2]."</td
	   ><td><a href=\"mailto:${fila[3]}\">".$fila[3]."</a></td>
		<td style=\"text-lign:center;\"><a href=\"/usuarios_archivos?usuario={$fila[0]}\"><img src=\"/img/silk/folder\" alt=\"Archivos\" style=\"border:0;\" /></a></td>
	  </tr";
     } while($fila = $result->fetch_row());
    $result->close();
    echo "
	 ></tbody
	></table>
	<div id=\"error_check_form\" class=\"div_error\" style=\"display: none;\">No ha seleccionado ningun administrador.</div>
	<div id=\"listado_opciones\" style=\"padding: 4px;\"><img src=\"./img/flecha_arr_der.png\" alt=\"Para los items seleccionados\" style=\"padding: 0pt 5px;\" /><input type=\"submit\" name=\"mult_submit\" value=\"Eliminar\" onclick=\"return confBorrado('lista_item[]');\" />";
	if($_SESSION['permisos'][$seccion_id] >= 4) echo " <input type=\"submit\" name=\"mult_submit\" value=\"Ascender a usuario-cliente\" />";
	echo "</div>
	</form>";
	if(count($clase_filas))
	 {
	  echo "
	<script type=\"text/javascript\">
	 var celdaClases = new Array();";
	  for($cf = 0; $cf < count($clase_filas); $cf++)
	   { echo "\r\t celdaClases[".$cf."] = '".$clase_filas[$cf]."';"; }
	  echo "
	</script>";
	 }
    if(!$total = $mysqli->query("SELECT id FROM `usuarios` ${filtro_admin}")) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
    else
     {
      if ($total->num_rows < $limite_pre) $limit = $total->num_rows;
      else $limit = $limite_pre;
      $paginas = ceil ($total->num_rows / $a);
      $ante = ($limit - ($desde + 1));
      if ($desde == 0) $b = "1";
      else $b = ($limit / $a);
      $c = ceil ($b);
      echo "
	 <div id=\"listado_result\">Resultados <b>".($desde + 1)."</b> - <b>".$limit."</b> de <b>".$total->num_rows."</b><br />P&aacute;gina <b>".$c."</b> de <b>".ceil ($paginas)."</b><br />";
      if (($desde + 1) > 1)
	    echo "<a href=\"/usuarios?de=".($desde - $a)."\">&lt;&lt; Anterior</a>&nbsp;-&nbsp;";

      if($paginas > 1)
       {
        if($paginas > 20 && $c > 11)
         { $i = ($c - 9); }
        else
         { $i = 1; }
        $ib = $a;
        $ipg = ($c + 9);
        if($ipg > $paginas)
         { $ipg = $paginas; }
        for($i;$i<=$ipg;$i++)
         {
          $ia = (($i - 1) * $a);
          if($i <> $c)
           { echo " <a href=\"/usuarios?de=".$ia."\">".$i."</a> "; }
          else
           { echo " <b>".$i."</b> "; }
         }
       }
      if ($total->num_rows > $limit)
       { echo "&nbsp;-&nbsp;<a href=\"/usuarios?de=".($desde + $a)."');\">Siguiente &gt;&gt;</a>"; }
      echo "</div>";
      $total->close();
     }
   }
  else
   { echo "<div class=\"div_alerta\">No se encontraron usuarios en la base de datos.</div>"; }
 }

include('inc/iapie.php');

?>