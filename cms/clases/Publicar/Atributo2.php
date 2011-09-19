<?php

/**
 * Imagen
 *
 * @author pablo
 */
class Publicar_Atributo2 extends Publicar_Atributo {

	public static function valor($leng_id, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		$mysqli = BaseDatos::Conectar();
		///$this->nodos[$attr_k] = '';//$doc->createElement('imagen');
		if(!empty($valores['int'])) {
			$cons_img = $mysqli->query("SELECT io.formato, iaa.peso, io.archivo, iaa.ancho, iaa.alto, iaa.ancho_m, iaa.alto_m, iaa.peso_m FROM imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ".$attr_k." WHERE io.id = ".$valores['int']);
			$img[$attr_k] = $cons_img->fetch_row();
		}
		if($attr_v['en_listado'])
			$db_item['img__'.$attr_v['identificador']] = $img[$attr_k] ? "'".serialize(array($attr_k, urlencode($img[$attr_k][2]), $img[$attr_k][0], $img[$attr_k][1], $img[$attr_k][3], $img[$attr_k][4], $img[$attr_k][5], $img[$attr_k][6], $img[$attr_k][7]))."'" : 'NULL';
		if(empty($img[$attr_k][2]))
			return;
		$item[$attr_k]["mime"] = $img[$attr_k][0];
		$item[$attr_k]["peso"] = $img[$attr_k][1];
		$item[$attr_k]["ancho"] = $img[$attr_k][3];
		$item[$attr_k]["alto"] = $img[$attr_k][4];
		$item[$attr_k]["archivo"] = urlencode($img[$attr_k][2]);
		$item[$attr_k]["miniatura"] = urlencode($img[$attr_k][2]);
		$item[$attr_k]["ancho_m"] = $img[$attr_k][5];
		$item[$attr_k]["alto_m"] = $img[$attr_k][6];
		$item[$attr_k]["peso_m"] = $img[$attr_k][7];
	}

}

?>