<?php

$titulo = "Pedidos";
$seccion = "pedidos";
//$seccion_id = 4;

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$id = $_REQUEST['id'];
$ia = $_REQUEST['ia'];

$mysqli = BaseDatos::Conectar();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

if($id)
 {
  if($_POST['estado'] && $_POST['id'])
   {
   	$mysqli->query("UPDATE `carrito_pedidos` SET estado_id = {$_POST['estado']} WHERE id = {$_POST['id']} LIMIT 1");
    $div_mensaje = ($mysqli->affected_rows == 1) ? "El estado del pedido ha sido modificado." : "No se ha realizado ningún cambio.";
   }
  include('inc/iaencab.php');
  $pedidos_estados = array('Pendiente', 'En proceso', 'Entregado parcialmente', 'Entregado', 'Cancelado');


  if(!$result = $mysqli->query("SELECT u.id, u.usuario, u.nombre_mostrar, cp.fecha, cp.estado_id, cp.fecha_estado FROM `carrito_pedidos` cp, `usuarios` u WHERE cp.id = ${id} AND cp.usuario_id = u.id")) echo __LINE__." - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
	$fecha = $fila[3];
	//echo "<pre>".htmlspecialchars(var_export($referer, true))."</pre>";
	if($_POST['referer'])
	 {
	  $referer = urldecode($_POST['referer']);
	  $referer_enc = urlencode($referer);
	 }
	elseif($referer_http = parse_url($_SERVER['HTTP_REFERER']))
	 {
	  if($referer_http['path'] == '/usuarios')
	   {
		parse_str($referer_http['query'], $ref_query);
		unset($ref_query['id']);
		$referer = http_build_query($ref_query, '', '&amp;');
		$referer_enc = urlencode($referer);
	   }
	  else
	   {
	   	$referer = 'ia=pedidos';
	   }
	 }

   	$sel_estado[$fila[4]] = ' selected="selected"';
   	echo "
<a href=\"/usuarios?id={$fila[0]}&amp;${referer}\">Regresar</a>
<form action=\"/pedidos?id=${id}\" method=\"post\">
<input type=\"hidden\" name=\"referer\" value=\"${referer_enc}\" />
<input type=\"hidden\" name=\"id\" value=\"${id}\" />
<fieldset class=\"listaatrib\">
 <legend>Pedido ".sprintf("%06s", $id)."</legend>
 <ul>
  <li><label>Usuario:</label> <a href=\"usuarios?id={$fila[0]}\">{$fila[2]} ({$fila[1]})</a></li>
  <li><label>Iniciado:</label> ".formato_fecha($fila[3], false, true)."</li>
  <li><label>Actualizado:</label> ".formato_fecha($fila[5], false, true)."</li>
  <li><label>Cambiar estado:</label> <select name=\"estado\"><option value=\"0\"{$sel_estado[0]}>Pendiente</option><option value=\"1\"{$sel_estado[1]}>En proceso</option><option value=\"2\"{$sel_estado[2]}>Entregado parcialmente</option><option value=\"3\"{$sel_estado[3]}>Entregado</option><option value=\"4\"{$sel_estado[4]}>Cancelado</option></select></li>
  <li><input type=\"submit\" value=\"Aceptar\" /></li>
 </ul>
</fieldset>
</form>";
   }





  if(!$result = $mysqli->query("SELECT DISTINCT i.seccion_id FROM `carrito_pedidos_items` cpi LEFT JOIN items i ON cpi.item_id = i.id WHERE cpi.id = ${id}")) echo __LINE__." - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
	$total = 0;

	echo "
	<table class=\"tabla\">
	 <thead>
	  <tr>
	   <td>Producto</td>
	   <td>Precio</td>
	   <td>Cantidad</td>
	   <td>Sub-total</td></tr>
	 </thead>
	 <tbody>";

	do
	 {
	 $seccion = $fila[0];

	$attrs_lista = array();
	if(!$consulta_attrs = $mysqli->query("SELECT isaa.atributo_id, ia.tipo_id, ian.atributo FROM items_secciones_a_atributos isaa, items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1 WHERE isaa.atributo_id = ia.id AND isaa.seccion_id = '{$fila[0]}' AND (ia.tipo_id = 1 OR ia.tipo_id = 21 OR ia.tipo_id = 16) ORDER BY orden")) die(__LINE__."<br />\n".$mysqli->error);
	if($fila_attrs = $consulta_attrs->fetch_row())
	 {
	  $cons_campos = '';
	  $abre_parts = '';
	  $i = 1;
	  $cpi_texto = false;
	  $cpi_precio = false;
	  do
	   {
		$tipo = $fila_attrs[1];
		if($attrs_lista[$tipo]) continue;
		$attrs_lista[$tipo] = array($fila_attrs[0], $fila_attrs[2]);
		if($tipo == 1 || $tipo == 21)
		 {
		  if($cpi_texto) continue;
		  $cpi_texto = $fila_attrs[0];
		  $cons_campos .= ", iv${i}.`string`";
		  $abre_parts .= "(";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]})";
		 }
		else
		 {
		  if($cpi_precio) continue;
		  $cpi_precio = $fila_attrs[0];
		  $cons_campos .= ", im.archivo";
		  $abre_parts .= "((";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]}) LEFT JOIN imagenes_orig im ON iv${i}.`int` = im.id)";
		 }
		$i++;
		if($cpi_texto && $cpi_precio) break;
		if(count($attrs_lista) == 2) break;
	   }while($fila_attrs = $consulta_attrs->fetch_row());
	 }

  if(!$result = $mysqli->query("SELECT cpi.item_id, cpi.cantidad, iv.`string`, ph.precio FROM ((`carrito_pedidos_items` cpi LEFT JOIN items_valores iv ON cpi.item_id = iv.item_id AND iv.atributo_id = ${cpi_texto}) LEFT JOIN precios_historial ph ON cpi.item_id = ph.item_id AND ph.atributo_id = ${cpi_precio} AND ph.fecha = (SELECT MAX(fecha) FROM precios_historial WHERE fecha < '${fecha}' AND item_id = cpi.item_id)), items i WHERE cpi.item_id = i.id AND i.seccion_id = ${seccion} AND cpi.id = ${id} ORDER BY cpi.item_id ASC")) echo __LINE__." - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
	do
	 {
	  $subtotal = ($fila[3] * $fila[1]);
	  $total += $subtotal;
	  echo "
	  <tr>
	   <td><a onclick=\"return abrirPop({$fila[0]})\" href=\"/ver_item?id={$fila[0]}\">{$fila[2]}</a></td>
	   <td style=\"text-align:right;\">{$fila[3]}</td>
	   <td style=\"text-align:right;\">{$fila[1]}</td>
	   <td style=\"text-align:right;\">".number_format($subtotal, 2, '.', '')."</td></tr>";
	 }while($fila = $result->fetch_row());

   }



	 }while($fila = $result->fetch_row());
	echo "
	  <tr>
	   <td colspan=\"3\" style=\"background-color:transparent;text-align:right;\">Total: </td>
	   <td style=\"text-align:right;\">".number_format($total, 2, '.', '')."</td></tr>
	 </tbody>
	</table>";
   }












 }
else
 {

 }

include('inc/iapie.php');

?>