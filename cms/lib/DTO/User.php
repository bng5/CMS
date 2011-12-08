<?php

/**
 * Description of User
 *
 * @author pablo
 */
class DTO_User extends DTO {

    protected $_valores = array(
        'username' => null,
        'estado_id' => 0,
        'nombre_mostrar' => null,
        'clave' => null,
        'email' => null,
        'aut' => null,
        'creado_por' => null,
        'pase' => null,
        'su' => false,
        'leng_id' => 1,
	);

    protected function _nuevo($a, $b = '') {
        var_dump($a, $b);
    }


    public function setUsername($value) {
		$value = trim($value);
        $this->_valores['username'] = $value;
		return $this;
    }

    public function setPassword($value, $encoded = true) {
        $this->_valores['clave'] = $encoded ? $value : crypt($value.$this->_created_date->format('U'));
        return $this;
    }

    /**
     *
     * @deprecated
     * @param string $value
     */
    public function setClave($value) {
        return $this->setPassword($value);
    }


    public function validatePass($pass) {
        return ($this->_valores['clave'] == crypt($pass.$this->_created_date->format('U'), $this->_valores['clave']));
    }

    public function sessionData() {
		return array(
			'id' => $this->_id,
			'username' => $this->_valores['username'],
			'su' => $this->_valores['su'],
			'leng_id' => $this->_valores['leng_id']
		);
	}

}
