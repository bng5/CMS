<?php

/**
 * Fecha
 *
 * @author pablo
 */

class Publicar_Atributo7 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {
//global $bng5_texto;
		//$almacenamiento = '';//Atributos::$almacenamiento[$attr_v['tipo_id']];
//$texto = current($bng5_texto);
		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			// es lista, por ahora no me preocupo
		}
		elseif($attr_v['unico'] == 1) {
			$fecha = new Fecha($valores['date']);
			$item[$attr_k]['valor'] = $valores['date'];
			$formatoMst = $attr_v['extra']['f'] ? $attr_v['extra']['f'] : '%d/%m/%Y';//"%l, %d de %F de %Y";
			$item[$attr_k]['str'] = $valores['date'] ? $fecha->Formatear($formatoMst, $leng->codigo) : null;
			if($valores['date'] && $attr_v['en_listado']) {
				$db_item['date__'.$attr_v['identificador']] = ($valores['date'] ? "'".$valores['date']."'" : 'NULL');
				$db_item['string__'.$attr_v['identificador']] = ($valores['date'] ? "'".$item[$attr_k]['str']."'" : 'NULL');

//                $mysqli = BaseDatos::Conectar();
//                $db_item['string__'.$attr_v['identificador']] = $valores['string'] ? "'".$mysqli->real_escape_string($valores['string'])."'" : 'NULL';

			}
		}
	}
}
