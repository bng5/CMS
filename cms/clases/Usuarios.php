<?php

/**
 * 
 *
 * @author pablo
 */
class Usuarios {

	public static function obtenerPorUsuario($nombreusuario) {
		$db = DB::instancia();
		$statement = $db->prepare('SELECT `id`, `usuario`, `estado_id`, leng_id, nombre_mostrar, clave FROM `usuarios` WHERE `usuario` = ? LIMIT 1');
		$statement->execute(array($nombreusuario));
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}
}

?>