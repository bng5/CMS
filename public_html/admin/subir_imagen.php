<?php

$ventananovisible = true;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');
//include('../../inc/class_imagen.php');

/*********************************/
header("Content-type: application/x-javascript; charset=utf-8");//text/plain
/*********************************/


$mysqli = BaseDatos::Conectar();
if(!$consulta = $mysqli->query("SELECT extra FROM items_atributos i WHERE id = {$_POST['atributo']}"))
	die("\n".__LINE__." mySql: ".$mysqli->error);
if($fila = $consulta->fetch_row()) {
	$extra = unserialize($fila[0]);
	/*
	print_r($extra);
	print_r($_POST);
	print_r($_GET);
	print_r($_FILES);
	exit;
	*/

	$carpeta_imagenes_raiz = RUTA_CARPETA."public_html/img/";
	$carpeta_imagenes = array("0/", "1/");
	$carpeta_originales = RUTA_CARPETA.'img/';
	$errorno = "false";
	$n_imagen_archivo = "false";
	$imagen_id = "false";
	if($_POST['retorno']) {
		$atributo = "archivo";
		$funcionJS = $_POST['retorno'];
	}
	elseif($_GET['remplazo']) {
		$atributo = "remplazar";
		$funcionJS = "remplazoImg";
	}
	else {
		$atributo = "archivo";
		$funcionJS = "imgCargada";
	}

	if($_FILES[$atributo]['error'] == 0) {
		//if(!empty($_POST['borrar'])) @unlink($carpeta_imagenes.$_POST['borrar']);
		$archivo = $_FILES[$atributo]['tmp_name'];
		//$archivo_nombre = strtr(strtolower($HTTP_POST_FILES[$atributo]['name']), $enthtml);
		$archivo_nombre = $_FILES[$atributo]['name'];
		$imagen = Imagen::crearDesdeArchivo($archivo);
		if(!$imagen->dato('errorno')) {
			if(file_exists($carpeta_originales.$archivo_nombre)) {
				$nombre_arr = explode(".", $archivo_nombre);
				if(count($nombre_arr) > 1)
					$ext = array_pop($nombre_arr);
				$archivo_nombre = implode(".", $nombre_arr);
				//$bsqformato = array($formato, end($nombre_arr), $this->ext);
				if(strlen($archivo_nombre) > 22)
					$archivo_nombre = substr($archivo_nombre, 0, 22);
				$archivo_nombre = basename(tempnam($carpeta_originales, $archivo_nombre));
				//unlink($carpeta_originales.$archivo_nombre);
				if($ext)
					$archivo_nombre .= '.'.$ext;
			}
			$ancho[] = $imagen->dato('ancho');
			$alto[] = $imagen->dato('alto');
			$peso[] = filesize($archivo);
			$formato = $imagen->dato('mime');
			$md5 = md5_file($archivo);

            foreach($extra AS $i => $metodos) {
                if(!is_int($i))
                    break;
                $errorno = "false";
                /*
                if($metodos['m'] == 'recortar') {
                    if($imagen->proporcion >= ($metodos['an'] / $metodos['al'])) {
                        $imagen->escalar(0, $metodos['al']);
                    }
                    else {
                        $imagen->escalar($metodos['an']);
                    }
                }
                else
                */
                    $imagen->$metodos['m']($metodos['an'], $metodos['al'], $metodos['anmin'], $metodos['almin']);
                if(is_array($metodos['wm']))
                    $imagen->marcaDeAgua('../img/5/'.$metodos['wm'][0], $metodos['wm'][1], $metodos['wm'][2]);
                $n_imagen_ruta = $imagen->guardar($carpeta_imagenes_raiz.$carpeta_imagenes[$i].$_POST['atributo'], $archivo_nombre);
                $archivo_nombre = basename($n_imagen_ruta);
                $n_imagen_archivo = "'{$archivo_nombre}'";
                //if(!$ancho)
                // {
                $ancho[] = $imagen->dato('ancho');
                $alto[] = $imagen->dato('alto');
                $peso[] = filesize($n_imagen_ruta);
                // }
                //chmod($n_imagen_ruta, 0666);
            }

			$mysqli = BaseDatos::Conectar();
			$mysqli->query("INSERT INTO `imagenes_orig` (`archivo`, `ancho`, `alto`, `peso`, `formato`, `hash`) VALUES ('{$archivo_nombre}', '{$ancho[0]}', '{$alto[0]}', '{$peso[0]}', '{$formato}', '{$md5}')");
			$imagen_id = $mysqli->insert_id;
			$mysqli->query("INSERT INTO `imagenes_a_atributos` (`imagen_id`, `atributo_id`, `ancho`, `alto`, `peso`, `ancho_m`, `alto_m`, `peso_m`) VALUES ('{$imagen_id}', '{$_POST['atributo']}', '{$ancho[1]}', '{$alto[1]}', '{$peso[1]}', '{$ancho[2]}', '{$alto[2]}', '{$peso[2]}')");
			//$imagen_id = $mysqli->insert_id;
			//if(!$mysqli->query("INSERT INTO `imagenes` (`archivo`, `nombre`, `fecha`, `ancho`, `alto`, `peso`, `formato`, `hash`, `ancho_m`, `alto_m`, `peso_m`) VALUES ('${archivo_nombre}', '${archivo_nombre}', now(), '{$ancho[1]}', '{$alto[1]}', '{$peso[0]}', '${formato}', '${md5}', '{$ancho[2]}', '{$alto[2]}', '{$peso[1]}')")) die("\n".__LINE__." mySql: ".$mysqli->error);

			move_uploaded_file($archivo, $carpeta_originales.$archivo_nombre);
		}
		else
			$errorno = "1".$imagen->dato('errorno');
	}
	else
		$errorno = $_FILES[$atributo]['error'];
//$funcionJS = 'modalCrop';
	echo "{funcion : {$funcionJS}, errorno : {$errorno}, imagenId : {$imagen_id}, imagenArchivo : {$n_imagen_archivo}, frame : '{$_REQUEST['frame']}', indice : {$_POST['indice']}, atributo : {$_POST['atributo']}}";
	/*
	echo "&lt;body onload=\"parent.${funcionJS}( ${errorno}, ${imagen_id}, ${n_imagen_archivo}, '${_REQUEST['frame']}', {$_POST['indice']});\"&gt;";

	print_r($_POST);
	print_r($_GET);
	print_r($_FILES);
	echo "


	../img/0/{$_POST['atributo']}
	../img/1/{$_POST['atributo']}
	";
				//</pre>

	<?php
	*/

}
else {
  echo "{funcion : {$funcionJS}, errorno : 16, imagenId : {$imagen_id}, imagenArchivo : {$n_imagen_archivo}, frame : '{$_REQUEST['frame']}', indice : {$_POST['indice']}, atributo : {$_POST['atributo']}}";
}

?>