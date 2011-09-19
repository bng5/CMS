<?php

$seccion = "galerias";
$seccion_id = $_REQUEST['cat_sup'];

require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');

$seccion_id = $_REQUEST['cat'];

/**********************************************/
if($_REQUEST['id'] && $_REQUEST['pos'])
 {
  $item_id = $_REQUEST['id'];
  $inicial = $_REQUEST['posorig'] ? $_REQUEST['posorig'] : 1;
  $final = $_REQUEST['pos'];
  $padre = $seccion_id;
  if($inicial < $final)// ab
   {
    $ag_preg_pos_inc = $inicial;
    $tbnovedades = $mysqli->query("SELECT `id`, `orden` IS NULL AS ordennull FROM `galerias` WHERE `orden` > '${inicial}' AND `orden` <= '${final}' AND `categoria_id` = '${padre}' AND `id` != '${item_id}' ORDER BY ordennull ASC, `orden` ASC");
    if ($row = $tbnovedades->fetch_row())
     {
      do
       {
//echo "UPDATE `galerias` SET `orden` = '${ag_preg_pos_inc}' WHERE `id` = '".$row[0]."'\n";
        $mysqli->query("UPDATE `galerias` SET `orden` = '${ag_preg_pos_inc}' WHERE `id` = '".$row[0]."'");
        $ag_preg_pos_inc--;
       } while($row = $tbnovedades->fetch_row());
      $tbnovedades->close();
     }
   }
  elseif($inicial > $final) // arr
   {
    $ag_preg_pos_inc = ($final + 1);
    $tbnovedades = $mysqli->query("SELECT `id`, `orden` IS NULL AS ordennull FROM `galerias` WHERE `orden` >= '${final}' AND `orden` < '${inicial}' AND `categoria_id` = '${padre}' AND `id` != '${item_id}' ORDER BY ordennull ASC, `orden` ASC");
    if ($row = $tbnovedades->fetch_row())
     {
      do
       {
//echo "UPDATE `galerias` SET `orden` = '${ag_preg_pos_inc}' WHERE `id` = '".$row[0]."'\n";
        $mysqli->query("UPDATE `galerias` SET `orden` = '${ag_preg_pos_inc}' WHERE `id` = '".$row[0]."'");
        $ag_preg_pos_inc++;
       } while($row = $tbnovedades->fetch_row());
      $tbnovedades->close();
     }
   }
//echo "UPDATE `galerias` SET `orden` = '${final}' WHERE `id` = '${item_id}'";
  $mysqli->query("UPDATE `galerias` SET `orden` = '${final}' WHERE `id` = '${item_id}'");
  //if($mysqli->affected_rows) echo "1";
 }

// reordenar
$consulta = $mysqli->query("SELECT `id`, `orden` IS NULL AS ordennull FROM `galerias` WHERE categoria_id = '${seccion_id}' ORDER BY ordennull ASC, `orden` ASC");
if($fila = $consulta->fetch_row())
 {
  $reor_num = 0;
  do
   {
    $reor_num++;
    $mysqli->query("UPDATE `galerias` SET `orden` = '${reor_num}' WHERE `id` = '".$fila[0]."'");
   } while($fila = $consulta->fetch_row());
  $consulta->close();
 }
/**********************************************/

header('Content-type: application/xml');
$doc = new DOMDocument('1.0', 'utf-8');
$root = $doc->createElement('galerias');
$root = $doc->appendChild($root);

$pagina = is_numeric($_REQUEST["pagina"]) ? floor($_REQUEST["pagina"]): 1;
$a = is_numeric($_REQUEST["resultados"]) ? floor($_REQUEST["resultados"]): 25;
$desde = ($pagina-1)*$a;
if(!empty($_GET['cat'])) $bsq_cat = "WHERE categoria_id = '".$_GET['cat']."'";

$root->setAttribute("rpp", $a);
$consultatotal = $mysqli->query("SELECT id FROM `galerias` ${bsq_cat}");
$total = $consultatotal->num_rows;
$root->setAttribute("total", $total);

$consultastr = "SELECT ga.id, titulo, estado_id, miniatura, DATE_FORMAT(creada, '%e--%Y %H:%i hs.'), orden, DATE_FORMAT(creada, '%c'), `orden` IS NULL AS ordennull FROM `galerias` ga LEFT JOIN `galerias_textos` gt ON ga.id = gt.galeria_id AND leng_id = '${leng}' ${bsq_cat} ORDER BY ordennull ASC, `orden` ASC LIMIT ${desde}, ${a}";
//$consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, xml_lang, dir $campos FROM `lenguajes` le LEFT JOIN secciones_textos st ON le.leng_id = st.leng_id $condicion LEFT JOIN `secciones` se ON se.seccion_id = st.seccion_id WHERE $lenguajes ORDER BY $orden";
if(!$consulta = $mysqli->query($consultastr)) die(xml_sqlerror($mysqli->errno, $consultastr, $mysqli->error));
if($fila = $consulta->fetch_row())
 {
  do
   {
	$seccion = $doc->createElement('galeria');
	$seccion->setAttribute("id", $fila[0]);
	$seccion->setAttribute("estado", $fila[2]);
	$seccion->setAttribute("img", $fila[3]);
	$seccion->setAttribute("creada", str_replace("--", "-".substr($meses[$fila[6]], 0, 3)."-", $fila[4]));
	$seccion->setAttribute("orden", $fila[5]);
	$titulo = $doc->createTextNode($fila[1]);
	$titulo = $seccion->appendChild($titulo);
	$seccion = $root->appendChild($seccion);
   } while ($fila = $consulta->fetch_row());
  $consulta->close();
  $root->setAttribute("pagina", $pagina);
 }
$root->setAttribute("paginas", ceil($total/$a));

echo $doc->saveXML();

?>