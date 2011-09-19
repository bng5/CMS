<?php

require('inc/iniciar.php');
//require('inc/ad_sesiones.php');

header("Expires: ", true);
header("Cache-Control: ", true);
header("Content-Type: ", true);
header("Pragma: ", true);
if($path = trim($_SERVER['PATH_INFO'], " /")) {
	$archivo = RUTA_CARPETA."img/{$path}";

	if($_GET['tam']) {
		$imagen = Imagen::crearDesdeArchivo($archivo);
		$imagen->escalar($_GET['tam'], $_GET['tam']);
		$imagen->imprimir();
		exit;
	}

	if(file_exists($archivo) && $hash = md5_file($archivo)) {
		$fecha_mod = filemtime($archivo);
		if(@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $fecha_mod || trim($_SERVER['HTTP_IF_NONE_MATCH']) == $hash) {
			header('Etag: '.$hash, true, 304);
			exit;
		}
		header("Last-Modified: ".date("r", $fecha_mod));
		header("Etag: ".$hash);
		$archivo_size = @getimagesize($archivo);
		header('Content-Type: '.$archivo_size['mime']);
		header('Content-Length: '.filesize($archivo));
		readfile($archivo);
		exit;
	}

exit;
/*
	$archivo_arr = explode("/", $path);

	$max_ancho = array(2 => 50, 3 => 250, 4 => 40);
	$max_alto = array(2 => 50, 3 => 250, 4 => 40);
	$metodos = array(2 => 'recortar', 3 => 'escalar', 4 => 'recortar');

	$imagen = new Imagen(RUTA_CARPETA.'img/'.$archivo_arr[1]);
	if(!$imagen->dato('error')) {
		$imagen->$metodos[$archivo_arr[0]]($max_ancho[$archivo_arr[0]], $max_alto[$archivo_arr[0]]);
		$imagen->imprimir();
		$imagen->guardar(RUTA_CARPETA.'public_html/img/'.$archivo_arr[0], $archivo_arr[1]);
	}
	else {
		echo $errorno = $imagen->valor('error');
	}
 */
}

?>