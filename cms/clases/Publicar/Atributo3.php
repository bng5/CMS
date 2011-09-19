<?php

/**
 * Archivo
 *
 * @author pablo
 */
class Publicar_Atributo3 extends Publicar_Atributo {

	public static function valor($leng_id, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		$mysqli = BaseDatos::Conectar();
		$valor = $valores['int'];
		///$this->nodos[$attr_k] = '';//$doc->createElement('imagen');
		if(!empty($valor)) {
			$cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = '".$valor."'");
			$img[$attr_k] = $cons_img->fetch_row();
		}
		if($attr_v['en_listado'])
			$db_item['arch__'.$attr_v['identificador']] = $img[$attr_k] ? "'".serialize(array(urlencode($img[2]), $img[0], $img[1]))."'" : 'NULL';
		if(empty($img[$attr_k][2]))
			return;
		$item[$attr_k]["mime"] = $img[$attr_k][0];
		$item[$attr_k]["peso"] = $img[$attr_k][1];
		$item[$attr_k]["archivo"] = urlencode($img[$attr_k][2]);
	}

}

?>