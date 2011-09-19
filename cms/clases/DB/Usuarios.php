<?php

/**
 * Description of Usuarios
 *
 * @author pablo
 */
class DB_Usuarios {

	public static function obtenerPorUsuario($usuario) {
		$db = DB::instancia();
		$statement = $db->prepare('SELECT `id`, `usuario`, `estado_id`, leng_id, nombre_mostrar, clave FROM `usuarios` WHERE `usuario` = ? LIMIT 1');
		$statement->execute(array($usuario));
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}

	public static function obtenerPorPase($nombreusuario, $pase) {
		$db = DB::instancia();
		$statement = $db->prepare('SELECT `id`, `usuario`, `estado_id`, leng_id, nombre_mostrar, clave FROM `usuarios` u JOIN usuarios_pases up ON u.id = up.usuario_id WHERE `usuario` = ? AND up.pase = ? LIMIT 1');
		$statement->execute(array($nombreusuario, $pase));
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}
}

?>