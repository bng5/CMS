<?php

class EPS_Planilla
 {
  public $id, $fecha_agregado, $fecha_modificado, $titulo;

  function getIterator()
   {
	return $this->stmt;
   }
 }

?>