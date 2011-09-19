<?php

/**
 * Entero
 *
 * @author pablo
 */
class Publicar_Atributo11 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {
		$leng_id = $leng->id;


        $item[$attr_k] = $valores['int'];
        if($attr_v['en_listado']) {
            $mysqli = BaseDatos::Conectar();
            $db_item['int__'.$attr_v['identificador']] = $valores['int'] ? "'".$mysqli->real_escape_string($valores['int'])."'" : 'NULL';
        }
        /* TODO
         * definir a listado

        if($this->listado[$attr_k])
            $a_sqlite[$attr_v['tipo'].'__'.$attr_v['identificador']] = $valores[$attr_k][$attr_v['tipo']] ? "'".$mysqli->real_escape_string($valores[$attr_k][$attr_v['tipo']])."'" : 'NULL';
        */

    }
}
