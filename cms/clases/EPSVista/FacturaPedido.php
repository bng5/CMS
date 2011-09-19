<?php

class EPSVista_FacturaPedido
 {
  private $cabecera, $pie;
  private $estados = array("Re Manufacturado", "Original");
  function __construct()
   {
	$this->i = 0;
	$this->cabecera = false;
	$this->pie = false;
	$this->total = 0;
	$this->iva = 0.16;
   }
  function __destruct()
   {
	if($this->cabecera && !$this->pie)
	  $this->_pie();
   }
  function _cabecera()
   {
	$this->cabecera = true;
    echo '<table>
 <thead>
  <tr>
   <th title="Cantidad">Cant.</th>
   <th>Descripción</th>
   <th title="Precio unitario">Precio u.</th>
   <th>Precio total</th>
  </tr>
 </thead>
 <tbody class="items">';
   }

  function _pie()
   {
	$this->pie = true;
	$impuesto = ($this->total * $this->iva);
	echo '
 </tbody>
 <tbody class="totales">
  <tr>
   <td colspan="3">Sub-Total</td>
   <td class="der">'.number_format($this->total, 2, ',', '.').'</td>
  </tr>
  <tr>
   <td colspan="3" >I.V.A.</td>
   <td class="der">'.number_format($impuesto, 2, ',', '.').'</td>
  </tr>
  <tr class="fact_total">
   <td colspan="3" id="tot_label"><b>Total €</b></td>
   <td class="der">'.number_format($this->total + $impuesto, 2, ',', '.').'</td>
  </tr>
 </tbody>
</table>';
   }

  function fila($item, $estado, $cantidad)
   {
	if(!$this->cabecera)
	  $this->_cabecera();
	if($estado == 0)
	 {
	  $precio = $item->precio_reman;
	  $precio_formato = $item->getPrecioReman();
	 }
	else
	 {
	  $precio = $item->precio_nuevo;
	  $precio_formato = $item->getPrecioNuevo();
	 }
	$subtotal = ($precio * $cantidad);
	$this->total += $subtotal;
	$this->i++;
    echo "
  <tr".(($this->i % 2) ? '' : ' class="par"').">
   <td>".$cantidad."</td>
   <td class=\"desc\">".$item->getTipo()." ".$this->estados[$estado]." ".$item->marca." ".$item->modelo." ".$item->insumo."</td>
   <td>".$precio_formato."</td>
   <td>".number_format($subtotal, 2, ',', '.')."</td>
  </tr>";
   }
 }

?>