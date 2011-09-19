<?php

/**
 * Video YouTube
 *
 * @author pablo
 */

class Publicar_Atributo6 extends Publicar_Atributo {

	public static function valor($leng_id, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		//$almacenamiento = '';//Atributos::$almacenamiento[$attr_v['tipo_id']];

		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			// es lista, por ahora no me preocupo
		}
		elseif($attr_v['unico'] == 1) {
				$item[$attr_k] = $valores[$almacenamiento];
		}
		// Es multilingüe
		elseif($attr_v['unico'] == 2) {
			// NO hay opcion mltilingüe
		}
	}

}

?>