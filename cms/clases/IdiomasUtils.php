<?php

/**
 *
 * 26/04/10 05:26 PM
 * 
 *
 */


class IdiomasUtils {
	
	/*
	function __construct($disponibles = array()) {
		$this->disponibles = $disponibles;
		$this->usuario = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		
		foreach($this->usuario as $i=>$valor) {
			$valor = explode(';', $valor);
			$this->usuario[$i] = trim($valor[0]);
		}
		//return language_negotiate($lang, $accept);
	}
	*/

	/**
	 *
	 * @param array $disponibles
	 * @param string $def
	 * @return string
	 */
	static function negociar_http($disponibles, $def = "es") {
		foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $option) {
			$option = array_map('trim', explode(';', $option));
			$leng = $option[0];
			if(in_array($leng, $disponibles))
				return $leng;
			//$leng = substr($leng, 0, 2);
			if(strrpos($leng, "-") && !$mejor_opcion) {
				$leng = explode('-', $leng);
				if(in_array($leng[0], $disponibles))
					$mejor_opcion = $leng[0];
			}
		}
		return $mejor_opcion ? $mejor_opcion : $def;
	
		/*
	foreach($usuario as $i=>$valor)
	 {
	  $valor = explode(';', $valor);
	  $usuario[$i] = trim($valor[0]);
	 }

	 print_r($usuario);

	if(!(is_array($usuario) && is_array($disponibles)))
	  return '';
	foreach($usuario as $leng)
	 {
	  if(in_array($leng, $disponibles))
	    return $leng;
	  $leng = substr($leng, 0, 2);
	  if(in_array($leng, $disponibles))
	    return $leng;
	 }
	return '';
		 *
		 */
	}

	/*
	static function negotiateLanguage($supp, $default = 'en-US') {
        / *$supp = array();
        foreach ($supported as $lang => $isSupported) {
            if ($isSupported) {
                $supp[strtolower($lang)] = $lang;
            }
        }* /

        if (!count($supp)) {
            return $default;
        }

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $match = self::_matchAccept($_SERVER['HTTP_ACCEPT_LANGUAGE'], $supp);
            if (!is_null($match)) {
                return $match;
            }
        }

        if (isset($_SERVER['REMOTE_HOST'])) {
            $lang = strtolower(end($h = explode('.', $_SERVER['REMOTE_HOST'])));
            if (isset($supp[$lang])) {
                return $supp[$lang];
            }
        }

        return $default;
    }

	    / **
     * Parses a weighed "Accept" HTTP header and matches it against a list
     * of supported options
     *
     * @param string  $header    The HTTP "Accept" header to parse
     * @param array   $supported A list of supported values
     *
     * @return string|NULL  a matched option, or NULL if no match
     * @access private
     * @static
     * /
    private static function _matchAccept($header, $supported)
    {
        $matches = self::_sortAccept($header);
        foreach ($matches as $key => $q) {
            if (isset($supported[$key])) {
                return $supported[$key];
            }
        }
        // If any (i.e. "*") is acceptable, return the first supported format
        if (isset($matches['*'])) {
            return array_shift($supported);
        }
        return null;
    }

    / **
     * Parses and sorts a weighed "Accept" HTTP header
     *
     * @param string  $header The HTTP "Accept" header to parse
     *
     * @return array  a sorted list of "accept" options
     * @access private
     * @static
     * /
    private static function _sortAccept($header)
    {
        $matches = array();
        foreach (explode(',', $header) as $option) {
            $option = array_map('trim', explode(';', $option));

            $l = strtolower($option[0]);
            if (isset($option[1])) {
                $q = (float) str_replace('q=', '', $option[1]);
            } else {
                $q = null;
                // Assign default low weight for generic values
// OJO SIGUIENTE LÍNEA
                if ($l == '* /*') {
                    $q = 0.01;
                } elseif (substr($l, -1) == '*') {
                    $q = 0.02;
                }
            }
            // Unweighted values, get high weight by their position in the
            // list
            $matches[$l] = isset($q) ? $q : 1000 - count($matches);
        }
        arsort($matches, SORT_NUMERIC);
print_r($matches);
        return $matches;
    }
*/
}
/*
/ *$neg = new Idiomas(array('fr', 'en', 'es-uy', 'de'));
print $neg->Negociar();
* /
//$_SERVER['HTTP_ACCEPT_CHARSET']
*/
?>