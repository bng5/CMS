<?php

require_once('../../inc/configuracion.php');
// header('Content-type: application/xml; charset=utf-8');

if($lengs)
 {
  $bsq_lengs = "AND ";
  if(count($lengs) > 1) $bsq_lengs .= "(";
  $bsq_lengs .= "leng_id = '";
  $bsq_lengs .= implode("' OR leng_id = '", $lengs);
  $bsq_lengs .= "'";
  if(count($lengs) > 1) $bsq_lengs .= ")";
 }
unset($lengs);

$lenguajes = $mysqli->query("SELECT leng_id, leng_cod, leng_poromision FROM `lenguajes` WHERE `leng_habilitado` = '1' ${bsq_lengs}");
if($fila_leng = $lenguajes->fetch_row())
 {
  do
   {
	$lengs[$fila_leng[0]] = $fila_leng[1];
	if($fila_leng[2] == 1) $leng_poromision = $fila_leng[0];
   }while($fila_leng = $lenguajes->fetch_row());
  $lenguajes->close();
 }

if(!$leng_poromision)
 {
  $leng_porom = $mysqli->query("SELECT leng_id FROM lenguajes WHERE leng_poromision = '1' LIMIT 1");
  if($fila = $leng_porom->fetch_row()) $leng_poromision = $fila[0];
  $leng_porom->close();
 }

/* Ícono */
if(!empty($_POST['miniatura']))
 {
  include_once('../../inc/class_imagen.php');
  $icono = "../img/galerias/iconos/${id}.jpg";
  $miniatura = '../img/galerias/imagenes/'.$_POST['miniatura'];
  $imagen = new Imagen($miniatura);
  if(!$imagen->valor('error'))
   {
    @unlink($icono);
    $imagen->escalarEstricto(40, 40);
    $n_imagen_ruta = $imagen->guardar('../img/galerias/iconos/', $id, "jpg");
   }
 }

foreach($lengs AS $leng_id => $leng_cod)
 {
  /* Publicar índice */
  $doc = new DOMDocument('1.0', 'utf-8');
  if(@$doc->load('../menuXml/galerias.xml.'.$leng_cod))
   {
    $root = $doc->firstChild;
    $doc->validateOnParse = true;
    $nodoseccion = $doc->getElementById($id);
   }
  else
   {
	//$doc->formatOutput = true;
    $root = $doc->createElement('galerias');
    //$root->setAttribute("nombre", utf8_encode(SITIO_TITULO));
    $root = $doc->appendChild($root);
   }
  $consultasrt = "SELECT ga.galeria_id, galeria_titulo, galeria_texto, galeria_miniatura, galeria_orden IS NULL AS ordennull FROM `galerias` ga LEFT JOIN `galerias_textos` gt ON ga.galeria_id = gt.galeria_id AND (gt.leng_id = '$leng_id' OR gt.leng_id = '$leng_poromision'), lenguajes le WHERE gt.leng_id = le.leng_id AND ga.galeria_id = '${id}' LIMIT 1"; // ORDER BY ordennull, galeria_orden, leng_poromision";
  if(!$consulta = $mysqli->query($consultasrt)) die(xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
  if ($fila = $consulta->fetch_row())
   {
	// do
    // {
      //if($indice == $fila[0]) continue;
	  $descripcion = $fila[2];
      $indice = $fila[0];
      if($nodoseccion)
       {
        while($nodoseccion->firstChild) $nodoseccion->removeChild($nodoseccion->firstChild);
       }
      else
	   {
        $nodoseccion = $doc->createElement('galeria');
        $nodoseccion = $root->appendChild($nodoseccion);
        $nodoseccion->setAttribute("id", $fila[0]);
        $nodoseccion->setAttribute("xml:id", $fila[0]);
       }

	  $texto = $doc->createElement('texto');
	  $texto->setAttribute("titulo", "mc_titulo");
	  $texto_txt = $doc->createTextNode($fila[1]);
	  $texto_txt = $texto->appendChild($texto_txt);
	  $texto = $nodoseccion->appendChild($texto);

	  $consultasrt2 = "SELECT atributo_identificador, texto FROM `galerias_atributos` gat, `galerias_info` gi LEFT JOIN `galerias_valores_t` gvt ON gi.valor_id = gvt.valor_id AND (gvt.leng_id = '$leng_id' OR gvt.leng_id = '$leng_poromision'), lenguajes le WHERE gvt.leng_id = le.leng_id AND gat.galeria_atrib_id = gi.atributo_id AND galeria_id = '${indice}' ORDER BY atributo_orden, leng_poromision";
	  if(!$consulta2 = $mysqli->query($consultasrt2)) die(xml_sqlerror($mysqli->errno, $consultasrt2, $mysqli->error));
	  if ($fila2 = $consulta2->fetch_row())
	   {
	    do
	     {
	      if($indice2 == $fila2[0]) continue;
	      $indice2 = $fila[0];
		  $texto = $doc->createElement('texto');
		  $texto->setAttribute("titulo", "mc_${fila2[0]}");
		  $texto_txt = $doc->createTextNode($fila2[1]);
		  $texto_txt = $texto->appendChild($texto_txt);
		  $texto = $nodoseccion->appendChild($texto);
	     }while($fila2 = $consulta2->fetch_row());
	    $consulta2->close();
	   }
	  $img = $doc->createElement('img');
	  $img->setAttribute("titulo", "mc_img");
	  $img_txt = $doc->createTextNode('img/galerias/iconos/'.$id.'.jpg');
	  $img_txt = $img->appendChild($img_txt);
	  $img = $nodoseccion->appendChild($img);
    // }while($fila = $consulta->fetch_row());
    $consulta->close();
   }
  $doc->save('../menuXml/galerias.xml.'.$leng_cod);
  unset($doc);

  /* Publicar galeria */
  $doc = new DOMDocument('1.0', 'utf-8');
  $root = $doc->createElement('galeria');
  $root = $doc->appendChild($root);
  
  $nodo_descripcion = $doc->createElement('texto');
  $nodo_descripcion->setAttribute("titulo", "mc_descripcion");
  $descripcion_txt = $doc->createTextNode($descripcion);
  $descripcion_txt = $nodo_descripcion->appendChild($descripcion_txt);
  $nodo_descripcion = $root->appendChild($nodo_descripcion);

  $indice = '';
  $imagenes = $mysqli->query("SELECT imagen_archivo_nombre, imagen_titulo, imagen_texto, imagen_orden IS NULL AS ordennull FROM `galerias_imagenes` gi LEFT JOIN galerias_imagenes_textos git ON gi.imagen_id = git.imagen_id AND (git.leng_id = '${leng_id}' OR git.leng_id = '${leng_poromision}') WHERE galeria_id = '${id}' ORDER BY ordennull, imagen_orden");
  if($fila2 = $imagenes->fetch_row())
   {
    $contenedor = $doc->createElement('imagenes');
    $contenedor = $root->appendChild($contenedor);
	do
	 {
	  // imagen_archivo_nombre 	imagen_titulo 	imagen_texto
	  if($indice == $fila2[0]) continue;
	  $indice = $fila2[0];
      $nodoseccion = $doc->createElement('imagen');
      $nodoseccion->setAttribute("archivo", $fila2[0]);
      $nodoseccion = $contenedor->appendChild($nodoseccion);

	  if($fila2[1])
	   {
	    $texto = $doc->createElement('texto');
	    $texto->setAttribute("titulo", "mc_imgtitulo");
	    $texto_txt = $doc->createTextNode($fila2[1]);
	    $texto_txt = $texto->appendChild($texto_txt);
	    $texto = $nodoseccion->appendChild($texto);
	   }
	  if($fila2[2])
	   {
	    $texto = $doc->createElement('texto');
	    $texto->setAttribute("titulo", "mc_imgdescripcion");
	    $texto_txt = $doc->createTextNode($fila2[2]);
	    $texto_txt = $texto->appendChild($texto_txt);
	    $texto = $nodoseccion->appendChild($texto);
	   }
	 }while($fila2 = $imagenes->fetch_row());
	$imagenes->close();
   }
  $doc->save("../galerias/${id}.xml.${leng_cod}");
  unset($doc);
 }

/*
$lenguajes = $mysqli->query("SELECT leng_id, leng_cod FROM `lenguajes` WHERE leng_habilitado = '1'");
if($fila = $lenguajes->fetch_row())
 {
  do
   {

   }while($fila = $lenguajes->fetch_row());
  $lenguajes->close();
 }
*/
 
?>