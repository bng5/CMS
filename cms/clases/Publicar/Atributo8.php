<?php

/**
 * Enlace externo
 *
 * @author pablo
 */

class Publicar_Atributo8 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		$prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			// es lista, por ahora no me preocupo
		}
		elseif($attr_v['unico'] == 1) {
			$item[$attr_k] = $prot_arr[$valores['int']].$valores['text'];
			if($attr_v['en_listado'])
				$db_item['link__'.$attr_v['identificador']] = "'".$mysqli->real_escape_string($item[$attr_k])."'";
		}
	}

}

?>