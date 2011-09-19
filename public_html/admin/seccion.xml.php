<?php

$seccion = "secciones";

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

header('Content-type: application/xml');
$doc = new DOMDocument('1.0');
$root = $doc->createElement('secciones');
$root = $doc->appendChild($root);

if(isset($_REQUEST['seccion']))
 {
  $lenguajes = "le.leng_id = '".$_REQUEST['leng'][0]."'";
  if($_REQUEST['leng'][1]) $lenguajes .= " OR le.leng_id = '".$_REQUEST['leng'][1]."'";
  $consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, xml_lang, dir, seccion_texto, seccion_usartexto, seccion_subniveles, seccion_tipo, seccion_imagenes, le.leng_id, seccion_ttitulo FROM `lenguajes` le LEFT JOIN secciones_textos st ON le.leng_id = st.leng_id AND st.seccion_id = '".$_REQUEST['seccion']."' LEFT JOIN `secciones` se ON se.seccion_id = st.seccion_id WHERE $lenguajes ORDER BY se.`seccion_id` DESC";
 }
else $consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, seccion_superior, seccion_subniveles FROM secciones se JOIN secciones_textos st ON se.seccion_id = st.seccion_id WHERE seccion_superior = '".$_REQUEST['superior']."' AND leng_id = '".$_REQUEST['leng']."' ORDER BY seccion_orden";

//$consultastr = "SELECT se.seccion_id, seccion_estado, seccion_titulo, xml_lang, dir $campos FROM `lenguajes` le LEFT JOIN secciones_textos st ON le.leng_id = st.leng_id $condicion LEFT JOIN `secciones` se ON se.seccion_id = st.seccion_id WHERE $lenguajes ORDER BY $orden";
if($consultastr)
 {
  if(!$consulta = $mysqli->query($consultastr)) die(xml_sqlerror($mysql->errno, $consultastr, $mysql->error));
  if($fila = $consulta->fetch_row())
   {
    if(isset($_REQUEST['seccion']))
     {
      if($fila[9] == NULL) $imgs = "nulo";
      else $imgs = $fila[9];
	  $root->setAttribute("id", $fila[0]);
	  $root->setAttribute("estado", $fila[1]);
	  $root->setAttribute("texto", $fila[6]);
	  $root->setAttribute("imgs", $imgs);
	  $root->setAttribute("subniveles", $fila[7]);
	  $root->setAttribute("tipo", $fila[8]);

	  $textos = $doc->createElement('textos');
	  $textos = $root->appendChild($textos);
	  do
	   {
	    $seccion = $doc->createElement('seccion');
	    $seccion->setAttribute("lang", $fila[3]);
	    $seccion->setAttribute("dir", $fila[4]);
	    $seccion->setAttribute("leng_id", $fila[10]);
	    $titulo = $doc->createElement('titulo');
	    $titulo_txt = $doc->createTextNode($fila[2]);
	    $titulo_txt = $titulo->appendChild($titulo_txt);
	    $titulo = $seccion->appendChild($titulo);
		if($fila[6] == 1)
		 {
	      $texto = $doc->createElement('texto');
	      $texto->setAttribute("titulo", $fila[11]);
	      $texto_txt = $doc->createTextNode($fila[5]);
	      $texto_txt = $texto->appendChild($texto_txt);
	      $texto = $seccion->appendChild($texto);
	     }
	    $seccion = $textos->appendChild($seccion);
	   } while ($fila = $consulta->fetch_row());
     }
    else
     {
	  do
	   {
	    $seccion = $doc->createElement('seccion');
	    $seccion->setAttribute("id", $fila[0]);
		$seccion->setAttribute("superior", $fila[3]);
	    $seccion->setAttribute("estado", $fila[1]);
	    $seccion->setAttribute("subniveles", $fila[4]);
	    $titulo = $doc->createTextNode($fila[2]);
	    $titulo = $seccion->appendChild($titulo);
	    $seccion = $root->appendChild($seccion);
	   } while ($fila = $consulta->fetch_row());
     }
    $consulta->close();
   }
 }

echo $doc->saveXML();

?>