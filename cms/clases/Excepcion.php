<?php

class Excepcion extends Exception
 {
  private $previous;
  public function __construct($message = "", $code = 0, Exception $previous = null)
   {
    parent::__construct($message, $code);
	$this->previous = $previous;
	$registro = new SplFileObject(RUTA_CARPETA.'registro/excepcion.log', 'a');
	$registro->fwrite(var_export($previous, true)."\n------------------------------------------------------------------------\n\n");
	unset($registro);
   }
  final public function getPrevious()
   {
	return $this->previous;
   }
  function __toString()
   {
	return $this->message;
   }
 }

?>