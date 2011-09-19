<?php

class Item
 {
  private $atributos = array();
  public $valores = array();
  function __construct($seccion_id)
   {
	$this->atributos = Seccion_Items::Atributos();
   }

  function cargaValores($id)
   {

   }
 }
?>
