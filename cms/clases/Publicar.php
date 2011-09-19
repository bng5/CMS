<?php

class Publicar {

	protected function acondicionar($leng, $valores, &$item, &$db_item = null) {

		foreach($this->atributos AS $attr_k => $attr_v) {

            call_user_func_array(array("Publicar_Atributo".$attr_v['tipo_id'], 'valor'), array($leng, $attr_k, $attr_v, $valores[$attr_k], &$item['valores'], &$db_item));
            // Función remplazada por lo que parece ser un Bug
			//call_user_func(array("Publicar_Atributo".$attr_v['tipo_id'], 'valor'), $leng, $attr_k, $attr_v, $valores[$attr_k], $item['valores'], $db_item);

		}

	}
}
