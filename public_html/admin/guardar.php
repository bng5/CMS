<?php


$tablas = array(
	's' => '',
	'i' => 'items_',
	'c' => 'categorias_',
);
if(!array_key_exists($_POST['tipo'], $tablas))
	die(__FILE__." ".__LINE__);
$tipo = $_POST['tipo'];

$edicion_id = $_POST['id'];
//$cat_id = $_POST['cat'];
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar") {
	$mysqli = BaseDatos::Conectar();
	$atributos = array();
	$listado = array();
	if(!$atributos_tipos = $mysqli->query("SELECT ia.* FROM items_atributos ia LEFT JOIN ".$tablas[$tipo]."secciones_a_atributos isaa ON ia.id = isaa.atributo_id, secciones s WHERE isaa.seccion_id = ".$seccion_id." AND isaa.seccion_id = s.id"))
		echo __LINE__." - ".$mysqli->error;
	if($fila_at = $atributos_tipos->fetch_assoc()) {
		do {
			$fila_id = array_shift($fila_at);
			$atributos[$fila_id] = $fila_at;
			if($fila_at['en_listado'] == 1)
				$listado[] = $fila_id;
		}while($fila_at = $atributos_tipos->fetch_assoc());
		$atributos_tipos->close();
	}

}


?>