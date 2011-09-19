<?php

class EPS_Pedido extends Pedido
 {
  function getIterator()
   {
	$id = $this->id + 0;
	$db = DB::Conectar();
	$consulta = $db->query("SELECT epi.id, epi.tipo, epi.marca, epi.modelo, epi.insumo, epi.rendimiento, epi.precio_reman, epi.precio_nuevo, ecpi.tipo AS estado, ecpi.cantidad FROM `eps__carrito_pedidos_items` ecpi JOIN eps__planillas_items epi ON ecpi.item_id = epi.id WHERE ecpi.id = ".$id);
	$consulta->setFetchMode(DB::FETCH_CLASS, 'EPS_PlanillaItem');
	return $consulta;
   }
 }

?>