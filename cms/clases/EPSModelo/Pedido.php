<?php

class EPSModelo_Pedido
 {
  function __construct($id = false)
   {
	$this->DB = DB::Conectar();
	if(!$id)
	 $id = $this->_nuevo();
	$this->id = $id;
   }

  private function _nuevo()
   {
    $this->DB->exec("INSERT INTO carrito_pedidos (`usuario_id`, `fecha`) VALUES ({$_SESSION['usuario_id']}, now())");
	return $this->DB->lastInsertId();
   }

  function agregar($item_id, $tipo, $cantidad)
   {
	$this->DB->exec("INSERT INTO eps__carrito_pedidos_items (`id`, `item_id`, `tipo`, `cantidad`) VALUES (".$this->id.", ".$item_id.", ".$tipo.", ".$cantidad.")");
   }
  function getPorId()
   {
	$pedido->id = 1;
   }
 }

?>