<?php

/**
 *
 * @deprecated
 */

class Seccion_publicar extends Publicar {
	
	function __construct($seccion) {
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
		$this->enlaces_protocolos = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");

		if($lenguajes = Idiomas::Listado(null, $params = array('estado' => 1))) {
			foreach($lenguajes->getIterator() AS $item) {
				$this->lengs[$item->id] = $item->codigo;
				if($this->leng_poromision == false)
					$this->leng_poromision = $item->id;
			}
		}


		if(!$cons_attrs = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, ia.tipo_id, ia.extra, isaa.salida, ia.unico FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, secciones_a_atributos isaa WHERE ia.id = isaa.atributo_id AND isaa.seccion_id = ".$this->seccion_id." ORDER BY isaa.orden"))
			echo __LINE__." - ".$mysqli->error;
		if($fila_attrs = $cons_attrs->fetch_assoc()) {
			do {
				$atributo_id = array_shift($fila_attrs);
				$leng_id = array_shift($fila_attrs);
				$etiqueta = array_shift($fila_attrs);
				if(!$this->atributos[$atributo_id])
					$this->atributos[$atributo_id] = $fila_attrs;
				$this->atributos[$atributo_id]['etiquetas'][$leng_id] = $etiqueta;
				if($fila_attrs['tipo'] == "int") {
					//elseif($fila_attrs['tipo'] == "text") $s_tipo = "text";
				}
			}while($fila_attrs = $cons_attrs->fetch_assoc());
			$cons_attrs->close();
		}
	}
	
	
	
	
	
	function Item($id) {
		$mysqli = BaseDatos::Conectar();
		$seccion_nombre = array();
		if(!$cons_item = $mysqli->query("SELECT sn.leng_id, sn.titulo, s.identificador FROM secciones_nombres sn JOIN secciones s ON sn.id = s.id WHERE s.id = {$id} AND sn.titulo != ''"))
			echo __LINE__." - ".$mysqli->error;
		if($fila_item = $cons_item->fetch_row()) {
			$identificador = $fila_item[2];
			do {
				$seccion_nombre[$fila_item[0]] = $fila_item[1];
			}while($fila_item = $cons_item->fetch_row());
			$cons_item->close();
		}
		else {
			echo "No se encontró el item";
			exit;
		}
		$valores = array();
		if(!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM secciones_valores iv WHERE iv.item_id = {$id} ORDER BY iv.leng_id"))
			echo __LINE__." - ".$mysqli->error;
		if($fila_valores = $cons_valores->fetch_assoc()) {
			do {


//print_r($fila_valores);
				$atributo_id = array_shift($fila_valores);
				$leng_id = array_shift($fila_valores);
				//$leng_id = $fila_valores['leng_id'];
				if($this->atributos[$atributo_id]['unico'] == 0)
					$valores[$atributo_id][] = $fila_valores;
				elseif($this->atributos[$atributo_id]['unico'] == 1)
					$valores[$atributo_id] = $fila_valores;
				else
				$valores[$atributo_id][$leng_id] = $fila_valores;
			}while($fila_valores = $cons_valores->fetch_assoc());
		$cons_valores->close();
		}

		$item = array();
		$tipos = array('string' => 'texto', 'num' => 'texto', 'text' => 'areadetexto', 'date' => 'texto');
		foreach($this->lengs AS $leng_id => $leng_cod) {
			$leng->id = $leng_id;


			$this->acondicionar($leng, $valores, $item);

			file_put_contents(RUTA_CARPETA.'bng5/datos/seccion/'.$id.'.'.$leng_cod.'.php', "<?php\nreturn ".var_export($item, true).";\n?>");
			//file_put_contents(RUTA_CARPETA."cms2/datos/seccion/{$this->seccion_id}.php", "asd -- ".var_export($doc, true));
			//$doc->save("../seccion/{$identificador}.xml.{$leng_cod}");
			//unset($doc);
		}

	}

}
