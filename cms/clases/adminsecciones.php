<?php

class adminsecciones
 {
  public function __construct()
   {
	global $mysqli, $seccion_id;
	$this->seccion = $seccion_id ? $seccion_id : false;
	$this->separador_niv = "  ";
	$this->secciones = array();
	$this->superior = array();
	$this->actual_superior = array();
	$tbsubcat = $mysqli->query("SELECT id, nombre, superior_id, link, link_param FROM `admin_secciones` ORDER BY `superior_id`, `orden`"); // or die(mysql_error());
	if($row_subcat = $tbsubcat->fetch_assoc())
	 {
	  do
	   {
	    $this->superior[$row_subcat['id']] = $row_subcat['superior_id'];
		$this->secciones[$row_subcat['superior_id']][$row_subcat['id']] = $row_subcat;
	   }while($row_subcat = $tbsubcat->fetch_assoc());
	 }
	if($this->seccion) $this->armado($this->seccion);
	//$this->imprimir(current($this->secciones));
   }
  private function armado($actual)
   {
    array_unshift($this->actual_superior, $actual);
   	do
   	 {
   	  $actual = $this->superior[$actual];
	  array_unshift($this->actual_superior, $actual);
	 }
	while($this->superior[$actual] > 0);
   }
  public function rearmado($seccion)
   {
	array_splice($this->actual_superior, 0);
	$this->armado($seccion);
   }
  public function imprimir($subseccion = false, $nivel = 0)
   {
    if($subseccion == false) $subseccion = current($this->secciones);
	echo "\n".str_repeat($this->separador_niv, $nivel)."><ul";
	foreach($subseccion AS $a)
	 {
	  if(!array_key_exists($a['id'], $_SESSION['permisos']) && $nivel == 0) continue;
	  $link = $a['link'];
	  $link .= $a['link_param'] ? "?".$a['link_param'] : "";
	  echo "\n".str_repeat($this->separador_niv, $nivel)."><li><a href=\"/".$link."\"";
	  if(current($this->actual_superior) == $a['id'] || $this->seccion == $a['id'])
	   {
	    $this->titulo = $a['nombre'];
	    echo " class=\"activo\">{$a['nombre']}</a";
	    if(is_array($this->secciones[$a['id']]))
	     {
	      array_shift($this->actual_superior);
		  $this->imprimir($this->secciones[$a['id']], ++$nivel);
		  $nivel--;
		 }
	   }
	  else echo ">{$a['nombre']}</a";
	  echo "></li";
	 }
	echo "\n".str_repeat($this->separador_niv, $nivel)."></ul\n";
   }
  public function __toString()
   {
    return $this->titulo ? $this->titulo : "Título";
   }
 }

?>