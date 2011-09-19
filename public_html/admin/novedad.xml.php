<?php

$seccion = "novedades";

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

header('Content-type: application/xml');
$doc = new DOMDocument('1.0');
//$doc->formatOutput = true;
$root = $doc->createElement('novedad');
//$root->setAttribute("nombre", utf8_encode(SITIO_TITULO));
$root = $doc->appendChild($root);

$consultastr = "SELECT xml_lang, dir, novedad_titulo, novedad_subtitulo, novedad_texto FROM `lenguajes` l LEFT JOIN `novedades_textos` nt ON l.leng_id = nt.leng_id AND `novedad_id` = '".$_REQUEST['id']."' WHERE l.leng_id = '".$_REQUEST['leng']."'";
$consulta = mysql_query($consultastr, $mysql) or die(xml_sqlerror(mysql_errno(), $consultastr, mysql_error()));
if ($fila = mysql_fetch_row($consulta))
 {
  do
   {
    $lang = $doc->createElement('lang');
    $lang_txt = $doc->createTextNode($fila[0]);
    $lang_txt = $lang->appendChild($lang_txt);
    $lang = $root->appendChild($lang);
    $dir = $doc->createElement('dir');
    $dir_txt = $doc->createTextNode($fila[1]);
    $dir_txt = $dir->appendChild($dir_txt);
    $dir = $root->appendChild($dir);
    $titulo = $doc->createElement('titulo');
    $titulo_txt = $doc->createTextNode($fila[2]);
    $titulo_txt = $titulo->appendChild($titulo_txt);
    $titulo = $root->appendChild($titulo);
	$subtitulo = $doc->createElement('subtitulo');
    $subtitulo_txt = $doc->createTextNode($fila[3]);
    $subtitulo_txt = $subtitulo->appendChild($subtitulo_txt);
    $subtitulo = $root->appendChild($subtitulo);
    $texto = $doc->createElement('texto');
    $texto_txt = $doc->createTextNode($fila[4]);
    $texto_txt = $texto->appendChild($texto_txt);
    $texto = $root->appendChild($texto);
   } while ($fila = mysql_fetch_row($consulta));
 }

echo $doc->saveXML();

?>