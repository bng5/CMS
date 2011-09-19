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
  $pedido = Modelo_Pedidos::getPorId($id);
var_dump($id, $pedido);
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

	if($pedidos->paginas > 1)
	 {
	  $paginado = new Vista_Admin_Paginado('/pedidos?'.($usuario ? 'usuario='.$bsq['usuario_id'].'&amp;' : '').'pagina=', $pagina, $pedidos->paginas);
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

?>