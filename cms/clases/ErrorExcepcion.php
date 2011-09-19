<?php

class ErrorExcepcion extends ErrorException
 {
  private $tiempo;
  public function __construct($message = "", $code = 0, $severity = 0, $filename = "", $lineno = 0)
   {
    parent::__construct($message, $code, $severity, $filename, $lineno);
	$this->tiempo = date("r");
	$registro = new SplFileObject(RUTA_CARPETA.'registro/error-excepcion.log', 'a');
	$registro->fwrite(var_export($this, true)."\n------------------------------------------------------------------------\n\n");
	unset($registro);
   }

  function __toString()
   {
	return $this->message;
   }
 }

?>