<?php

header('Content-type: text/plain');
if($_GET['atributo'] && isset($_GET['desde']))
 {
  require('inc/iniciar.php');
  $mysqli = BaseDatos::Conectar();
  $id = $_GET['atributo'];
  $carpeta_imagenes_raiz = RUTA_CARPETA."public_html/img/";
  $carpeta_originales = RUTA_CARPETA."img/";
  $carpeta_imagenes = array("0/", "1/");
  $cons_campo = $mysqli->query("SELECT extra, tipo_id FROM items_atributos WHERE id = {$_GET['atributo']}");
  if($fila_campo = $cons_campo->fetch_row())
   {
   	$tipo_id = $fila_campo[1];
/*
if($tipo_id != 10)
 {
  echo "Esta funcionalidad se encuentra momentaneamente no disponible.";
  exit;
 }
*/
	if($fila_campo[0])
	  $extra = unserialize($fila_campo[0]);
   }
  echo "var respuestaM = {atributo : {$_GET['atributo']},";
  $desde = $_GET['desde'] ? $_GET['desde'] : 0;
  $hechos = $desde;
  if($desde == 0)
   {
	function SureRemoveDir($dir, $DeleteMe = false)
	 {
	  if(!$dh = @opendir($dir))
	   {
	   	mkdir($dir);
	   	return;
	   }
	  while (false !== ($obj = readdir($dh)))
	   {
		if($obj=='.' || $obj=='..') continue;
		if(!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, $DeleteMe);
	   }
	  closedir($dh);
	  if($DeleteMe)
		rmdir($dir);
	 }
	SureRemoveDir($carpeta_imagenes_raiz.'0/'.$id);
	SureRemoveDir($carpeta_imagenes_raiz.'1/'.$id);
	$mysqli->query("DELETE FROM `imagenes_a_atributos` WHERE atributo_id = {$id}");
/*
	if(!$cons_total = $mysqli->query("(SELECT 'it', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN items_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']}) UNION ( SELECT 'sb', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN subitems_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']}) UNION (SELECT 'sc', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN secciones_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']})")) die (__LINE__.": ".$mysqli->error);
	if($filas = $cons_total->fetch_row())
	 {
	  $total = 0;
	  do
	   {
	  	$fila[] = $filas;
	  	$total += $filas[1];
	   }while($filas = $cons_total->fetch_row());
	 }
	//if(!$cons_total = $mysqli->query("SELECT id FROM imagenes_a_atributos WHERE atributo_id = {$_GET['atributo']}")) die (__LINE__.": ".$mysqli->error);
	//if(!$total = $cons_total->num_rows)
	else
	 {
	  echo "total : 0, porc : 100, errores : false, hechos : 0}";
	  exit;
	 }
*/
   }
/*
  else
   {
	$total = $_GET['total'];
   }
*/
/*********************************************************************/
	$cons_str = ($tipo_id == 10) ? "(SELECT 'items', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN items_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']}) UNION ( SELECT 'subitems', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN subitems_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']}) UNION (SELECT 'secciones', count( DISTINCT gi.imagen_id ) FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN secciones_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id = {$_GET['atributo']})" : "(SELECT 'items', count(DISTINCT io.archivo) FROM `items_valores` iv JOIN imagenes_orig io ON iv.`int` = io.id WHERE atributo_id = {$_GET['atributo']}) UNION (SELECT 'secciones', count(DISTINCT io.archivo) FROM `secciones_valores` iv JOIN imagenes_orig io ON iv.`int` = io.id WHERE atributo_id = {$_GET['atributo']}) UNION (SELECT 'categorias', count(DISTINCT io.archivo) FROM `categorias_valores` iv JOIN imagenes_orig io ON iv.`int` = io.id WHERE atributo_id = {$_GET['atributo']}) UNION (SELECT 'subitems', count(DISTINCT io.archivo) FROM `subitems_valores` iv JOIN imagenes_orig io ON iv.`int` = io.id WHERE atributo_id = {$_GET['atributo']})";
	if(!$cons_total = $mysqli->query($cons_str)) die (__LINE__.": ".$mysqli->error);
	if($fila = $cons_total->fetch_row())
	 {
	  $total = 0;
	  do
	   {
	  	$filas[] = $fila;
	  	$total += $fila[1];
	   }while($fila = $cons_total->fetch_row());
	 }
	if($total == 0)//else
	 {
	  echo "total : 0, porc : 100, errores : false, hechos : 0}";
	  exit;
	 }
/*********************************************************************/
  $errores = array();
  $intervalo = 5;
  $hasta = ($desde + $intervalo);
  $etapa = 0;
  $pos = 0;
  //echo "Totales: {$filas[0][1]}-{$filas[1][1]}-{$filas[2][1]}\n-----------------------------------------------------------------------\n\n";
  for($i = 0; $i < 3; $i++)
   {
   	$pos += $filas[$i][1];
	if($desde < $pos)
     {
	  //echo "Etapa: {$filas[$i][0]} LIMIT {$desde}, ${intervalo}\n";
	  $cons_str = ($tipo_id == 10) ? "galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id, items_atributos ia JOIN {$filas[$i][0]}_valores iv ON ia.id = iv.atributo_id WHERE gi.galeria_id = iv.`int` AND ia.id" : "`{$filas[$i][0]}_valores` iv JOIN imagenes_orig io ON iv.`int` = io.id WHERE atributo_id";
	  $consulta = $mysqli->query("SELECT DISTINCT io.archivo, io.id FROM ${cons_str} = {$_GET['atributo']} ORDER BY 1 LIMIT {$desde}, ${intervalo}");
  //SELECT io.archivo, iaa.id FROM imagenes_a_atributos iaa, imagenes_orig io WHERE iaa.imagen_id = io.id AND iaa.atributo_id = {$_GET['atributo']} ORDER BY 1 LIMIT {$desde}, ${intervalo}");
  if($fila = $consulta->fetch_row())
   {
	do
	 {
	  $imagen = new Imagen($carpeta_originales.$fila[0]);
	  if(!$imagen->dato('errorno'))
	   {
		$ancho = array();
		$alto = array();
		$peso = array();
	    foreach($extra AS $i => $metodos)
		 {
		  $errorno = "false";
		  $imagen->$metodos[0]($metodos[1], $metodos[2], $metodos[4], $metodos[5]);
		  if(is_array($metodos[3])) $imagen->marcaDeAgua($carpeta_imagenes_raiz.'5/'.$metodos[3][0], $metodos[3][1], $metodos[3][2]);
		  $n_imagen_ruta = $imagen->guardar($carpeta_imagenes_raiz.$carpeta_imagenes[$i].$_GET['atributo'], $fila[0]);
		  $ancho[] = $imagen->dato('ancho');
		  $alto[] = $imagen->dato('alto');
		  //$peso[] = filesize($n_imagen_ruta);
		 }
		//$mysqli->query("UPDATE `imagenes_a_atributos` SET `ancho` = '{$ancho[0]}', `alto` = '{$alto[0]}', `peso` = '{$peso[0]}', `ancho_m` = '{$ancho[1]}', `alto_m` = '{$alto[1]}', `peso_m` = '{$peso[1]}' WHERE id = {$fila[1]}");
		$mysqli->query("INSERT INTO `imagenes_a_atributos` (imagen_id, atributo_id, ancho, alto, peso, ancho_m, alto_m, peso_m) VALUES ({$fila[1]}, ${id}, '{$ancho[0]}', '{$alto[0]}', '{$peso[0]}', '{$ancho[1]}', '{$alto[1]}', '{$peso[1]}')");
		$hechos++;
		$intervalo--;
	   }
	  else
	   {
		$total--;
		$errores[] = $fila[0];
	   }
	  $desde++;
	 }while($fila = $consulta->fetch_row());
   }

	  if($hasta >= $pos)
	   {
		//echo "{$intervalo} -= ({$hasta} - {$pos});";
	    $desde = 0;
	   }
	  else $intervalo = 0;
	  //echo "Pos: {$pos}\nIntervalo: ${intervalo}\n";
	 }
	else $desde -= $filas[$i][1];
	if($intervalo <= 0) break;
   }



  $errores_str = count($errores) ? "['".implode("','", $errores)."']" : 'false';
  echo "total : ${total}, errores : ${errores_str},porc : ".round(($hechos * 100) / $total).", hechos : ${hechos}}";
 }

?>