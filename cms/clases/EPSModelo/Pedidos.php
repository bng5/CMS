<?php

class EPSModelo_Pedidos
 {
  function getPorId($id)
   {
	$id += 0;
	$DB = DB::Conectar();
	$consulta = $DB->query("SELECT id, usuario_id, fecha, estado_id, fecha_estado FROM carrito_pedidos WHERE id = ".$id." LIMIT 1");
	$consulta->setFetchMode(DB::FETCH_CLASS, 'EPS_Pedido');
	return $consulta->fetch();
   }
 }

?>