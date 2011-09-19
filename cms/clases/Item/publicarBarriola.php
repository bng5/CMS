<?php

class Item_publicarBarriola
 {
  function __construct($seccion)
   {
    global $mysqli;
    //$this->mysqli = $mysqli;
	$this->seccion = $seccion;
	$this->modificadas = 0;
	$this->leng_poromision = false;
	$this->lengs = array();
	$lenguajes = $mysqli->query("SELECT id, leng_cod FROM `lenguajes` WHERE `leng_habilitado` = '1' ORDER BY `leng_poromision` DESC");
	if($fila_leng = $lenguajes->fetch_row())
	 {
	  do
	   {
		$this->lengs[$fila_leng[0]] = $fila_leng[1];
		if($this->leng_poromision == false) $this->leng_poromision = $fila_leng[0];
	   }while($fila_leng = $lenguajes->fetch_row());
	  $lenguajes->close();
	 }
   }

  function Item($id)
   {
	global $mysqli;
	if(!$consulta_item = $mysqli->query("SELECT `miniatura` FROM `{$this->seccion}` WHERE `id` = '${id}' LIMIT 1")) die($mysqli->error);
	if($fila_item = $consulta_item->fetch_row())
	 {
	  $miniatura = $fila_item[0];
	  $consulta_item->close();
	 }

	/* Ícono */
	if(!empty($miniatura))
	 {
	  $icono = "../img/{$this->seccion}/iconos/${id}.jpg";
	  $miniatura = "../img/{$this->seccion}/imagenes/${miniatura}";
	  $imagen = new Imagen($miniatura);
	  if(!$imagen->valor('error'))
	   {
	    @unlink($icono);
	    $imagen->escalarEstricto(90, 225);
	    $n_imagen_ruta = $imagen->guardar("../img/{$this->seccion}/iconos/", $id, "jpg");
	   }
	 }
	
	foreach($this->lengs AS $leng_id => $leng_cod)
	 {
	  /* Publicar índice */
/***************/
	  if($sqlite = new SQLiteDatabase("../menuXml/{$this->seccion}.sqlite", 0666, $sqlite_error))
	   {
		@$sqlite->queryExec("create table galerias (id integer, categoria integer, orden integer, miniatura varchar(50), titulo varchar(50), cliente varchar(50), texto text)");
		@$sqlite->queryExec("CREATE VIEW ver_galerias AS SELECT * FROM galerias ORDER BY 3 ASC");

		$consultasrt = "SELECT g.id, g.categoria_id, g.orden, g.miniatura, gt.titulo, git.imagen_titulo AS cliente, gt.texto FROM galerias g LEFT JOIN galerias_valores gv ON g.id = gv.galeria_id AND gv.atributo_id = '1', galerias_textos gt, galerias_imagenes_textos git WHERE g.id = gt.galeria_id AND gt.leng_id = '1' AND g.id = '${id}' AND gv.`int` = git.imagen_id LIMIT 1"; // ORDER BY ordennull, galeria_orden, leng_poromision";
	    if(!$consulta = $mysqli->query($consultasrt)) die(__LINE__."\n".$mysqli->errno."\n".$consultasrt."\n".$mysqli->error);
	    if($fila = $consulta->fetch_row())
	     {
		  do
		   {
			$sqlite->queryExec("DELETE FROM {$this->seccion} WHERE id = '".$fila[0]."'");
			$sqlite->queryExec("INSERT INTO {$this->seccion} VALUES (".$fila[0].", ".$fila[1].", ".$fila[2].", '".$fila[3]."', '".$fila[4]."', '".$fila[5]."', '".$fila[6]."')");
		   }while($fila = $consulta->fetch_row());

/*
		  $descripcion = $fila[2];
		  $indice = $fila[0];

		  $consultasrt2 = "SELECT atributo_identificador, texto FROM `galerias_atributos` gat, `galerias_info` gi LEFT JOIN `galerias_valores_t` gvt ON gi.valor_id = gvt.valor_id AND (gvt.leng_id = '$leng_id' OR gvt.leng_id = '$leng_poromision'), lenguajes le WHERE gvt.leng_id = le.leng_id AND gat.galeria_atrib_id = gi.atributo_id AND galeria_id = '${indice}' ORDER BY atributo_orden, leng_poromision";
		  if(!$consulta2 = $mysqli->query($consultasrt2)) die(__LINE__." ".$mysqli->errno." ".$consultasrt2." ".$mysqli->error);
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
//		  $sqlite->queryExec("DELETE FROM {$this->seccion} WHERE id = '".$fila[0]."' AND leng = '${leng_cod}'");
//		  $sqlite->queryExec("INSERT INTO {$this->seccion} VALUES ('".$fila[0]."', '${leng_cod}', '".$fila[4]."', '".time()."', '".$fila[1]."', '${id}', '".$mc_zona."', '".$mc_materiales."')");
*/
		 }
	   }
	  else
	   {
		die($sqlite_error);
	   }
	  /* Publicar galeria */
	  $doc = new DOMDocument('1.0', 'utf-8');
	  $root = $doc->createElement('node');
	  $root = $doc->appendChild($root);

	  $indice = '';
	  $valores = array(1 => "nombre", "descripcion", "fecha");
	  $imagenes = $mysqli->query("SELECT gi.imagen_archivo_nombre, git.imagen_titulo, git.imagen_texto, gi.imagen_orden IS NULL AS ordennull, gi.ancho FROM `galerias_imagenes` gi LEFT JOIN galerias_imagenes_textos git ON gi.imagen_id = git.imagen_id AND (git.leng_id = '${leng_id}' OR git.leng_id = '{$this->leng_poromision}') WHERE galeria_id = '${id}' AND imagen_estado = '1' ORDER BY ordennull, imagen_orden");
	  if($fila2 = $imagenes->fetch_row())
	   {
		do
		 {
		  if($indice == $fila2[0]) continue;
		  $indice = $fila2[0];
	      $nodoseccion = $doc->createElement('node');
	      $nodoseccion = $root->appendChild($nodoseccion);

		  $nodoseccion->setAttribute("foto", "imagenes/".$fila2[0]);
		  $nodoseccion->setAttribute("anchoDeFoto", $fila2[4]);
		  for($i = 1; $i <= count($valores); $i++)
		   {
			if($fila2[$i]) $nodoseccion->setAttribute($valores[$i], $fila2[$i]);
		   }
		 }while($fila2 = $imagenes->fetch_row());
		$imagenes->close();

	   }
	  $doc->save("../galerias/${id}.xml.${leng_cod}");
	  unset($doc);
	 }
	$mysqli->query("UPDATE `{$this->seccion}` SET `estado` = '1' WHERE `id` = '${id}'");
	$this->modificadas++;
   }
 }

?>