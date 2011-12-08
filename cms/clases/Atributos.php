<?php

class Atributos {

	const STR = 1;// 	 [int], string	 1	 0	 1
	const IMG = 2;// 	int	1	0	2
	const ARCH = 3;// 	int	1	0	2
	const IMGGAL = 4;// 	int	0	0	2
	const TXT = 5;// 	[int], text	1	0	1
	const VIDEOYT = 6;// 	string
	const FECHA = 7;// 	string
	const LINK = 8;// 	string
	const ENTERO = 11;
	const ITEM = 12;
    const SHARE = 13;
    const FACEBOOK_OBJ = 14;

	public static $almacenamiento = array(
		1 => 'string',
		2 => 'int',
		3 => 'int',
		4 => 'int',
		5 => 'text',
		6 => 'string',
		7 => 'date',
		8 => 'text',
		9 => 'int',
		10 => 'int',
		11 => 'int',
		12 => 'int',
		13 => 'string',
        14 => 'string',
	);

	public static function Listado($leng_id, $tipo) {
		$DB = DB::Conectar();
		$stmt = $DB->query("SELECT ia.id, ia.identificador, ia.sugerido, ia.unico, ia.tipo_id AS tipo, ia.extra, ian.atributo AS nombre, isaa.orden, isaa.por_omision, isaa.en_listado, isaa.salida, isaa.superior, iv.leng_id, iv.string, iv.date, iv.text, iv.int, iv.num FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = {$leng_id}, items_secciones_a_atributos isaa LEFT JOIN items_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.id = isaa.atributo_id AND seccion_id = ".$tipo['items']." ORDER BY orden");
		$stmt->setFetchMode(DB::FETCH_OBJ);
		$listado = new Listado($total, $stmt);
		return $listado;
	}

	/**
	 *
	 * @param string (i|s|c) $tipo
	 * @param int $seccion_id
	 * @param array $bsq
	 * @param object $leng
	 * @return array
	 */
	public static function getArray($tipo, $seccion_id, $bsq = array(), $leng_id = 1) {
		$tablas = array(
			's' => '',
			'i' => 'items_',
			'c' => 'categorias_',
		);
		if(!array_key_exists($tipo, $tablas))
			die(__FILE__." ".__LINE__);
		$db = DB::Conectar();
		$stmt = $db->query("SELECT ia.*, ian.atributo FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND ian.leng_id = ".$leng_id.", ".$tablas[$tipo]."secciones_a_atributos isaa WHERE isaa.seccion_id = ".$seccion_id." AND ia.id = isaa.atributo_id");
		//return $stmt->fetchAll(DB::FETCH_ASSOC);
		$stmt->setFetchMode(DB::FETCH_ASSOC);
		$arr = array();
		foreach($stmt AS $fila) {
			$arr[$fila['id']] = $fila;
		}
		return $arr;
	}
}
