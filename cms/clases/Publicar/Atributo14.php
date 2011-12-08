<?php

/**
 * Item (Dato externo)
 *
 * @author pablo
 */
class Publicar_Atributo14 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {
		$leng_id = $leng->id;


        $item[$attr_k] = $valores['string'];
        if($attr_v['en_listado']) {
            if($valores['string']) {
                $mysqli = BaseDatos::Conectar();
                $partes = explode(',', $valores['string']);
                $db_item['string__'.$attr_v['identificador']] = $partes[1] ? "'".$mysqli->real_escape_string($partes[1])."'" : 'NULL';
                $db_item['text__'.$attr_v['identificador']] = $partes[0] ? "'".$mysqli->real_escape_string($partes[0])."'" : 'NULL';
            }
        }
    }
}
