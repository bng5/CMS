<?php

/**
 *
 * BackTrace
 *	admin	secciones_const.php
 *
 * @author pablo
 *
 */
class Publicacion_Acceso_Secciones {

	public static $secciones, $secciones_sup, $secciones_urls;

	public static function publicar() {
		$seccionesListado = Secciones::Listado(null, array('salida_sitio' => 1), 'superior_id, orden, id');
		$secciones = array();
		$secciones_sup = array();
		if($seccionesListado->total) {
			$iterador = $seccionesListado->getIterator();
			foreach($iterador AS $seccion) {
				$secciones[$seccion->id] = array('identificador' => $seccion->identificador, 'info' => $seccion->info, 'items' => $seccion->items, 'categorias' => $seccion->categorias, 'menu' => (boolean) $seccion->menu);//'id' => $seccion->id,
				$secciones_sup[$seccion->superior_id][] = $seccion->id;
			}
		}
		$urls = array();
		$fila_nombres = Secciones::nombres();
		foreach($fila_nombres AS $fila) {
			$id = (int) array_shift($fila);
			$codigo = array_shift($fila);
			if(!isset($secciones[$id]))
				continue;
			$urls[$codigo][$fila['url']] = $id;
			$secciones[$id]['nombres'][$codigo] = $fila['titulo'];
			$secciones[$id]['urls'][$codigo] = $fila['url'];
		}
		file_put_contents(RUTA_CARPETA.'bng5/datos/secciones.php', "<?php /* Este archivo fue generado por ".__FILE__."*/\n\$secciones=".var_export($secciones, true)."; \$secciones_sup=".var_export($secciones_sup, true)."; \$secciones_urls=".var_export($urls, true).";");
	}

	/**
	 *
	 * @param string $identificador
	 * @param StdClass $idioma
	 * @return StdClass
	 */
	public static function resolver($identificador, $idioma) {
		if(!self::$secciones)
			self::_carga();
		if(empty($identificador)) {
			$identificador = self::$secciones[current(self::$secciones_sup[0])]['urls'][$idioma->cod];
		}
		elseif(!array_key_exists($identificador, self::$secciones_urls[$idioma->cod])) {
			throw new Exception("", 404);
		}
		$seccion = (object) self::$secciones[self::$secciones_urls[$idioma->cod][$identificador]];
		$seccion->id = self::$secciones_urls[$idioma->cod][$identificador];
		return $seccion;
	}

	private static function _carga() {
		include(RUTA_CARPETA.'bng5/datos/secciones.php');
		self::$secciones = $secciones;
		self::$secciones_sup = $secciones_sup;
		self::$secciones_urls = $secciones_urls;
	}

	public static function obtener() {
		if(!self::$secciones) {
			self::_carga();
		}
		return self::$secciones;
		//return include(RUTA.'/bng5/datos/idiomas.php');
	}
}
