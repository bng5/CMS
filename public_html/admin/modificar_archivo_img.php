<?php

header('Content-type: text/plain');
if($_GET['attr'] && $_GET['img'] && $_GET['archivo'])
 {
  require('inc/iniciar.php');
  $mysqli = BaseDatos::Conectar();

  $cons_as = $mysqli->query("SELECT id FROM imagenes_a_atributos WHERE imagen_id = {$_GET['img']} AND atributo_id = {$_GET['attr']}");
  if($fila_as = $cons_as->fetch_row())
   {
	echo "1";
	exit;
   }

  $carpeta_imagenes_raiz = RUTA_CARPETA."public_html/img/";
  $carpeta_imagenes = array("0/", "1/");

  $cons_campo = $mysqli->query("SELECT extra FROM items_atributos WHERE id = {$_GET['attr']}");
  if($fila_campo = $cons_campo->fetch_row())
   {
	if($fila_campo[0]) eval('$extra = '.$fila_campo[0].';');
   }

  $imagen = new Imagen(RUTA_CARPETA.'img/'.$_GET['archivo']);
  if(!$imagen->dato('errorno'))
   {
	foreach($extra AS $i => $metodos)
	 {
	  $imagen->$metodos[0]($metodos[1], $metodos[2], $metodos[4], $metodos[5]);
	  if(is_array($metodos[3])) $imagen->marcaDeAgua('../img/5/'.$metodos[3][0], $metodos[3][1], $metodos[3][2]);
	  $n_imagen_ruta = $imagen->guardar($carpeta_imagenes_raiz.$carpeta_imagenes[$i].$_GET['attr'], $_GET['archivo']);
	  $ancho[] = $imagen->dato('ancho');
	  $alto[] = $imagen->dato('alto');
	  $peso[] = filesize($n_imagen_ruta);
	 }
	$mysqli->query("INSERT INTO `imagenes_a_atributos` (`imagen_id`, `atributo_id`, `ancho`, `alto`, `peso`, `ancho_m`, `alto_m`, `peso_m`) VALUES ({$_GET['img']}, {$_GET['attr']}, '{$ancho[0]}', '{$alto[0]}', '{$peso[0]}', '{$ancho[1]}', '{$alto[1]}', '{$peso[1]}')");
	echo "1";
   }
 }

?>