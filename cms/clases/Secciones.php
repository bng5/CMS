<?php

/**
 * BackTrace
 *	class	Publicacion_Secciones
 * 
 */

class Secciones {

	/**
	 *
	 * @param (int) $leng
	 * @param <type> $bsq
	 * @param <type> $orden
	 * @param <type> $params
	 * @return Listado
	 */
	public static function Listado($leng_id = null, $bsq = array(), $orden = 'orden',       $params = array()) { //'superior_id' => 0
		/*if(!$leng_id = intval($leng)) {
			$leng = "(SELECT id FROM lenguajes WHERE codigo = '".$leng."')";
		}
		*/
		if(count($bsq)) {
			$_bsq = array();
			foreach($bsq AS $k => $v)
				$_bsq[] = "`${k}` = ${v}";
			$bsq_sql = 'WHERE '.implode(" AND ", $_bsq);
		}
		$db = DB::instancia();
		$total = $db->query("SELECT COUNT(*) FROM secciones ".$bsq_sql)->fetchColumn();

		if(isset($leng_id)) {
			$consulta = $db->query("SELECT * FROM secciones s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = ".$leng_id." ".$bsq_sql." ORDER BY ".$orden);
		}
		else {
			$consulta = $db->query("SELECT * FROM secciones ".$bsq_sql." ORDER BY ".$orden);
		}
		$consulta->setFetchMode(DB::FETCH_CLASS, 'Seccion');
		return new Listado($total, $consulta, $pagina, $rpp);
		//$l->setIterator($consulta);
		//return $l;
		//return Listado::InstanciaSQL("SELECT s.*, sn.titulo FROM `secciones` s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = ".$leng." ".$bsq_sql." ORDER BY orden");
	}

	
	public static function obtenerPorId($id) {
		//if(!$id = intval($id))
		//	throw new Exception("No se indicó un Id válido para cargar la sección.");
		$db = DB::instancia();
		$consulta = $db->prepare("SELECT * FROM secciones s WHERE id = ? LIMIT 1");
		$consulta->bindValue(1, $id, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchObject(Seccion);
	}

	public static function obtenerPorIdentificador($identificador) {
		$identificador = trim($identificador);
		$db = DB::instancia();
		$consulta = $db->prepare("SELECT * FROM secciones WHERE identificador = ? LIMIT 1");
		$consulta->bindValue(1, $identificador, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->fetchObject(Seccion);
	}

	public static function Guardar(Seecion $seccion) {

	}

	public static function nombres() {
		$db = DB::instancia();
		return $db->query("SELECT sn.id, l.codigo, sn.titulo, sn.url FROM `secciones_nombres` sn JOIN lenguajes l ON sn.leng_id = l.id WHERE l.estado = 1 ORDER BY leng_poromision DESC")->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>