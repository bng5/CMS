<?php

/**
 *
 *
 * @author pablo
 *
 */

class Publicacion_Categorias {
	

	/**
	 */
	public static function publicar() {

	}

	/**
	 *
	 * @param <type> $seccion
	 * @param <type> $idioma
	 * @return <type>
	*/
	public static function obtener($seccion, $idioma, $superior_id = false) {
		$db = DB::instancia();
        //$sql = "SELECT * FROM pubcats__".$seccion->id." WHERE leng_cod = ?";
        $sql = "SELECT pc.* FROM pubcats__{$seccion->id} pc JOIN items_categorias ic ON pc.id = ic.id WHERE pc.leng_cod = ?";
        $criterios = array($idioma->cod);
        if(is_int($superior_id)) {
            $sql .= " AND superior = ?";
            array_push($criterios, $superior_id);
        }
        $sql .= " ORDER BY ic.orden";
		$consulta = $db->prepare($sql);
		$consulta->execute($criterios);
		return $consulta->fetchAll(DB::FETCH_ASSOC);
	}
}
