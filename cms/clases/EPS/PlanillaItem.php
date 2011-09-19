<?php

class EPS_PlanillaItem
 {
  public $id, $tipo, $marca, $modelo, $insumo, $rendimiento, $precio_reman, $precio_nuevo;
  public $estado, $cantidad;
  // tipo
  //   1 Tinta
  //   2 Toner

  function set_parseRendimiento($valor)
   {
	$valor = (int) str_replace('.', '', $valor);
	$this->rendimiento = $valor ? $valor : null;
   }

  function set_parsePrecio($attr, $valor)
   {
	preg_match('/(\d+)(,(\d+))*/', $valor, $match);
	$this->$attr = $match[1] + eval('return 0.'.$match[3].';');
   }

  function getPrecioNuevo()
   {
	return number_format($this->precio_nuevo, 2, ',', '');
   }
  function getPrecioReman()
   {
	return number_format($this->precio_reman, 2, ',', '');
   }
  function getRendimiento()
   {
	return number_format($this->rendimiento, 0, '', '.');
   }
  function getRendimientoUn()
   {
	return $this->tipo == 1 ? 'ml' : 'págs.';
   }
  function getTipo()
   {
	return $this->tipo == 1 ? 'Tinta' : 'Tóner';
   }

  function getTipoIdentif()
   {
	return $this->tipo == 1 ? 'tinta' : 'toner';
   }

  function getRendimientoUnIdentif()
   {
	return $this->tipo == 1 ? 'ml' : 'pags';
   }
 }

?>