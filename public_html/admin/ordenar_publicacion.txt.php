<?php

if(!empty($_REQUEST['seccion']) && !empty($_REQUEST['orden_criterio']))
 {
//print_r($_REQUEST);
  $seccion = $_REQUEST['seccion'];
  require('../../inc/configuracion.php');
  require('../../inc/ad_sesiones.php');
  header('Content-type: text/plain');
  $dir = ($_REQUEST['orden_dir'] == "1") ? "DESC": "ASC";
  if($sqlite = new SQLiteDatabase("../menuXml/${seccion}.sqlite", 0666, $sqlite_error))
   {
	@$sqlite->queryExec("DROP VIEW ver_${seccion}");
	@$sqlite->queryExec("CREATE VIEW ver_${seccion} AS SELECT * FROM ${seccion} ORDER BY ".$_REQUEST['orden_criterio']." ${dir}");
	print "1";
/*
	$consultasrt = "SELECT ga.id, galeria_titulo, galeria_texto, miniatura, galeria_orden, galeria_orden IS NULL AS ordennull FROM `galerias` ga LEFT JOIN `galerias_textos` gt ON ga.id = gt.galeria_id AND (gt.leng_id = '$leng_id' OR gt.leng_id = '{$this->leng_poromision}'), lenguajes le WHERE gt.leng_id = le.leng_id AND ga.id = '${id}' LIMIT 1"; // ORDER BY ordennull, galeria_orden, leng_poromision";
    if(!$consulta = $mysqli->query($consultasrt)) die(xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
    if($fila = $consulta->fetch_row())
     {
	  $descripcion = $fila[2];
      $indice = $fila[0];

	  $consultasrt2 = "SELECT atributo_identificador, texto FROM `galerias_atributos` gat, `galerias_info` gi LEFT JOIN `galerias_valores_t` gvt ON gi.valor_id = gvt.valor_id AND (gvt.leng_id = '$leng_id' OR gvt.leng_id = '$leng_poromision'), lenguajes le WHERE gvt.leng_id = le.leng_id AND gat.galeria_atrib_id = gi.atributo_id AND galeria_id = '${indice}' ORDER BY atributo_orden, leng_poromision";
	  if(!$consulta2 = $mysqli->query($consultasrt2)) die(xml_sqlerror($mysqli->errno, $consultasrt2, $mysqli->error));
	  if ($fila2 = $consulta2->fetch_row())
	   {
	    do
	     {
	      if($indice2 == $fila2[0]) continue;
	      $indice2 = $fila[0];
		  $campo = "mc_${fila2[0]}";
		  $$campo = $fila2[1];
	     }while($fila2 = $consulta2->fetch_row());
	    $consulta2->close();
	   }
	 }
*/
   }
 }

?>