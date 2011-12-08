<?php

/**
 * Users DTO
 *
 * @package CMS
 * @author pablo
 */
class DAO_Users extends DAO {


    /**
     *
     * @param string $username
     * @return DTO
     */
    public function getByUsername($username) {
		$statement = $this->_db->prepare('SELECT * FROM `usuarios` WHERE `username` = ? LIMIT 1');
		$statement->execute(array($username));
		$statement->setFetchMode(DB::FETCH_CLASS, 'DTO_User');
		if($user = $statement->fetch())
			return $user;
		else
			return false;
    }

    public function validateUsername($username) {
        if(empty($value))
			throw new Exception('username', StdResponse::CAMPO_ERR_REQUERIDO);// Debe indicar el nombre de usuario
		if((mb_strlen($value) < 4 || mb_strlen($value) > 60))
			throw new Exception('username', ((mb_strlen($value) < 4) ? StdResponse::CAMPO_ERR_LARGO_MINIMO : StdResponse::CAMPO_ERR_LARGO_MAXIMO));// El nombre de usuario debe contener entre 4 y 60 caracteres
		if(!preg_match('/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ]{1}[-a-zA-Z0-9@._áéíóúüñÁÉÍÓÚÜÑ]+$/', $value))
			throw new Exception('username', StdResponse::CAMPO_ERR_TIPO_DATO);// El nombre de usuario contiene caracteres no válidos
    }


}
