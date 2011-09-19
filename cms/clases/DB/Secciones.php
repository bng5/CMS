<?php

class DB_Secciones {

	//public static $criterios = array('id', 'url', 'superior_id', 'sistema', 'salida_sitio', 'menu');



	/**
	 *
	 * @param int $idioma_id
	 * @param array $criterios
	 * @param string $orden
	 * @return PDOStatement
	 */
	public static function obtenerIterador($idioma_id, $criterios = array(), $orden = 'orden') {
		$consulta = "SELECT s.id, sn.titulo, sn.url, superior_id, orden, link_cms, sistema, info, items, categorias, categorias_prof, salida_sitio, menu, rpp FROM secciones s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = ".$idioma_id;
		if(count($criterios)) {
			foreach($criterios AS $crit_k => &$crit_v) {
				$crit_v = $crit_k." = '{$crit_v}'";
			}
			$consulta .= "WHERE ".implode(" AND ", $criterios);
		}
		$consulta .= " ORDER BY ".$orden;
		$db = DB::instancia();
		$consulta = $db->query($consulta);
		$consulta->setFetchMode(DB::FETCH_CLASS, 'Seccion');
		return $consulta;
	}

	/**
	 *
	 * @param int $idioma_id
	 * @param array $criterios
	 * @return Seccion
	 */
	public static function obtenerSeccion($idioma_id, $criterios = array()) {
		list($kt, $k) = isset($criterios['id']) ? array('s', 'id') : array('sn', 'url');
		$db = DB::instancia();
		//, rpp
		$consulta = $db->query("SELECT s.id, sn.titulo, sn.url, superior_id, orden, info, items, categorias, categorias_prof, salida_sitio, menu FROM secciones s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = {$idioma_id} WHERE {$kt}.{$k} = '{$criterios[$k]}' LIMIT 1");
		return $consulta->fetchObject('Seccion');
	}
	
}
