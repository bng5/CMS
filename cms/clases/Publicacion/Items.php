<?php

/**
 *
 *
 * @author pablo
 *
 */

class Publicacion_Items {
	

	/**
	 */
	public static function publicar() {

	}

	/**
	 *
	 * @param Parametros_PublicacionItems $parametros
	 * @return Listado
	*/
	public static function obtener(Parametros_PublicacionItems $parametros) {
		$db = DB::instancia();

        $bsq[] = "leng_cod = :leng_cod";
        $bsq_criterios[':leng_cod'] = $parametros->idioma->cod;

        if($parametros->where) {
            foreach($parametros->where AS $k => $v) {
                if(is_null($v)) {
                    $bsq[] = "{$k} IS NULL";
                }
                else {
                    $bsq[] = "{$k} = :{$k}";
                    $bsq_criterios[":{$k}"] = $v;
                }
            }
        }
        if(is_array($bsq))
            $bsq = "AND ".implode(" AND ", $bsq);

        if($parametros->categoria) {
			$orden_prov = "iac";
			$tabla_cats = ", items_a_categorias iac";
			$bsq .= " AND i.id = iac.item_id AND iac.categoria_id = :categoria_id";
            $bsq_criterios[':categoria_id'] = $parametros->categoria;
		}
		else {
			$orden_prov = "ii";
			$tabla_cats = " LEFT JOIN items_a_categorias iac ON i.id = iac.item_id";
        }

		$total = $db->prepare("SELECT COUNT(*) FROM `pub__{$parametros->seccion->id}` i{$tabla_cats} {$bsq}");
		//$total->execute(array($parametros->idioma->cod));
		$total->execute($bsq_criterios);
		$total = $total->fetchColumn();

		$orden = 'orden';
		$limite = ($parametros->rpp) ? " LIMIT ".(($parametros->pagina - 1) * $parametros->rpp).", ".$parametros->rpp : "";
        $orden_crit = $orden_aleat ? 'RAND()' : "ordennull ASC, {$orden_prov}.{$orden} ASC";//{$orden_dir}";

		$consulta = $db->prepare("SELECT *, {$orden_prov}.{$orden} IS NULL AS ordennull FROM `pub__{$parametros->seccion->id}` i{$tabla_cats}, items ii WHERE i.id = ii.id {$bsq} ORDER BY {$orden_crit} {$limite}");
//		$consulta = $db->prepare("SELECT ip.*, i.orden IS NULL AS ordennull FROM `pub__".$parametros->seccion->id."` ip JOIN items i ON ip.id = i.id WHERE ip.leng_cod = ? ORDER BY ordennull ASC, i.".$parametros->orden." ".$parametros->ordenDir.$limite);
		$consulta->setFetchMode(DB::FETCH_ASSOC);
		$consulta->execute($bsq_criterios);

		return new Listado($total, $consulta, $parametros->pagina, $parametros->rpp);
		//return $consulta->fetchAll(DB::FETCH_ASSOC);
	}
}
