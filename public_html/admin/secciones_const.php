<?php

include_once('inc/iniciar.php');
header("Content-Type: text/plain");

Publicacion_Secciones::publicar();

if($lengs) {
	$bsq_lengs = "AND ";
	if(count($lengs) > 1)
		$bsq_lengs .= "(";
	$bsq_lengs .= "leng_id = '";
	$bsq_lengs .= implode("' OR leng_id = '", $lengs);
	$bsq_lengs .= "'";
	if(count($lengs) > 1)
		$bsq_lengs .= ")";
}
unset($lengs);

$mysqli = BaseDatos::Conectar();
$lenguajes = $mysqli->query("SELECT id, codigo, leng_poromision FROM `lenguajes` WHERE `estado` > 0 AND estado < 5 ${bsq_lengs}");
if($fila_leng = $lenguajes->fetch_row())
 {
  do
   {
	$lengs[$fila_leng[0]] = $fila_leng[1];
	if($fila_leng[2] == 1)
		$leng_poromision = $fila_leng[0];
   }while($fila_leng = $lenguajes->fetch_row());
  $lenguajes->close();
 }

if(!$leng_poromision)
 {
  $leng_porom = $mysqli->query("SELECT id FROM lenguajes WHERE leng_poromision = '1' LIMIT 1");
  if($fila = $leng_porom->fetch_row()) $leng_poromision = $fila[0];
  $leng_porom->close();
 }

$secciones_arr = array();
$secciones_sup = array();


  //$consultasrt = "SELECT se.id AS db_id, superior_id, ads.identificador AS id, se.tipo, se.icono, ads.info, ads.items, ads.categorias, se.permiso_min FROM `secciones` se JOIN `admin_secciones` ads ON se.id = ads.id WHERE estado = '1' ORDER BY superior_id, se.orden, se.id";
  $seccionesListado = Secciones::Listado(null, array('salida_sitio' => 1), 'superior_id, orden, id');
  //$consultasrt = "SELECT id, superior_id, identificador, tipo, info, items, categorias, menu FROM `secciones` WHERE salida_sitio = '1' ORDER BY superior_id, orden, id";
  //if(!$consulta = $mysqli->query($consultasrt))
  //  die($consultasrt);//xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
  if($seccionesListado->total) {
	  $iterador = $seccionesListado->getIterator();
	  foreach($iterador AS $seccion) {
		$secciones_arr[$seccion->id] = array('id' => $seccion->id, 'info' => $seccion->info, 'items' => $seccion->items, 'categorias' => $seccion->categorias, 'menu' => (boolean) $seccion->menu);//, 'superior' => (int) $seccion->superior_id);
		$secciones_sup[$seccion->superior_id][] = $seccion->id;
     }
    //$consulta->close();
   }

   //print_r($secciones_arr);
   //echo "

//";
//   print_r($secciones_ref);

   //$seccionesListado = Secciones::ListadoNombres(array('salida_sitio' => 1), 'superior_id, orden, id');




$nombres = array();
$cons_nombres = $mysqli->query("SELECT sn.id, l.codigo, sn.titulo, sn.url FROM `secciones_nombres` sn JOIN lenguajes l ON sn.leng_id = l.id");// ${bsq_lengs}");
if($fila_nombres = $cons_nombres->fetch_assoc())
 {
  do {
	  //[$fila_nombres[1]]
	  $id = array_shift($fila_nombres);
	  $codigo = array_shift($fila_nombres);
	  if(!isset($secciones_arr[$id]))
		  continue;

	  //$fila_nombres += $secciones_arr[$id];
	  $nombres[$codigo][$fila_nombres['url']] = $id;
	  $secciones_arr[$id]['nombres'][$codigo] = $fila_nombres['titulo'];
	  $secciones_arr[$id]['urls'][$codigo] = $fila_nombres['url'];
	  //print_r($secciones_nombres);
	  //$secciones_nombres[$superiores[$fila_nombres[0]]][$fila_nombres[0]]['url'] = $fila_nombres[3];
   }while($fila_nombres = $cons_nombres->fetch_assoc());
 }


?>