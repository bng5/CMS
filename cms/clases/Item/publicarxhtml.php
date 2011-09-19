<?php

class Item_publicarxhtml
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

	$this->atributos = array();
	$this->subatributos = array();
	$this->listado = array();
	$this->strc_sqlite = array();
	$this->enlaces_protocolos = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");

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

	if(!$cons_attrs = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, at.tipo, at.subtipo, isaa.en_listado, ia.extra, isaa.salida, ia.unico, ia.et_xhtml FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, items_secciones_a_atributos isaa, atributos_tipos at WHERE at.id = ia.tipo_id AND ia.id = isaa.atributo_id AND isaa.seccion_id = {$this->seccion_id} ORDER BY isaa.orden")) echo __LINE__." - ".$mysqli->error;
	if($fila_attrs = $cons_attrs->fetch_assoc())
	 {
	  do
	   {
		$atributo_id = array_shift($fila_attrs);
		$leng_id = array_shift($fila_attrs);
		$etiqueta = array_shift($fila_attrs);
		if(!$this->atributos[$atributo_id]) $this->atributos[$atributo_id] = $fila_attrs;
		$this->atributos[$atributo_id]['etiquetas'][$leng_id] = $etiqueta;
		if($fila_attrs['en_listado'] == 1 && !$this->listado[$atributo_id])
		 {
		  $this->listado[$atributo_id] = $atributo_id;
		  $s_pref = $fila_attrs['tipo'];
		  $s_tipo = "VARCHAR(200)";
		  if($fila_attrs['tipo'] == "date")
		   {
		   	$s_tipo = ($fila_attrs['subtipo'] == 1) ? "date" : "datetime";
			if($fila_attrs['salida'] == 1)
			 {
			  $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` ${s_tipo}";
			  $s_pref = "string";
			  $s_tipo = "VARCHAR(200)";
			 }
		   }
		  elseif($fila_attrs['tipo'] == "int")
		   {
		   	if($fila_attrs['subtipo'] == 2) $s_pref = "img";//__{$atributo_id}
		   	elseif($fila_attrs['subtipo'] == 3) $s_pref = "arch";
		   	else
		   	 {
			  if($fila_attrs['salida'] == 1)
			   {
				$this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` integer";
				$s_pref = "string";
			   }
			  else
			   {
				$s_tipo = "integer";
			   }
		   	 }
		   }
		  elseif($fila_attrs['tipo'] == "num")
		   {
			if($fila_attrs['salida'] == 1)
			 {
			  $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` DECIMAL(15,2)";
			  $s_pref = "string";
			 }
			else
			 {
			  $s_tipo = "DECIMAL(15,2)";
			 }
		   }
		  elseif($fila_attrs['tipo'] == "text")
		   {
		   	$s_tipo = "TEXT";
		   	if($fila_attrs['subtipo'] == 1) $s_pref = "link";
		   }
		  $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` {$s_tipo}";
		 }
		// si es área (o galería)
		elseif($fila_attrs['subtipo'] == 4 || $fila_attrs['subtipo'] == 7)
		 {
/*********************************************************************/
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
/*********************************************************************/

		 }
       }while($fila_attrs = $cons_attrs->fetch_assoc());
	  $cons_attrs->close();
	 }

	//if($this->sqlite = new SQLiteDatabase("../menuXml/{$this->seccion}.sqlite", 0666, $sqlite_error))
	// {
	//echo "create table {$this->seccion} (id integer, leng_cod varchar(3), creado datetime, modificado datetime, orden integer, ".implode(", ", $this->strc_sqlite).")\n<br />";
	//if(@$this->sqlite->queryExec("create table seccion (leng_cod varchar(3), nombre varchar(25))"))
	// {
	if(!$cons_etseccion = $mysqli->query("SELECT leng_id, titulo FROM secciones_nombres WHERE id = ${seccion_id}")) echo __LINE__." - ".$mysqli->error;
	if($fila_etseccion = $cons_etseccion->fetch_row())
	 {
	  do
	   {
		$etsecccion[$fila_etseccion[0]] = $fila_etseccion[1];
	   }while($fila_etseccion = $cons_etseccion->fetch_row());
	  $cons_etseccion->close();
	  foreach($this->lengs AS $leng_k => $leng_v)
	   {
		$et = $etsecccion[$leng_k] ? $etsecccion[$leng_k] : $etsecccion[$this->leng_poromision];
		//$this->sqlite->queryExec("insert into seccion VALUES ('${leng_v}', '${et}')");
	   }
	 }
	// }

	//@$this->sqlite->queryExec("create table {$this->seccion} (id integer, leng_cod varchar(3), creado datetime, modificado datetime, orden integer, ".implode(", ", $this->strc_sqlite).")");
	//@$this->sqlite->queryExec("CREATE VIEW ver_{$this->seccion} AS SELECT * FROM {$this->seccion} ORDER BY 4 ASC");
	//@$this->sqlite->queryExec("CREATE VIEW ver_{$this->seccion}_leng AS SELECT * FROM {$this->seccion} WHERE leng_cod = '{$this->lengs[$this->leng_poromision]}' ORDER BY 4 ASC");
	// }
	//else die($sqlite_error);

	$los_campos = count($this->strc_sqlite) ? implode(" DEFAULT NULL,\n ", $this->strc_sqlite)." DEFAULT NULL,\n ": "";
	$mysqli->query("CREATE TABLE `pub__{$this->seccion}` (
 `id` INT UNSIGNED NOT NULL,
 `leng_cod` VARCHAR(3)  CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
 `creado` DATETIME  NOT NULL,
 `modificado` DATETIME  NOT NULL,
 `orden` TINYINT UNSIGNED DEFAULT NULL,
 ${los_campos}PRIMARY KEY(`id`, `leng_cod`)
)
ENGINE = MYISAM
CHARACTER SET utf8 COLLATE utf8_general_ci;");
//print_r($this->subatributos);
   }

  function Item($id)
   {
	global $mysqli;
	if(!$cons_item = $mysqli->query("SELECT f_creado, f_modificado, orden FROM items WHERE id = ${id} LIMIT 1")) echo __LINE__." - ".$mysqli->error;
	if($fila_item = $cons_item->fetch_row())
	 {
	  $a_sqlite_base = array('creado' => "'{$fila_item[0]}'", 'modificado' => "'{$fila_item[1]}'", 'orden' => ($fila_item[2] ? $fila_item[2] : 'NULL'));
	  $cons_item->close();
	 }
	else
	 {
	  echo "No se encontró el item";
	  exit;
	 }

	$valores = array();
	if(!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM items_valores iv WHERE iv.item_id = ${id} ORDER BY iv.leng_id")) echo __LINE__." - ".$mysqli->error;
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

	$valor_leng = array();
	$tipos = array('string' => 'texto', 'int' => 'texto', 'text' => 'areadetexto', 'date' => 'texto');
	foreach($this->lengs AS $leng_id => $leng_cod)
	 {
	  @include(RUTA_CARPETA."leng/textos.".$leng_cod);
	  $doc = new DOMDocument('1.0', 'utf-8');
	  //$doc->formatOutput = true;
	  /*
	  $root = $doc->createElement('div');
	  $root->setAttribute("xml:lang", $leng_cod);
	  $root = $doc->appendChild($root);
	  */
	  $item = $doc->createElement('div');
	  //$item->setAttribute("xml:id", $id);
	  $item = $doc->appendChild($item);
	  $a_sqlite = $a_sqlite_base;
	  foreach($this->atributos AS $attr_k => $attr_v)
	   {
	   	//$valor = null;
	   	if($attr_v['tipo'] == "int")
	   	 {
	   	  if($attr_v['subtipo'] == 1)
	   	   {
	   	   	if($this->listado[$attr_k]) $a_sqlite['int__'.$attr_v['identificador']] = $valores[$attr_k]['int'] ? $valores[$attr_k]['int'] : 'NULL';
	   	   	if($attr_v['salida'] == 0) continue;
			$dato = $doc->createElement('dato');
			$dato->setAttribute("tipo", "texto");
			$e_valor = $valores[$attr_k]['int'];
			if(!$cons_valores = $mysqli->query($attr_v['extra'].$leng_id." AND i.id = '{$valores[$attr_k]['int']}' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_row())
			 {
			  if(empty($valor_leng[$e_valor])) $valor_leng[$e_valor] = $fila_valores[1];
			  $dato->appendChild($doc->createTextNode($fila_valores[1]));
			  $cons_valores->close();
			 }
			$string_valor = $fila_valores[1] ? $fila_valores[1] : $valor_leng[$e_valor];
			if($this->listado[$attr_k]) $a_sqlite['string__'.$attr_v['identificador']] = $string_valor ? "'".$string_valor."'" : 'NULL';
			if(empty($valores[$attr_k]['int'])) continue;
		   }
	   	  elseif($attr_v['subtipo'] == 2)
	   	   {
			$dato = $doc->createElement('img');
			if(!empty($valores[$attr_k]['int']))
			 {
			  $cons_img = $mysqli->query("SELECT io.formato, iaa.peso, io.archivo, iaa.ancho, iaa.alto, iaa.ancho_m, iaa.alto_m, iaa.peso_m FROM imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ${attr_k} WHERE io.id = {$valores[$attr_k]['int']}");
			  $img[$attr_k] = $cons_img->fetch_row();
			 }
			if($this->listado[$attr_k]) $a_sqlite['img__'.$attr_v['identificador']] = $img[$attr_k] ? "'${attr_k}/".urlencode($img[$attr_k][2]).",{$img[$attr_k][0]},{$img[$attr_k][1]},{$img[$attr_k][3]},{$img[$attr_k][4]},{$img[$attr_k][5]},{$img[$attr_k][6]},{$img[$attr_k][7]}'" : 'NULL';
			if(empty($img[$attr_k][2])) continue;
			//$dato->setAttribute("mime", $img[$attr_k][0]);
			//$dato->setAttribute("peso", $img[$attr_k][1]);
			$dato->setAttribute("src", "/img/0/${attr_k}/".urlencode($img[$attr_k][2]));
			$dato->setAttribute("width", $img[$attr_k][3]);
			$dato->setAttribute("height", $img[$attr_k][4]);
			$dato->setAttribute("alt", "");
			//$dato->setAttribute("miniatura", "img/1/${attr_k}/".urlencode($img[$attr_k][2]));
			//$dato->setAttribute("ancho_m", $img[$attr_k][5]);
			//$dato->setAttribute("alto_m", $img[$attr_k][6]);
			//$dato->setAttribute("peso_m", $img[$attr_k][7]);
		   }
		  elseif($attr_v['subtipo'] == 3)
		   {
/*********************************************************************/

		   	if($attr_v['unico'] == 1)
		   	 {
			  $dato = $doc->createElement('archivo');
			  $valor[$attr_k] = $valores[$attr_k]['int'];
		   	 }
		   	else
		   	 {
			  $dato = $doc->createElement('alineacion');
			  if(!$acons[$attr_k])
			   {
				$acons[$attr_k] = array();
				if(is_array($valores[$attr_k])) foreach($valores[$attr_k] AS $nu_v) $acons[$attr_k][] = $nu_v['int'];
				if(count($acons[$attr_k])) $valor[$attr_k] = implode("' OR id = '", $acons[$attr_k]);
			   }
		   	 }
		   	if(empty($valor[$attr_k]))
		   	 {
		   	  if($this->listado[$attr_k]) $a_sqlite['arch__'.$attr_v['identificador']] = 'NULL';
		   	  continue;
			 }
		   	else
			 {
			  $cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = '{$valor[$attr_k]}'");
			  if($img = $cons_img->fetch_row())
			   {
			   	if($this->listado[$attr_k]) $a_sqlite['arch__'.$attr_v['identificador']] = "'".urlencode($img[2]).",{$img[0]},{$img[1]}'";
				if($attr_v['unico'] == 1)
				 {
				  $dato->setAttribute("mime", $img[0]);
				  $dato->setAttribute("peso", $img[1]);
				  $dato->setAttribute("archivo", "archivos/".urlencode($img[2]));
				 }
				else
				 {
				  do
				   {
				   	$aldato = $doc->createElement('archivo');
					$aldato->setAttribute("mime", $img[0]);
					$aldato->setAttribute("peso", $img[1]);
					$aldato->setAttribute("archivo", "archivos/".urlencode($img[2]));
					$aldato = $dato->appendChild($aldato);
				   }while($img = $cons_img->fetch_row());
				 }
			   }
			  else
			   {
			   	if($this->listado[$attr_k]) $a_sqlite[$attr_v['identificador']] = "";
			   	continue;
			   }
			 }

/**********************************************************************
		   	$dato = $doc->createElement('archivo');
		   	if(!empty($valores[$attr_k]['int']))
			 {
			  $cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = {$valores[$attr_k]['int']}");
			  $img = $cons_img->fetch_row();
			 }
			if($this->listado[$attr_k]) $a_sqlite[] = $img ? urlencode($img[2]).",{$img[0]},{$img[1]}" : "";
			if(empty($img[2])) continue;
			$dato->setAttribute("mime", $img[0]);
			$dato->setAttribute("peso", $img[1]);
			$dato->setAttribute("archivo", "archivos/".urlencode($img[2]));
*/
		   }
		  elseif($attr_v['subtipo'] == 4)
		   {
		   	$dato = $doc->createElement('galeria');
		   	$dato->setAttribute("imagenes", "img/0/${attr_k}/");
		   	$dato->setAttribute("miniaturas", "img/1/${attr_k}/");
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
			$cons_img = $mysqli->query("SELECT gi.imagen_id, io.archivo, io.formato, iaa.peso, iaa.ancho, iaa.alto, iaa.peso_m, iaa.ancho_m, iaa.alto_m FROM galerias_imagenes gi, imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ${attr_k} WHERE gi.galeria_id = '{$valores[$attr_k]['int']}' AND gi.imagen_id = io.id AND gi.estado = 1 ORDER BY gi.orden");
			if($img = $cons_img->fetch_row())
			 {
			  $imgs = $doc->createElement('imagenes');
			  do
			   {
				$imagen_id = $img[0];
				$imagen = $doc->createElement('imagen');
				$imagen->setAttribute("mime", $img[2]);
				$imagen->setAttribute("peso", $img[3]);
				$imagen->setAttribute("archivo", $img[1]);
				$imagen->setAttribute("ancho", $img[4]);
				$imagen->setAttribute("alto", $img[5]);
				$imagen->setAttribute("peso_m", $img[6]);
				$imagen->setAttribute("ancho_m", $img[7]);
				$imagen->setAttribute("alto_m", $img[8]);
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

		   	/*********************************************************/

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
			   	/*
				$subdato = $doc->createElement('dato');

				if($attr_v['tipo'] == "date") $valor['date'] = formato_fecha($subvalores[$subattr_k][$subattr_v['tipo']], true, false);
				//formato_fecha($v, true);
				else
				 {
				  if(isset($subvalores[$subattr_k][$subattr_v['tipo']])) $valor = $subvalores[$subattr_k];
				  else $valor = $subvalores[$subattr_k][$leng_id] ? $subvalores[$subattr_k][$leng_id] : $subvalores[$subattr_k][$this->leng_poromision];
				 }
				$subdato->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$subattr_v['tipo']])));
				*/
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
			  //$etiqueta = $subattr_v['etiquetas'][$leng_id] ? $subattr_v['etiquetas'][$leng_id] : $subattr_v['etiquetas'][$this->leng_poromision];
			  //$etiqueta = $subattr_v['nombre'];//[$leng_id] ? $subattr_v['nombre'][$leng_id] : $subattr_v['nombre'][$this->leng_poromision];
			  $etiqueta = $subattr_v['etiquetas'][$leng_id] ? $subattr_v['etiquetas'][$leng_id] : $subattr_v['etiquetas'][$this->leng_poromision];
			  $subdato = $dato->appendChild($subdato);
			  $tipo = ($subattr_v['tipo'] == "string" && $subattr_v['subtipo'] == 1) ? "hex" : $tipos[$subattr_v['tipo']];

			  $subdato->setAttribute("id", $subattr_v['identificador']);
			  $subdato->setAttribute("tipo", $tipo);
			  $subdato->setAttribute("etiqueta", $etiqueta);
			  $subdato = $dato->appendChild($subdato);
			   }
			 }
		   	/*********************************************************/
		   	/*if(!empty($valores[$attr_k]['int']))
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
			 }*/
		   }
		  elseif($attr_v['subtipo'] == 8)
		   {
			$radio_valor = $valores[$attr_k]['int'] ? $valores[$attr_k]['int'] : 0;
			if($this->listado[$attr_k]) $a_sqlite['int__'.$attr_v['identificador']] = $radio_valor;
			if($attr_v['salida'] == 0) continue;
			else// continue;
			 {
			  eval('$opc = '.$attr_v['extra'].';');
		   	  $radio_valor_txt = $opc[$radio_valor];
			  if($this->listado[$attr_k]) $a_sqlite['string__'.$attr_v['identificador']] = $radio_valor_txt ? $radio_valor_txt : 'NULL';
			  $dato = $doc->createElement('dato');
			  $dato->appendChild($doc->createTextNode($radio_valor_txt));
			  $dato->setAttribute("tipo", "texto");
			 }
		   }
		  else
		   {
		   	if($this->listado[$attr_k]) $a_sqlite['int__'.$attr_v['identificador']] = $valores[$attr_k]['int'];// ? $valores[$attr_k]['int'] : 0;
			if($attr_v['salida'] == 0) continue;
			if($this->listado[$attr_k]) $a_sqlite['strng__'.$attr_v['identificador']] = $valores[$attr_k]['int'];
			$dato = $doc->createElement('dato');
			$dato->appendChild($doc->createTextNode($valores[$attr_k]['int']));
		   }
		 }

		elseif($attr_v['tipo'] == "num")
	   	 {
		  if($this->listado[$attr_k]) $a_sqlite['num_'.$attr_v['identificador']] = $valores[$attr_k]['num'] ? $valores[$attr_k]['num'] : 'NULL';
		  if($attr_v['salida'] == 0) continue;
/*
		  if($attr_v['salida'] == 0 || empty($valores[$attr_k]['num']))
		   {

		   }
*/
	   	  if(empty($attr_v['subtipo']))
	   	   {
	   	   	if($this->listado[$attr_k] && $attr_v['salida'] == 1) $a_sqlite['string__'.$attr_v['identificador']] = "NULL";
	   	   	if(empty($valores[$attr_k]['num']))
				continue;
			if(!$cons_moneda = $mysqli->query("SELECT simbolo_izq, simbolo_der, decimales, sep_decimales, sep_miles FROM monedas WHERE id = {$attr_v['extra']} LIMIT 1")) echo __LINE__." - ".$mysqli->error;
			if($fila_moneda = $cons_moneda->fetch_assoc())
			 {
			  $simbolo_izq = $fila_moneda['simbolo_izq'] ? $fila_moneda['simbolo_izq']." " : "";
			  $simbolo_der = $fila_moneda['simbolo_der'] ? " ".$fila_moneda['simbolo_der'] : "";
			  $decimales = $fila_moneda['decimales'];
			  $sep_decimales = $fila_moneda['sep_decimales'];
			  $sep_miles = $fila_moneda['sep_miles'];
			  $cons_moneda->close();
			 }
			else
			 {
			  $simbolo_izq = '';
			  $decimales = 2;
			  $sep_decimales = '.';
			  $sep_miles = '';
			 }
			$valor = $simbolo_izq.number_format($valores[$attr_k]['num'], $decimales, $sep_decimales, $sep_miles).$simbolo_der;
			if($this->listado[$attr_k]) $a_sqlite['string__'.$attr_v['identificador']] = "'".addslashes($valor)."'";
		   }
		  else $valor = $valores[$attr_k]['num'];
		  if(empty($valores[$attr_k]['num']))
			continue;
		  $dato = $doc->createElement('dato');
		  $dato->appendChild($doc->createTextNode($valor));
		 }

		else
		 {
		  if($attr_v['unico'] == 1)
		   {
			$dato = $doc->createElement(($attr_v['et_xhtml'] ? $attr_v['et_xhtml'] : 'p'));
			if($attr_v['tipo'] == "date")
			 {
			  $dato->setAttribute("valor", $valores[$attr_k][$attr_v['tipo']]);
			  // $valor = array('date' => formato_fecha($valores[$attr_k][$attr_v['tipo']], true, false));
			  if($this->listado[$attr_k]) $a_sqlite['date__'.$attr_v['identificador']] = $valores[$attr_k][$attr_v['tipo']] ? "'".$valores[$attr_k][$attr_v['tipo']]."'" : 'NULL';
			  if($attr_v['salida'] == 0) continue;
			  if(empty($valores[$attr_k]['date']))
			   {
				$a_sqlite['string__'.$attr_v['identificador']] = 'NULL';
				continue;
			   }
			  if(!$fecha[$attr_k]) $fecha[$attr_k] = new Fecha($valores[$attr_k][$attr_v['tipo']]);
			  $fecha_s = $fecha[$attr_k]->Formatear($formato_fecha[$attr_v['extra']], $texto);
			  $valor = array('date' => ($fecha_s ? $fecha_s : $valores[$attr_k][$attr_v['tipo']]));
			 }
			else
			 {
			  if(isset($valores[$attr_k][$attr_v['tipo']])) $valor = $valores[$attr_k];
		   	  else $valor = $valores[$attr_k][$leng_id] ? $valores[$attr_k][$leng_id] : $valores[$attr_k][$this->leng_poromision];
		   	  if($attr_v['tipo'] == "text" && $attr_v['subtipo'] == 1) $valor['text'] = $this->enlaces_protocolos[$valores[$attr_k]['int']].$valor['text'];
		   	  //if()
		     }
		    if($this->listado[$attr_k]) $a_sqlite['string__'.$attr_v['identificador']] = $valor[$attr_v['tipo']] ? "'".addslashes($valor[$attr_v['tipo']])."'" : 'NULL';
		    if(empty($valor[$attr_v['tipo']])) continue;

$varray = explode("\r\n", $valor[$attr_v['tipo']]);
$va = 0;
if(count($varray) > 1)
 {
  for($va = 0; $va < count($varray)-1 ; $va++)
   {
 $dato->appendChild($doc->createTextNode($varray[$va]));
 //$dato->setAttribute("class", $attr_v['identificador']);
 //$item->appendChild($dato);
 $dato->appendChild($doc->createElement('br'));
 //$dato = $doc->createElement(($attr_v['et_xhtml'] ? $attr_v['et_xhtml'] : 'p'));

   }
 }
			$dato->appendChild($doc->createTextNode($varray[$va]));

//$dato->appendChild($doc->createTextNode(nl2br($valor[$attr_v['tipo']])));

		   }
		  else
		   {
			$dato = $doc->createElement('alineacion');
			if(!count($valores[$attr_k])) continue;
			foreach($valores[$attr_k] AS $k => $v)
			 {
			  $dato1 = $doc->createElement('dato');
			  if($attr_v['tipo'] == "date") $valor[$k]['date'] = formato_fecha($valores[$attr_k][$k][$attr_v['tipo']], true, false);
			  //formato_fecha($v, true);
			  else $valor = $valores[$attr_k][$k];
		      $dato1->appendChild($doc->createTextNode(str_replace("\r", "", $valor[$attr_v['tipo']])));
		      $dato->appendChild($dato1);
		     }
		   }
		 }
		if($attr_v['tipo'] == "string" && $attr_v['subtipo'] == 1) $tipo = "hex";
		elseif($attr_v['tipo'] == "text" && $attr_v['subtipo'] == 1) $tipo = "enlaceexterno";
		else $tipo = $tipos[$attr_v['tipo']];

		$etiqueta = $attr_v['etiquetas'][$leng_id] ? $attr_v['etiquetas'][$leng_id] : $attr_v['etiquetas'][$this->leng_poromision];
//		if($attr_v['unico'] == 0)
//		 {
//		  $alineacion = $doc->createElement('alineacion');
//		  $alineacion->setAttribute("id", $attr_v['identificador']);
//		  $alineacion->setAttribute("tipo", $tipo);
//		  $alineacion->setAttribute("etiqueta", $etiqueta);
//		  $dato = $alineacion->appendChild($dato);
//		  $alineacion = $item->appendChild($alineacion);
//		 }
//		else
//		 {
		  $dato->setAttribute("class", $attr_v['identificador']);
		  //$dato->setAttribute("tipo", $tipo);
		  //$dato->setAttribute("etiqueta", $etiqueta);
		  $dato = $item->appendChild($dato);
//		 }
/*
		$dato->setAttribute("tipo", $tipos[$attr_v['tipo']]);
		$dato->setAttribute("id", $attr_v['identificador']);
		$dato->setAttribute("etiqueta", $etiqueta);
		$dato = $item->appendChild($dato);
*/
	   }

	  //$doc->save(RUTA_CARPETA."public_html/item/${id}.xml.${leng_cod}");
	  file_put_contents(RUTA_CARPETA."public_html/item_xhtml/${id}.tpl.${leng_cod}", $doc->saveXML($item));
	  unset($doc);

	  //@$this->sqlite->queryExec("DELETE FROM {$this->seccion} WHERE id = ${id} AND leng_cod = '${leng_cod}'");
	  //$this->sqlite->queryExec("INSERT INTO {$this->seccion} VALUES (${id}, '${leng_cod}', '".implode("','", $a_sqlite)."')");
@$mysqli->query("DELETE FROM `pub__{$this->seccion}` WHERE id = ${id} AND leng_cod = '${leng_cod}'");

if($_SESSION['usuario'] == "etdp")
 {
  echo "<pre>\n".htmlspecialchars(var_export($this->atributos, true))."\n".htmlspecialchars(var_export($a_sqlite, true))."\n</pre>
  ".htmlspecialchars("INSERT INTO `pub__{$this->seccion}` VALUES (${id}, '${leng_cod}', ".implode(",", $a_sqlite).")")."\n";
 }

$mysqli->query("INSERT INTO `pub__{$this->seccion}` VALUES (${id}, '${leng_cod}', ".implode(",", $a_sqlite).")");

//echo $mysqli->error;
	 }
	$this->modificadas++;
   }
 }
?>