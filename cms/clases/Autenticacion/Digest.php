<?php

/**
 * Description of Digest
 *
 * @author pablo
 */
class Autenticacion_Digest implements iAutenticacion {

	function  __construct($realm = null) {
		$this->realm = $realm ? $realm : self::REALM;
	}

	function clase() {
		return "digest";
	}
	
	function autenticar() {
		if(!isset($_SERVER['PHP_AUTH_DIGEST']))
			return false;
		$this->parse_digest($_SERVER['PHP_AUTH_DIGEST']);
		return true;
	}
	
	function solicitar_autenticacion() {
		header('WWW-Authenticate: Digest realm="'.$this->realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($this->realm).'"');
	}

	function parse_digest($txt) {
		$partes_requeridas = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();

		preg_match_all('@(' . implode('|', array_keys($partes_requeridas)) . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
var_dump($matches);
		foreach($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($partes_requeridas[$m[1]]);
		}
		return $partes_requeridas ? false : $data;
	}
}

?>