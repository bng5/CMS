<?php

class Valores {
	const TIPO_ITEM = 1;
	const TIPO_CATEGORIA = 2;
	const TIPO_SECCION = 3;

	private $_id, $_tabla, $_prefijo;

	public function __construct($id, $tipo) {
		$this->_id = $id;
		$tipos = array(
			1 => array('items', 'item'),
			2 => array('categorias', 'categoria'),
			3 => array('secciones', 'item'),
		);
		$this->_tabla = $tipos[$tipo][0];
		$this->_prefijo = $tipos[$tipo][1];
	}

	/**
	 *
	 * @param string (item|secciones|categorias) $tipo
	 * @param array $atributos
	 * @param array $valores
	 * @return int
	 */

/*


    [dato] => Array
        (

            [m] => Array
                (
                    [1] => Array
                        (
                            [1] => Prueba 1
                            [2] => Test 1
                        )

                    [4] => Array
                        (
                            [3] => 2
                        )

                    [3] => Array
                        (
                            [4] => 1
                        )

                )
        )

*/
	public function modificar($atributos, $valores) {
		$mysqli = BaseDatos::Conectar();
		foreach($valores AS $atributo_id => $atributo_arr) {
			foreach($atributo_arr AS $valor_id => $valor) {
				if(empty($valor)) { // || !$_POST['dato']['m'][$atributo_id]) {
					if($atributos[$atributo_id]['sugerido'] == 2) {
						// error de campo obligatorio
					}
					else {
						$mysqli->query("DELETE FROM ".$this->_tabla."_valores WHERE id = ".$valor_id);
					}
				}
				else {
					// Galería
					if($atributos[$atributo_id]['tipo_id'] == 4) {// == 'int' && $atributos[$mod_atributo_id]['subtipo'] == 4)
						$mysqli->query("DELETE FROM galerias_imagenes WHERE galeria_id = ".$valor);
						$orden = 1;
						if(is_array($_POST['img'])) {
							foreach($_POST['img'] AS $imagenes) {
								$mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (".$valor.", ".$imagenes.", ".$orden.")");
								$orden++;
							}
						}
					}
					else {
						//if($atributos[$mod_atributo_id]['en_listado'] == 1 && !$listado[$mod_atributo_id])
						// Enlace
						//if($atributos[$mod_atributo_id]['tipo'] == "text" && $atributos[$mod_atributo_id]['subtipo'] == 1)
						//	$mod_valor .= "', `int` = '".$_POST['prot'][$mod_atributo_id];
						//elseif($atributos[$mod_atributo_id]['tipo_id'] == 12 xor $atributos[$mod_atributo_id]['tipo_id'] == 13) {
						//	$mod_valor = implode(";", current($_POST['dato']['m'][$mod_atributo_id]));
						//	unset($_POST['dato']['m'][$mod_atributo_id]);
						//}
						$mysqli->query("UPDATE ".$this->_tabla."_valores SET `".Atributos::$almacenamiento[$atributos[$atributo_id]['tipo_id']]."` = '".$mysqli->real_escape_string($valor)."' WHERE id = ".$valor_id);
						// Precio
						//if($mysqli->affected_rows && $atributos[$atributo_id]['tipo_id'] == 16)
						//	$mysqli->query("INSERT INTO precios_historial (item_id, fecha, atributo_id, precio) VALUES (${id}, NOW(), ${atributo_id}, '${valor}')");
						$modif += $mysqli->affected_rows;
					}
				}
			}
		}
		return $modif;
	}

	/**
	 *
	 * @param string (items|ssecciones|categorias) $tipo
	 * @param array $atributos
	 * @param array $valores
	 * @return int
	 */

/*

    [dato] => Array
        (
            [n] => Array
                (
                    [1] => Array
                        (
                            [1] => Array
                                (
                                    [0] =>
                                )

                            [2] => Array
                                (
                                    [0] =>
                                )

                        )

                    [4] => Array
                        (
                            [0] =>
                        )

                    [2] => Array
                        (
                            [0] =>
                        )

                    [5] => Array
                        (
                            [0] =>
                        )

                    [3] => Array
                        (
                            [0] =>
                        )

                )

        )

*/
	public function ingresar($atributos, $valores) {
		$mysqli = BaseDatos::Conectar();
		foreach($valores AS $atributo_id => $atributo_arr) {
			foreach($atributo_arr AS $leng_id => $leng) {
				// Galería
				if($atributos[$atributo_id]['tipo_id'] == 4) {// == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 4)
					$mysqli->query("INSERT INTO galerias (`creada`) VALUES (now())");
					$leng = $mysqli->insert_id;
					$orden = 1;
					if(is_array($_POST['img'])) {
						foreach($_POST['img'] AS $imagenes) {
							$mysqli->query("INSERT INTO galerias_imagenes (`galeria_id`, `imagen_id`, `orden`) VALUES (".$leng.", ".$imagenes.", ".$orden.")");
							$orden++;
						}
					}
				}

				if(empty($leng)) {// || !$_POST['dato']['n'][$ins_atributo_id]
					continue;
				}
				// Checkbox / Selector múltiple
				//if($atributos[$ins_atributo_id]['tipo_id'] == 12 xor $atributos[$ins_atributo_id]['tipo_id'] == 13) {
				//	$ins_leng = implode(";", $_POST['dato']['n'][$ins_atributo_id]);
				//	unset($_POST['dato']['n'][$ins_atributo_id]);
				//}
				if(is_array($leng)) {
					foreach($leng AS $valor) {
						if(empty($valor))
							continue;
						$mysqli->query("INSERT INTO ".$this->_tabla."_valores (`atributo_id`, `".$this->_prefijo."_id`, `leng_id`, `".Atributos::$almacenamiento[$atributos[$atributo_id]['tipo_id']]."`) VALUES (".$atributo_id.", ".$this->_id.", ".$leng_id.", '".$mysqli->real_escape_string($valor)."')");
						$modif += $mysqli->affected_rows;
					}
				}
				else {
					//if($atributos[$ins_atributo_id]['tipo'] == 'int' && $atributos[$ins_atributo_id]['subtipo'] == 6) {
					//	$rango = str_replace(" ", "", $ins_leng);
					//	$rango = explode(",", $rango);
					//	$pares = $_POST['extra'][$ins_atributo_id];
					//	foreach($rango AS $numeros) {
					//		$numeros = explode("-", $numeros);
					//		for($i = $numeros[0]; $i <= $numeros[1]; $i++) {
					//			if($pares == 1 && ($i%2) != 1)
					//				continue;
					//			elseif($pares == 2 && ($i%2) == 1)
					//				continue;
					//			$mysqli->query("INSERT INTO subitems (`item_id`, `atributo_id`, `codigo`) VALUES (${id}, ${ins_atributo_id}, '${i}')");
					//		}
					//	}
					//	$mysqli->query("INSERT INTO secciones_valores (`atributo_id`, `item_id`, `{$atributos[$ins_atributo_id]['tipo']}`) VALUES (${ins_atributo_id}, ${id}, '${ins_atributo_id}')");
					//}
					//else {
					// Enlace
					//if($atributos[$ins_atributo_id]['tipo'] == "text" && $atributos[$ins_atributo_id]['subtipo'] == 1) {
					//	$atributos[$ins_atributo_id]['tipo'] .= "`, `int";
					//	$ins_leng .= "', '".$_POST['prot'][$ins_atributo_id];
					//}
					// Precio
					//elseif($atributos[$ins_atributo_id]['tipo_id'] == 16)
					//	$mysqli->query("INSERT INTO precios_historial (item_id, fecha, atributo_id, precio) VALUES (${id}, NOW(), ${ins_atributo_id}, '${ins_leng}')");
					$mysqli->query("INSERT INTO ".$this->_tabla."_valores (`atributo_id`, `".$this->_prefijo."_id`, `".Atributos::$almacenamiento[$atributos[$atributo_id]['tipo_id']]."`) VALUES (".$atributo_id.", ".$this->_id.", '".$mysqli->real_escape_string($leng)."')");
					$modif += $mysqli->affected_rows;
				}
			}
		}
		return $modif;
	}
}