<?php


/**
 * Description of AccesoArray
 *
 * @author pablo
 */
class AccesoArray implements ArrayAccess {

	private $_array = array();

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->_array);
	}

	public function offsetGet($offset) {
		return $this->_array[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->_array[$value['id']] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->_array[$offset]);
	}

}

?>