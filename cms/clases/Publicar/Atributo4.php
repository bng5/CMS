<?php

/**
 * Galería
 *
 * @author pablo
 */

class Publicar_Atributo4 extends Publicar_Atributo {

	public static function valor($leng_id, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		$mysqli = BaseDatos::Conectar();
		//$dato = $doc->createElement('galeria');
		//$dato->setAttribute("imagenes", "img/0/".$attr_k."/");
		//$dato->setAttribute("miniaturas", "img/1/".$attr_k."/");
		if(empty($valores['int']))
			return;

		/*
		$subvalores = array();
		if(!$cons_valores = $mysqli->query("SELECT atributo_id, imagen_id, leng_id, `string`, `date`, `text`, `int`, `num` FROM galerias_imagenes_valores WHERE galeria_id = ".$valores['int']))
			echo __LINE__." - ".$mysqli->error;
		if($fila_valores = $cons_valores->fetch_assoc()) {
			do {
				$atributo_id = array_shift($fila_valores);
				$imagen_id = array_shift($fila_valores);
				$subleng_id = array_shift($fila_valores);
				if($subleng_id)
					$subvalores[$atributo_id][$imagen_id][$subleng_id] = $fila_valores;
				else {
					if($this->subatributos[$attr_k][$atributo_id]['unico'] == 1)
						$subvalores[$atributo_id][$imagen_id] = $fila_valores;
					else
						$subvalores[$atributo_id][$imagen_id][] = $fila_valores;
				}
			}while($fila_valores = $cons_valores->fetch_assoc());
			$cons_valores->close();
		}
		*/
		$cons_img = $mysqli->query("SELECT gi.imagen_id, io.archivo, io.formato, iaa.peso, iaa.ancho, iaa.alto, iaa.peso_m, iaa.ancho_m, iaa.alto_m FROM galerias_imagenes gi, imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ".$attr_k." WHERE gi.galeria_id = '".$valores['int']."' AND gi.imagen_id = io.id AND gi.estado = 1 ORDER BY gi.orden");
		if($img = $cons_img->fetch_assoc()) {
			//$imgs = $doc->createElement('imagenes');
			$k = 0;
			do {
				$item[$attr_k]['imagenes'][$k]["mime"] = $img['formato'];
				$item[$attr_k]['imagenes'][$k]["peso"] = $img['peso'];
				$item[$attr_k]['imagenes'][$k]["ancho"] = $img['ancho'];
				$item[$attr_k]['imagenes'][$k]["alto"] = $img['alto'];
				$item[$attr_k]['imagenes'][$k]["archivo"] = urlencode($img['archivo']);
				$item[$attr_k]['imagenes'][$k]["miniatura"] = urlencode($img['archivo']);
				$item[$attr_k]['imagenes'][$k]["ancho_m"] = $img['ancho_m'];
				$item[$attr_k]['imagenes'][$k]["alto_m"] = $img['alto_m'];
				$item[$attr_k]['imagenes'][$k]["peso_m"] = $img['peso_m'];
				$k++;
				/*
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
				*/

				/*
				if($this->subatributos[$attr_k]) {
					foreach($this->subatributos[$attr_k] AS $subattr_k => $subattr_v) {
						if($subattr_v['unico'] == 1) {
							// único
							$subdato = $doc->createElement('dato');
							if($subattr_v['tipo'] == "date")
								$valor['date'] = formato_fecha($subvalores[$subattr_k][$imagen_id][$subattr_v['tipo']], true, false);
							else {
								if(isset($subvalores[$subattr_k][$subattr_v['tipo']]))
									$valor = $subvalores[$subattr_k][$imagen_id];
								else
									$valor = $subvalores[$subattr_k][$imagen_id][$leng_id] ? $subvalores[$subattr_k][$imagen_id][$leng_id] : $subvalores[$subattr_k][$imagen_id][$this->leng_poromision];
							}
							if(empty($valor[$subattr_v['tipo']]))
								return;
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
				*/
			}while($img = $cons_img->fetch_assoc());
			//$imgs = $dato->appendChild($imgs);
		}






return;


		//$almacenamiento = '';//Atributos::$almacenamiento[$attr_v['tipo_id']];

		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			// es lista, por ahora no me preocupo
		}
		elseif($attr_v['unico'] == 1) {
			$item['valores'][$attr_k] = $valores['date'];
		}
	}

}

?>