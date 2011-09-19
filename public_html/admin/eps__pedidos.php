<?php

require('inc/iniciar.php');

$seccion_identificador = "__pedidos";
$seccionObj = Modelo_Secciones::getPorIdentificador($seccion_identificador);

require('inc/ad_sesiones.php');

$id = $_REQUEST['id'];
$ia = $_REQUEST['ia'];
$pagina = is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

$vista = new Vista_XHTML($seccionObj);
?>

<link rel="stylesheet" type="text/css" media="all" href="http://epseurope2009.com/css/eps/confirmar_pedido.css" />

<?php
include('./vistas/iaencab.php');

if($id)
 {
  if($_POST['estado'] && $_POST['id'])
   {
	$db = DB::instancia();
   	$consulta = $db->prepare("UPDATE `carrito_pedidos` SET estado_id = :estado WHERE id = :id LIMIT 1");
	$consulta->bindValue(':estado', $_POST['estado'], DB::PARAM_INT);
	$consulta->bindValue(':id', $_POST['id'], DB::PARAM_INT);
	$consulta->execute();
    $div_mensaje = $consulta->rowCount() ? "El estado del pedido ha sido modificado." : "No se ha realizado ningún cambio.";
   }
  $pedido = EPSModelo_Pedidos::getPorId($id);
  $usuario = $pedido->getUsuario();
  echo '
  <form action="/pedidos?id='.$pedido->id.'" method="post">
   <input type="hidden" name="id" value="'.$pedido->id.'" />
   <ul>
    <li><label>Referencia: </label><span><b>'.$pedido->getId().'</b></span></li>
    <li><label>Usuario: </label><a href="/usuarios?id='.$usuario->id.'">'.$usuario->nombre_mostrar.' ('.$usuario->usuario.')</a></li>
    <li><label>Fecha: </label><span>'.$pedido->getCreado().'</span></li>
	<li><label>Estado: </label><span><select name="estado">';
	foreach($pedido->estados AS $k => $v)
	  echo '<option value="'.$k.'" '.($pedido->estado_id == $k ? 'selected="selected"' : '').'>'.$v.'</option>';
    echo '</select></span> <input type="submit" value="Cambiar" /></li>
	<li><label>Fecha modificado: </label><span>'.$pedido->getModificado().'</span></li>
   </ul>
  </form>';
  if($pedidoIt = $pedido->getIterator())
   {
	$tabla = new EPSVista_FacturaPedido;
	foreach($pedidoIt AS $item)
	 {
	  $tabla->fila($item, $item->estado, $item->cantidad);
	 }
	unset($tabla);
   }
 }
else
 {
  /**
   * Listado
   */
  $bsq = array();
  if($_GET['usuario'])
   {
    $bsq['usuario_id'] = $_GET['usuario'];
	echo '
	<div class="solapas"><ul><li><a href="/usuarios?id='.$_GET['usuario'].'">Información de usuario</a></li><li><span>Pedidos</span></li></ul></div>';
   }

  $pedidos = Modelo_Pedidos::getListado($bsq, $pagina);
  if($pedidosIt = $pedidos->getIterator())
   {
?>
  	<table class="tabla" style="width:auto;">
	 <thead>
	  <tr class="orden">
	   <td class="sel"><img src="img/fld2d7dd_ab" id="fl1" style="border:0;" width="11" height="14" class="fl" alt="Orden ascendente" />&nbsp;Pedido</td>
	   <td>Usuario</td>
	   <td>Fecha creado</td>
	   <td>Estado</td>
	   <td>Fecha modificado</td>
	  </tr>
	 </thead>
	 <!-- tfoot>
	  <tr>
	   <td colspan="4" style="background-color:#fff;text-align:right;"><img src="/img/pregunta_inactivo" onmouseover="this.src='/img/pregunta_activo';tablaReferencia(this, 'tablaReferencia').mostrar(true)" onmouseout="this.src='/img/pregunta_inactivo';tablaReferencia(this, 'tablaReferencia').mostrar(false)" alt="Referencia" /></td></tr>
	 </tfoot -->
	 <tbody>

<?php

    $estados = array("sinverificar", "actual", "enproceso", "", "suspendido");

    foreach($pedidosIt AS $pedido)
     {
	  $usuario = $pedido->getUsuario();
      echo '
	  <tr class="'.$estados[$pedido->estado_id].'">
	   <td><a href="/pedidos?id='.$pedido->id.'">'.$pedido->getId().'</a></td>
	   <td><a href="/usuarios?id='.$usuario->id.'">'.$usuario->nombre_mostrar.' ('.$usuario->usuario.')</a></td>
	   <td>'.$pedido->getCreado().'</td>
	   <td>'.$pedido->getEstado().'</td>
	   <td>'.$pedido->getModificado().'</td>
	  </tr>';
     }

?>

	 </tbody>
	</table>

<?php

	if($pedidos->paginas > 1) {
		//$paginado = new Vista_Admin_Paginado('/pedidos?'.($usuario ? 'usuario='.$usuario->id.'&amp;' : '').'pagina=',
		$paginado = new Vista_Admin_Paginado('/pedidos?'.($bsq['usuario_id'] ? 'usuario='.$bsq['usuario_id'].'&amp;' : '').'pagina=',
				$pagina,
				$pedidos->paginas);
		echo '<div>'.$paginado->mostrar().'</div>';
	}

?>

	<!-- table class="tabla" id="tablaReferencia">
	 <thead>
	  <tr class="orden">
	   <td>Referencia</td>
	  </tr>
	 </thead>
	 <tbody>
	  <tr class="sinverificar">
	   <td>Pendiente</td></tr>
	  <tr class="actual">
	   <td>En proceso</td></tr>
	  <tr class="enproceso">
	   <td>Entregado parcialmente</td></tr>
	  <tr class="">
	   <td>Entregado</td></tr>
	  <tr class="suspendido">
	   <td>Cancelado</td></tr>
	 </tbody>
	</table -->

<?php
   }
 }

unset($vista);
exit;




















$vista = new Vista_AdminXHTML();
$vista->seccion = $seccionObj;



































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
	//if(!$consulta_attrs = $mysqli->query("SELECT isaa.atributo_id, ia.tipo_id, ian.atributo FROM items_secciones_a_atributos isaa, items_atributos ia JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = 1 WHERE isaa.atributo_id = ia.id AND isaa.seccion_id = '{$fila[0]}' AND (ia.tipo_id = 1 OR ia.tipo_id = 21 OR ia.tipo_id = 16) ORDER BY orden")) die(__LINE__."<br />\n".$mysqli->error);
	//if($fila_attrs = $consulta_attrs->fetch_row())
	if(false)
	 {
	  $cons_campos = '';
	  $abre_parts = '';
	  $i = 1;
	  $cpi_texto = false;
	  $cpi_precio = false;
	  do
	   {
		$tipo = $fila_attrs[1];
		if($attrs_lista[$tipo])
		  continue;
		$attrs_lista[$tipo] = array($fila_attrs[0], $fila_attrs[2]);
		if($tipo == 1 || $tipo == 21)
		 {
		  if($cpi_texto)
		    continue;
		  $cpi_texto = $fila_attrs[0];
		  $cons_campos .= ", iv${i}.`string`";
		  $abre_parts .= "(";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]})";
		 }
		else
		 {
		  if($cpi_precio)
		    continue;
		  $cpi_precio = $fila_attrs[0];
		  $cons_campos .= ", im.archivo";
		  $abre_parts .= "((";
		  $cons_tablas .= " LEFT JOIN items_valores iv${i} ON i.id = iv${i}.item_id AND iv${i}.atributo_id = {$fila_attrs[0]}) LEFT JOIN imagenes_orig im ON iv${i}.`int` = im.id)";
		 }
		$i++;
		if($cpi_texto && $cpi_precio)
		  break;
		if(count($attrs_lista) == 2)
		  break;
	   }while($fila_attrs = $consulta_attrs->fetch_row());
	 }

  echo "SELECT cpi.item_id, cpi.cantidad, epi.tipo, epi.marca, epi.modelo, epi.insumo, epi.rendimiento, epi.precio_reman, epi.precio_nuevo FROM `carrito_pedidos_items` cpi JOIN eps__planillas_items epi ON cpi.item_id = epi.id WHERE cpi.id = ${id} ORDER BY cpi.item_id ASC
	  ";
  if(!$result = $mysqli->query("SELECT cpi.item_id, cpi.cantidad, epi.tipo, epi.marca, epi.modelo, epi.insumo, epi.rendimiento, epi.precio_reman, epi.precio_nuevo FROM `carrito_pedidos_items` cpi JOIN eps__planillas_items epi ON cpi.item_id = epi.id WHERE cpi.id = ${id} ORDER BY cpi.item_id ASC")) echo __LINE__." - ".$mysqli->error;
  if($fila = $result->fetch_row())
  //if(false)
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
echo "mm";
 }

include('inc/iapie.php');

?>