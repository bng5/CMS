<?php

/**
 * Selector
 *
 * @author pablo
 */

class Publicar_Atributo10 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			// es lista, por ahora no me preocupo
		}
		elseif($attr_v['unico'] == 1) {
			$item[$attr_k] = (int) $valores['int'];
			if($attr_v['en_listado'])
				$db_item['int__'.$attr_v['identificador']] = $item[$attr_k];
		}
	}

}

?>