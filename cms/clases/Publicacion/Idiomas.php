<?php

/**
 * Description of Secciones
 *
 * @author pablo
 */
class Publicacion_Idiomas {

	private static $_idiomas, $_poromision;
	
	public static function publicar() {

	}

	/**
	 * 
	 */
	private static function _carga() {
		self::$_idiomas = include(RUTA_CARPETA.'bng5/datos/idiomas.php');
		self::$_poromision = $poromision;
	}

	public static function obtener() {
		if(!self::$_idiomas) {
			self::_carga();
		}
		return self::$_idiomas;
	}

	/**
	 *
	 * @return StdClass
	 * 
	 */
	public static function predeterminado() {
		if(!self::$_poromision) {
			self::_carga();
		}
		return $idiomas[IdiomasUtils::negociar_http(array_keys(self::$_idiomas), self::$_poromision)];//self::$_idiomas[self::$_poromision];
	}
	/**
	 *
	 * @param string $codigo
	 */
	public static function resolver($codigo) {
		$idiomas = self::obtener();
		if(!empty($codigo)) {
			if(array_key_exists($codigo, $idiomas)) {
				/*
				 * FIXME
				 * Parche: en algunos lugares se usa codigo y en otros cod
				 */
				$idiomas[$codigo]['codigo'] = $idiomas[$codigo]['cod'];
				$idioma = (object) $idiomas[$codigo];
			}
			else {
				//return 404;
				///$idioma = (object) $idiomas[IdiomasUtils::negociar_http(array_keys($idiomas), $poromision)];
				//$codigo = self::$_poromision;
				//$vista->error_status(404);
				throw new Exception("", 404);
			}
		}
		else
			$codigo = IdiomasUtils::negociar_http(array_keys($idiomas), self::$_poromision);
			/*
			* FIXME
			* Parche: en algunos lugares se usa codigo y en otros cod
			*/
			$idiomas[$codigo]['codigo'] = $idiomas[$codigo]['cod'];
		return (object) $idiomas[$codigo];
	}

}

?>