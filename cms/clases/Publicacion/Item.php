<?php

/**
 * Description of Publicacion_Item
 *
 * @author pablo
 */
class Publicacion_Item {

	/**
	 *
	 * @param <type> $item_id
	 * @param <type> $idioma_codigo
	 * @return array
	 *
	 */
	public static function obtener($item_id, $idioma_codigo) {
		return include(RUTA_CARPETA.'bng5/datos/item/'.$item_id.'.'.$idioma_codigo.'.php');
	}

	/*
	 * TODO
	 * Pendiente a resolver
	public static function guardar(Item $item) {
		foreach($item->valores AS $idioma_codigo => $valores) {
			file_put_contents(RUTA_CARPETA."bng5/datos/item/".$item->id.".".$idioma_codigo.".php", "<?php\nreturn ".var_export($valores, true).";\n?>");
		}
	}
	*/
}

?>