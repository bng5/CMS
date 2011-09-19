<?php

/**
 * BACKTRACE
 *	clase Item/Publicar.php
 */
class Publicar_Atributo {

	public static function crearComponente($tipo, $subtipo = null) {
/*
		$componente = '';
		switch($tipo) {
			case 'string':
			case 'text':
				$componente .= 'texto';
				break;
		}
echo "
".$componente."::valor()
";

		$tipo = "Publicacion_Atributo".$tipo;
		$componente = new $tipo;
		$componente->indice = self::$_indice++;
		return $componente;

//		return new $componente;
*/
	}


	/**
	 *
	 * @param int $atributo_id
	 * @param array $atributo
	 * @param array $item
	 * @param array $db_item
	 */
	public static function valor($leng_id, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		global $mysqli;
		//if($item[$attr_k] && $attr_v['unico'] != 2)
		//  continue;
		/*
		if($attr_v['tipo'] == "int") {
			// Dato externo
			if($attr_v['subtipo'] == 1) {
				if($attr_v['salida'] == 0)
					continue;
				$this->nodos[$attr_k] = '';//$doc->createElement('dato');
				$e_valor = $valores[$attr_k]['int'];
				if(!$cons_valores = $mysqli->query($attr_v['extra'].$leng_id." AND i.id = '".$valores[$attr_k]['int']."' LIMIT 1"))
					echo __LINE__." - ".$mysqli->error;
				if($fila_valores = $cons_valores->fetch_row()) {
					if(empty($valor_leng[$e_valor]))
						$valor_leng[$e_valor] = $fila_valores[1];
					//$this->nodos[$attr_k]->appendChild($doc->createTextNode($fila_valores[1]));
					$item[$attr_k]['id'] = $valores[$attr_k]['int'];
					$item[$attr_k]['desc'] = $fila_valores[1];
					$cons_valores->close();
				}
				$string_valor = $fila_valores[1] ? $fila_valores[1] : $valor_leng[$e_valor];
				continue;
			}
			// Imagen
			elseif($attr_v['subtipo'] == 2) {
				$this->nodos[$attr_k] = '';//$doc->createElement('imagen');
				if(!empty($valores[$attr_k]['int'])) {
					$cons_img = $mysqli->query("SELECT io.formato, iaa.peso, io.archivo, iaa.ancho, iaa.alto, iaa.ancho_m, iaa.alto_m, iaa.peso_m FROM imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ".$attr_k." WHERE io.id = ".$valores[$attr_k]['int']);
					$img[$attr_k] = $cons_img->fetch_row();
				}
				if(empty($img[$attr_k][2]))
					continue;
				$item[$attr_k]["mime"] = $img[$attr_k][0];
				$item[$attr_k]["peso"] = $img[$attr_k][1];
				$item[$attr_k]["ancho"] = $img[$attr_k][3];
				$item[$attr_k]["alto"] = $img[$attr_k][4];
				$item[$attr_k]["archivo"] = urlencode($img[$attr_k][2]);
				$item[$attr_k]["miniatura"] = urlencode($img[$attr_k][2]);
				$item[$attr_k]["ancho_m"] = $img[$attr_k][5];
				$item[$attr_k]["alto_m"] = $img[$attr_k][6];
				$item[$attr_k]["peso_m"] = $img[$attr_k][7];
				continue;
			}
			elseif($attr_v['subtipo'] == 3) {
				if($attr_v['unico'] == 1) {
					$this->nodos[$attr_k] = '';//$doc->createElement('archivo');
					$valor[$attr_k] = $valores[$attr_k]['int'];
				}
				else {
					$this->nodos[$attr_k] = '';//$doc->createElement('alineacion');
					if(!$acons[$attr_k]) {
						$acons[$attr_k] = array();
						if(is_array($valores[$attr_k])) {
							foreach($valores[$attr_k] AS $nu_v)
								$acons[$attr_k][] = $nu_v['int'];
						}
						if(count($acons[$attr_k]))
							$valor[$attr_k] = implode("' OR id = '", $acons[$attr_k]);
					}
				}
				if(empty($valor[$attr_k])) {
					continue;
				}
				else {
					$cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = '".$valor[$attr_k]."'");
					if($img = $cons_img->fetch_row()) {
						if($attr_v['unico'] == 1) {
							$item[$attr_k]["mime"] = $img[0];
							$item[$attr_k]["peso"] = $img[1];
							$item[$attr_k]["archivo"] = urlencode($img[2]);
							continue;
						}
						else {
							do {
								$aldato = $doc->createElement('archivo');
								$item[$attr_k][] = array("mime" => $img[0], "peso" => $img[1], "archivo" => urlencode($img[2]));
							}while($img = $cons_img->fetch_row());
						}
					}
					else {
						continue;
					}
				}
			}

			// Galería de imágenes
			elseif($attr_v['subtipo'] == 4) {
				$this->nodos[$attr_k] = '';//$doc->createElement('galeria');
				//$this->nodos[$attr_k]->setAttribute("imagenes", "img/0/{$attr_k}/");
				//$this->nodos[$attr_k]->setAttribute("miniaturas", "img/1/{$attr_k}/");
				if(empty($valores[$attr_k]['int']))
					continue;

				$cons_img = $mysqli->query("SELECT gi.imagen_id, io.archivo, io.formato, iaa.peso, iaa.ancho, iaa.alto, iaa.peso_m, iaa.ancho_m, iaa.alto_m FROM galerias_imagenes gi, imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ".$attr_k." WHERE gi.galeria_id = '".$valores[$attr_k]['int']."' AND gi.imagen_id = io.id AND gi.estado = 1 ORDER BY gi.orden");
				if($img = $cons_img->fetch_assoc()) {
					//$imgs = $doc->createElement('imagenes');
					do {
						$item[$attr_k][] = $img;//array();
						continue;
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
					}while($img = $cons_img->fetch_assoc());
					//$imgs = $this->nodos[$attr_k]->appendChild($imgs);
				}
				continue;
			}

			// etiquetas
			elseif($attr_v['subtipo'] == 10) {
				if($this->listado[$attr_k])
					$a_sqlite['int__'.$attr_v['identificador']] = 0;
				if($attr_v['salida'] == 0)
					continue;
				$this->nodos[$attr_k] = '';//$doc->createElement('dato');
				$ets_arr = array();
				$ets = $mysqli->query("SELECT co.id, co.texto FROM campos_opciones_sel c LEFT JOIN campos_opciones_textos co ON c.opcion_id = co.id WHERE c.item_id = {$id} AND c.campo_id = {$attr_k} ORDER BY co.texto");
				if($et_fila = $ets->fetch_row()) {
					do {
						$ets_arr[$et_fila[0]] = $et_fila[1];
					}while($et_fila = $ets->fetch_row());
				}
				$item[$attr_k] = $ets_arr;
				continue;
			}
		}
		elseif($attr_v['tipo'] == "text") {
			// enlace externo
			if($attr_v['subtipo'] == 1) {
				$item[$attr_k] = $this->enlaces_protocolos[$valores[$attr_k]['int']].$valores[$attr_k]['text'];
				continue;
			}
		}
		*/
		// salida pura
		if($attr_v['unico'] == 0) {
			if($valores[$attr_k]) {
				foreach($valores[$attr_k] AS $attr_valores)
					$item[$attr_k][] = $attr_valores[$attr_v['tipo']];
			}
		}
		elseif($attr_v['unico'] == 1) {
			if($valores[$attr_k][$attr_v['tipo']] && $attr_v['formato']) {
				$item[$attr_k] = WikiTexto::parse($valores[$attr_k][$attr_v['tipo']]);
			}
			else
				$item[$attr_k] = $valores[$attr_k][$attr_v['tipo']];
		}
		elseif($attr_v['unico'] == 2) {
			if($valores[$attr_k][$leng_id][$attr_v['tipo']] && $attr_v['formato']) {
				$item[$attr_k] = WikiTexto::parse($valores[$attr_k][$leng_id][$attr_v['tipo']]);
			}
			else {
				$item[$attr_k] = $valores[$attr_k][$leng_id][$attr_v['tipo']];
			}
		}
	}





}

?>