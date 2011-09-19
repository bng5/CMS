<?php

class Seccion_publicarxhtml
 {
  function __construct($seccion)
   {
    global $mysqli, $seccion_id;
    //$this->mysqli = $mysqli;
	$this->seccion = $seccion;
	$this->seccion_id = $seccion_id;
	$this->modificadas = 0;
	$this->leng_poromision = false;
	$this->lengs = array();
	$this->etiquetas = array();
	$this->subatributos = array();
	$this->atributos = array();

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
	if(!$cons_attrs = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, at.tipo, at.subtipo, ia.extra, isaa.salida, ia.unico, ia.et_xhtml FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, secciones_a_atributos isaa, atributos_tipos at WHERE at.id = ia.tipo_id AND ia.id = isaa.atributo_id AND isaa.seccion_id = {$this->seccion_id} ORDER BY isaa.orden")) echo __LINE__." - ".$mysqli->error;
	if($fila_attrs = $cons_attrs->fetch_assoc())
	 {
	  do
	   {
		$atributo_id = array_shift($fila_attrs);
		$leng_id = array_shift($fila_attrs);
		$etiqueta = array_shift($fila_attrs);
		if(!$this->atributos[$atributo_id]) $this->atributos[$atributo_id] = $fila_attrs;
		$this->atributos[$atributo_id]['etiquetas'][$leng_id] = $etiqueta;
		if($fila_attrs['tipo'] == "int")
		 {
		  if($fila_attrs['subtipo'] == 4 || $fila_attrs['subtipo'] == 7)
		   {
			if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, at.tipo, at.subtipo, ia.extra, ia.unico FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, subitems_supatributos_a_atributos isaa, atributos_tipos at WHERE at.id = ia.tipo_id AND ia.id = isaa.atributo_id AND isaa.sup_atributo_id = ${atributo_id} ORDER BY isaa.orden")) echo __LINE__." - ".$mysqli->error;
			if($fila_at = $atributos_tipos->fetch_assoc())
			 {
			  do
			   {
				$attr_id = array_shift($fila_at);
				$subleng_id = array_shift($fila_at);
				if(!$this->subatributos[$atributo_id][$attr_id]) $this->subatributos[$atributo_id][$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
				$this->subatributos[$atributo_id][$attr_id]['etiquetas'][$subleng_id] = $fila_at['atributo'];
			   }while($fila_at = $atributos_tipos->fetch_assoc());
			  $atributos_tipos->close();
			 }
		   }
		  elseif($fila_attrs['tipo'] == "text") $s_tipo = "text";
		 }
       }while($fila_attrs = $cons_attrs->fetch_assoc());
	  $cons_attrs->close();
	 }
   }

  function Item($id)
   {
	global $mysqli;
	$seccion_nombre = array();
	if(!$cons_item = $mysqli->query("SELECT sn.leng_id, sn.titulo, ads.identificador FROM secciones_nombres sn JOIN admin_secciones ads ON sn.id = ads.id WHERE ads.id = ${id} AND sn.titulo != ''")) echo __LINE__." - ".$mysqli->error;
	if($fila_item = $cons_item->fetch_row())
	 {
	  $identificador = $fila_item[2];
	  do
	   {
		$seccion_nombre[$fila_item[0]] = $fila_item[1];
	   }while($fila_item = $cons_item->fetch_row());
	  $cons_item->close();
	 }
	else
	 {
	  echo "No se encontró el item";
	  exit;
	 }
	$valores = array();
	if(!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM secciones_valores iv WHERE iv.item_id = ${id} ORDER BY iv.leng_id")) echo __LINE__." - ".$mysqli->error;
	if($fila_valores = $cons_valores->fetch_assoc())
	 {
	  do
	   {
		$atributo_id = array_shift($fila_valores);
		$leng_id = array_shift($fila_valores);
		if($leng_id) $valores[$atributo_id][$leng_id] = $fila_valores;
		else
		 {
		  if($this->atributos[$atributo_id]['unico'] == 1) $valores[$atributo_id] = $fila_valores;
		  else $valores[$atributo_id][] = $fila_valores;
	 	 }
	   }while($fila_valores = $cons_valores->fetch_assoc());
	  $cons_valores->close();
	 }

	$tipos = array('string' => 'texto', 'num' => 'texto', 'text' => 'areadetexto', 'date' => 'texto');

//	'public_sec_des' => '',
	foreach($this->lengs AS $leng_id => $leng_cod)
	 {
	  $doc = new DOMDocument('1.0', 'utf-8');
	  //$doc->appendChild(new DOMProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="/xsl/seccion.xsl"'));
	  //$doc->formatOutput = true;
	  $root = $doc->createElement('div');
	  $root = $doc->appendChild($root);
	  $root->setAttribute("id", $identificador);
	  /*$root->setAttribute("xml:lang", $leng_cod);
	  $item = $doc->createElement('item');
	  $nombre = $seccion_nombre[$leng_id] ? $seccion_nombre[$leng_id] : $seccion_nombre[1];
	  $item->setAttribute("etiqueta", $nombre);
	  $item = $root->appendChild($item);
	  */
	  foreach($this->atributos AS $attr_k => $attr_v)
	   {
	   	if($attr_v['tipo'] == "int")
	   	 {
	   	  if($attr_v['subtipo'] == 1)
	   	   {
			$dato = $doc->createElement('dato');
			$dato->setAttribute("tipo", "texto");
			$e_valor = $valores[$attr_k]['int'];
			if(!$cons_valores = $mysqli->query($attr_v['extra'].$leng_id." AND i.id = {$valores[$attr_k]['int']} LIMIT 1")) echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_row())
			 {
			  if(empty($valor_leng[$e_valor])) $valor_leng[$e_valor] = $fila_valores[1];
			  $dato->appendChild($doc->createTextNode($fila_valores[1]));
			  $cons_valores->close();
			 }
		   }
		  elseif($attr_v['subtipo'] == 2)
	   	   {
			$dato = $doc->createElement('img');
			if(!empty($valores[$attr_k]['int']))
			 {
			  $cons_img = $mysqli->query("SELECT io.formato, iaa.peso, io.archivo, iaa.ancho, iaa.alto, iaa.ancho_m, iaa.alto_m, iaa.peso_m FROM imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ${attr_k} WHERE io.id = {$valores[$attr_k]['int']}");
			  $img = $cons_img->fetch_row();
			 }
			if(empty($img[2])) continue;
			$dato->setAttribute("width", $img[3]);
			$dato->setAttribute("height", $img[4]);
			$dato->setAttribute("src", "/img/0/${attr_k}/".urlencode($img[2]));
			$dato->setAttribute("alt", "");
		   }
		  elseif($attr_v['subtipo'] == 3)
		   {
		   	if($attr_v['unico'] == 1)
		   	 {
			  $dato = $doc->createElement('a');
			  $valor[$attr_k] = $valores[$attr_k]['int'];
		   	 }
		   	else
		   	 {
			  $dato = $doc->createElement('ul');
			  if(!$acons[$attr_k])
			   {
				$acons[$attr_k] = array();
				if(is_array($valores[$attr_k])) foreach($valores[$attr_k] AS $nu_v) $acons[$attr_k][] = $nu_v['int'];
				if(count($acons[$attr_k])) $valor[$attr_k] = implode("' OR id = '", $acons[$attr_k]);
			   }
		   	 }
		   	if(empty($valor[$attr_k])) continue;
		   	else
			 {
			  $cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = '{$valor[$attr_k]}'");
			  if($img = $cons_img->fetch_row())
			   {
				if($attr_v['unico'] == 1)
				 {
				  $dato->setAttribute("type", $img[0]);
				  //$dato->setAttribute("peso", $img[1]);
				  $dato->setAttribute("href", "/archivos/".urlencode($img[2]));
				 }
				else
				 {
				  do
				   {
				   	$aldato = $doc->createElement('a');
					$aldato->setAttribute("type", $img[0]);
					//$aldato->setAttribute("peso", $img[1]);
					$aldato->setAttribute("href", "/archivos/".urlencode($img[2]));
					$aldato = $dato->appendChild($aldato);
				   }while($img = $cons_img->fetch_row());
				 }
			   }
			  else  continue;
			 }
		   }
		  elseif($attr_v['subtipo'] == 4)
		   {
		   	$dato = $doc->createElement('div');
		   	//$dato->setAttribute("imagenes", "img/0/${attr_k}/");
		   	//$dato->setAttribute("miniaturas", "img/1/${attr_k}/");
			$imagenes = "/img/0/${attr_k}/";
		   	$miniaturas = "/img/1/${attr_k}/";
		   	if(empty($valores[$attr_k]['int'])) continue;

			$subvalores = array();
			if(!$cons_valores = $mysqli->query("SELECT atributo_id, imagen_id, leng_id, `string`, `date`, `text`, `int`, `num` FROM galerias_imagenes_valores WHERE galeria_id = {$valores[$attr_k]['int']}")) echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_assoc())
			 {
			  do
			   {
				$atributo_id = array_shift($fila_valores);
				$imagen_id = array_shift($fila_valores);
				$subleng_id = array_shift($fila_valores);
				if($subleng_id) $subvalores[$atributo_id][$imagen_id][$subleng_id] = $fila_valores;
				else
				 {
				  if($this->subatributos[$attr_k][$atributo_id]['unico'] == 1) $subvalores[$atributo_id][$imagen_id] = $fila_valores;
				  else $subvalores[$atributo_id][$imagen_id][] = $fila_valores;
	 			 }
			   }while($fila_valores = $cons_valores->fetch_assoc());
			  $cons_valores->close();
			 }
			$cons_img = $mysqli->query("SELECT gi.imagen_id, io.archivo, io.formato, iaa.peso, iaa.ancho, iaa.alto, iaa.peso_m, iaa.ancho_m, iaa.alto_m FROM galerias_imagenes gi, imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ${attr_k} WHERE gi.imagen_id = io.id AND gi.galeria_id = '{$valores[$attr_k]['int']}' AND gi.estado = 1 ORDER BY gi.orden");
			if($img = $cons_img->fetch_row())
			 {
			  $imgs = $doc->createElement('ul');
			  do
			   {
				$imagen_id = $img[0];
				$imagen = $doc->createElement('a');
				$imagen->setAttribute("href", $img[2]);
				$imagen->setAttribute("type", $imagenes.$img[1]);

				$imagen_m = $doc->createElement('img');
				$imagen_m->setAttribute("src", $miniaturas.$img[1]);
				$imagen_m->setAttribute("width", $img[7]);
				$imagen_m->setAttribute("height", $img[8]);
				$imagen_m = $imagen->appendChild($imagen_m);
				$imagen = $imgs->appendChild($imagen);
				if($this->subatributos[$attr_k])
				 {
				  foreach($this->subatributos[$attr_k] AS $subattr_k => $subattr_v)
				   {
					if($subattr_v['unico'] == 1)
					 {
					  // único
					  $subdato = $doc->createElement('dato');
					  if($subattr_v['tipo'] == "date") $valor['date'] = formato_fecha($subvalores[$subattr_k][$imagen_id][$subattr_v['tipo']], true, false);
					  else
					   {
						if(isset($subvalores[$subattr_k][$subattr_v['tipo']])) $valor = $subvalores[$subattr_k][$imagen_id];
						else $valor = $subvalores[$subattr_k][$imagen_id][$leng_id] ? $subvalores[$subattr_k][$imagen_id][$leng_id] : $subvalores[$subattr_k][$imagen_id][$this->leng_poromision];
					   }
					  if(empty($valor[$subattr_v['tipo']])) continue;
					  $subdato->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$subattr_v['tipo']])));
					 }
					$etiqueta = $subattr_v['etiquetas'][$leng_id] ? $subattr_v['etiquetas'][$leng_id] : $subattr_v['etiquetas'][$this->leng_poromision];
					$subdato = $dato->appendChild($subdato);
					$tipo = ($subattr_v['tipo'] == "string" && $subattr_v['subtipo'] == 1) ? "hex" : $tipos[$subattr_v['tipo']];
					$subdato->setAttribute("id", $subattr_v['identificador']);
					$subdato->setAttribute("tipo", $tipo);
					$subdato->setAttribute("etiqueta", $etiqueta);
					$subdato = $imagen->appendChild($subdato);
				   }
				 }
			   }while($img = $cons_img->fetch_row());
			  $imgs = $dato->appendChild($imgs);
			 }
			else continue;
		   }
		  elseif($attr_v['subtipo'] == 5)
	   	   {
			$dato = $doc->createElement('dato');
			if(!$cons_vista = $mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = ${leng_id} WHERE co.id = {$valores[$attr_k]['int']} LIMIT 1")) echo __LINE__." - ".$mysqli->error;
			if($fila_vista = $cons_vista->fetch_row())
			 {
			  $dato->appendChild($doc->createTextNode($fila_vista[1]));
			  $cons_vista->close();
			 }
		   }
		  elseif($attr_v['subtipo'] == 6)
		   {
		   	$dato = $doc->createElement('alineacion');
		   	if(!empty($valores[$attr_k]['int']))
			 {
			  $cons_img = $mysqli->query("SELECT id, codigo FROM subitems WHERE item_id = {$id} AND atributo_id = {$valores[$attr_k]['int']} ORDER BY codigo");
			  if($img = $cons_img->fetch_row())
			   {
			   	do
			   	 {
				  $el = $doc->createElement('elemento');
				  $el->setAttribute("xml:id", $img[0]);
				  $el->appendChild($doc->createTextNode($img[1]));
				  $el = $dato->appendChild($el);
				 }while($img = $cons_img->fetch_row());
			   }
			 }
		   }
		  elseif($attr_v['subtipo'] == 7)
		   {
		   	$dato = $doc->createElement('area');
			$subvalores = array();
			if(!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM subitems_valores iv WHERE iv.item_id = ${id} AND area_id = {$attr_k} ORDER BY iv.leng_id")) echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_assoc())
			 {
			  do
			   {
				$atributo_id = array_shift($fila_valores);
				$subleng_id = array_shift($fila_valores);
				if($subleng_id) $subvalores[$atributo_id][$subleng_id] = $fila_valores;
				else
				 {
				  if($this->subatributos[$attr_k][$atributo_id]['unico'] == 1) $subvalores[$atributo_id] = $fila_valores;
				  else $subvalores[$atributo_id][] = $fila_valores;
	 			 }
			   }while($fila_valores = $cons_valores->fetch_assoc());
			  $cons_valores->close();
			 }
			if(count($this->subatributos))
			 {
			foreach($this->subatributos[$attr_k] AS $subattr_k => $subattr_v)
			 {
			  if($subattr_v['tipo'] == "int")
			   {
			   	if($subattr_v['subtipo'] == 2)
				 {
				  $subdato = $doc->createElement('imagen');
				  if(!empty($subvalores[$subattr_k]['int']))
				   {
					$cons_img = $mysqli->query("SELECT formato, peso, archivo FROM imagenes WHERE id = {$subvalores[$subattr_k]['int']}");
					$img = $cons_img->fetch_row();
				   }
				  $subdato->setAttribute("mime", $img[0]);
				  $subdato->setAttribute("peso", $img[1]);
				  $subdato->setAttribute("archivo", "img/img/".urlencode($img[2]));
				 }
				else
				 {
				  $subdato = $doc->createElement('dato');
				  $subdato->appendChild($doc->createTextNode($subvalores[$subattr_k]['int']));
				 }
			   }
			  else
			   {
				if($subattr_v['unico'] == 1)
				 {
				  $subdato = $doc->createElement('dato');
				  if($subattr_v['tipo'] == "date") $valor['date'] = formato_fecha($subvalores[$subattr_k][$subattr_v['tipo']], true, false);
				  //formato_fecha($v, true);
				  else
				   {
					if(isset($valores[$subattr_k][$subattr_v['tipo']])) $valor = $subvalores[$subattr_k];
					else $valor = $subvalores[$subattr_k][$leng_id] ? $subvalores[$subattr_k][$leng_id] : $subvalores[$subattr_k][$this->leng_poromision];
				   }
				  if(empty($valor[$subattr_v['tipo']])) continue;

				  $subdato->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$subattr_v['tipo']])));
				 }
				else
				 {
				  $subdato = $doc->createElement('alineacion');
				  if(!count($subvalores[$subattr_k])) continue;
				  foreach($subvalores[$subattr_k] AS $k => $v)
				   {
					$dato1 = $doc->createElement('dato');
					if($subattr_v['tipo'] == "date") $valor[$k]['date'] = formato_fecha($subvalores[$subattr_k][$k][$subattr_v['tipo']], true, false);
					//formato_fecha($v, true);
					else $valor = $subvalores[$subattr_k][$k];
					$dato1->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$subattr_v['tipo']])));
					$subdato->appendChild($dato1);
				   }
				 }
			   }
			  $etiqueta = $subattr_v['etiquetas'][$leng_id] ? $subattr_v['etiquetas'][$leng_id] : $subattr_v['etiquetas'][$this->leng_poromision];
			  $subdato = $dato->appendChild($subdato);
			  $tipo = ($subattr_v['tipo'] == "string" && $subattr_v['subtipo'] == 1) ? "hex" : $tipos[$subattr_v['tipo']];

			  $subdato->setAttribute("id", $subattr_v['identificador']);
			  $subdato->setAttribute("tipo", $tipo);
			  $subdato->setAttribute("etiqueta", $etiqueta);
			  $subdato = $dato->appendChild($subdato);
			   }
			 }


		   }
		  elseif($attr_v['subtipo'] == 8)
		   {
		   	continue;
		   	//$dato = $doc->createElement('dato');
		   	//$dato->setAttribute("tipo", "entero");
		   }
		  else
		   {
		   	$dato = $doc->createElement('dato');
		   	$dato->setAttribute("tipo", "texto");
			$dato->appendChild($doc->createTextNode($valores[$attr_k]['int']));
		   }
		 }
		else
		 {
//$valores[$attr_k]['int']
		  $dato = $doc->createElement(($attr_v['et_xhtml'] ? $attr_v['et_xhtml'] : 'p'));
		  //$dato->setAttribute("tipo", $tipos[$attr_v['tipo']]);
		  if($attr_v['tipo'] == "date") $valor['date'] = formato_fecha($valores[$attr_k][$attr_v['tipo']], true, false);
		  //formato_fecha($v, true);
		  else
		   {
		   	if(isset($valores[$attr_k][$attr_v['tipo']])) $valor = $valores[$attr_k];
		   	else $valor = $valores[$attr_k][$leng_id] ? $valores[$attr_k][$leng_id] : $valores[$attr_k][$this->leng_poromision];
		   }

		  //$dato->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$attr_v['tipo']])));
		  $varray = explode("\r\n", $valor[$attr_v['tipo']]);
		  $va = 0;
		  if(count($varray) > 1)
		   {
		    for($va = 0; $va < count($varray)-1 ; $va++)
		     {
			  $dato->appendChild($doc->createTextNode($varray[$va]));
			  $dato->appendChild($doc->createElement('br'));
			 }
		   }
		  $dato->appendChild($doc->createTextNode($varray[$va]));
		 }
		$dato->setAttribute("class", $attr_v['identificador']);
		//$etiqueta = $attr_v['etiquetas'][$leng_id] ? $attr_v['etiquetas'][$leng_id] : $attr_v['etiquetas'][$this->leng_poromision];
		//$dato->setAttribute("etiqueta", $etiqueta);
		$dato = $root->appendChild($dato);
	   }

	  //$doc->saveHTMLFile(RUTA_CARPETA."public_html/seccion_xhtml/${identificador}.tpl.${leng_cod}");
	  //$doc->save(RUTA_CARPETA."public_html/seccion/${identificador}.xml.${leng_cod}");
	  file_put_contents(RUTA_CARPETA."public_html/seccion_xhtml/${identificador}.tpl.${leng_cod}", $doc->saveXML($root));
	  unset($doc);

	 }
   }
 }

?>