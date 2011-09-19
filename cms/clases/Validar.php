<?php

class Validar
 {
  function __construct($forma, $leng = false)
   {
	$this->atributos = $forma;
	$this->leng = $leng;
   }

  function Atributos($arr)
   {
   	$ret = array();
	foreach($this->atributos AS $k => $v)
	 {
	  if($arr[$k] || $v['req'])
	   {
	   	if($v['tipo'] == 'texto_ml' && $this->leng)
		 {
		  $ret[$k] = $arr[$k][$this->leng] ? $arr[$k][$this->leng] : (current($arr[$k]) ? current($arr[$k]) : 'Sin nombre');
		  settype($ret[$k], $v['interno']['type']);
		 }
	   	else
		 {
		  $ret[$k] = $arr[$k];
		  settype($ret[$k], $v['type']);
		 }
	   }
	 }
	return $ret;
   }

/*
  function __set($attr, $valor)
   {
   	if(isset($this->$attr))
   	 {
   	  echo "si";

   	 }
   	else
   	 {
   	  echo "no";
   	 }
	echo "\n\n";
   }
*/
 }

?>
