<?php

/**
 * Campo de texto
 *
 * @author pablo
 */
class Publicar_Atributo1 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {
		$leng_id = $leng->id;

		// Es lista
		if($attr_v['unico'] == 0) {
			if($valores) {
				foreach($valores AS $attr_valores)
					$item[$attr_k][] = $attr_valores['string'];
			}
		}
		// es único
		elseif($attr_v['unico'] == 1) {
			$item[$attr_k] = $valores['string'];
            if($attr_v['en_listado']) {
                $mysqli = BaseDatos::Conectar();
                $db_item['string__'.$attr_v['identificador']] = $valores['string'] ? "'".$mysqli->real_escape_string($valores['string'])."'" : 'NULL';
            }
			/* TODO
			 * definir a listado

			if($this->listado[$attr_k])
				$a_sqlite[$attr_v['tipo'].'__'.$attr_v['identificador']] = $valores[$attr_k][$attr_v['tipo']] ? "'".$mysqli->real_escape_string($valores[$attr_k][$attr_v['tipo']])."'" : 'NULL';
			*/
		}
		// Es multilingüe
		elseif($attr_v['unico'] == 2) {
			$item[$attr_k] = $valores[$leng_id]['string'];
			$bsq_texto .= $valores[$leng_id]['string']." ";
            if($attr_v['en_listado']) {
                $mysqli = BaseDatos::Conectar();
                $db_item['string__'.$attr_v['identificador']] = $valores[$leng_id]['string'] ? "'".$mysqli->real_escape_string($valores[$leng_id]['string'])."'" : 'NULL';
            }
		}
		//$item[$attr_k] = $valores[$attr_k][$leng_id][$attr_v['tipo']];
    }
}
