<?php

/**
 * Almacén de Tokens para recuperar sesiones
 *
 * @author pablo
 */
class DAO_UsersRemember extends DAO {

	public function consulta($usuario_id, $pase) {
		return $this->_db->query("SELECT * FROM `usuarios_sesiones_recuperar` WHERE `usuario_id` = {$usuario_id} AND `pase` = '{$pase}' LIMIT 1")->fetch(DB::FETCH_ASSOC);
	}

	public function alta($usuario_id, $pase, $recordar = false) {
		$recordar = (int) $recordar;
		$this->_exec("INSERT INTO `usuarios_sesiones_recuperar` (`usuario_id`, `pase`, `recuperar`) VALUES ({$usuario_id}, '{$pase}', {$recordar})", parent::INSERT);
	}

	/**
	 *
	 * @param int $usuario_id
	 * @param array $pase
	 */
	public function modificacion($usuario_id, $pase) {
		$this->_exec("UPDATE `usuarios_sesiones_recuperar` SET `pase` = '".current($pase)."' WHERE `usuario_id` = {$usuario_id} AND `pase` = '".key($pase)."' LIMIT 1", parent::UPDATE);
	}

	public function bajaPase($usuario_id, $pase) {
		$this->_exec("DELETE FROM `usuarios_sesiones_recuperar` WHERE `usuario_id` = {$usuario_id} AND `pase` = '{$pase}' LIMIT 1", parent::DELETE);
	}

	public function bajaUsuario($usuario_id) {
		$this->_exec("DELETE FROM `usuarios_sesiones_recuperar` WHERE `usuario_id` = {$usuario_id}", parent::DELETE);
	}

	public function bajaExpirado($dias) {
		$dias = -$dias;
		$this->_exec("DELETE FROM `usuarios_sesiones_recuperar` WHERE `tiempo` < TIMESTAMPADD(DAY,{$dias},NOW())", parent::DELETE);
	}



}