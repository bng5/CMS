<?php

/**********************************************************************
 *
 * Versión: 2.2
 * Rev: 2008-08
 *
 *********************************************************************/

class Imagen {

	const ARCHIVO_NO_ENCONTRADO = 1;
	const NO_ES_IMAGEN = 2;
	const FORMATO_NO_VALIDO = 3;
	const ERRORES_EN_IMAGEN = 4;
	const ERROR_INESPERADO = 5;

	protected $formatos, $archivo, $ruta, $mime, $ext, $fuente, $errorno, $ancho, $alto, $proporcion, $datos;

	public function __construct($archivo = false) {
		$this->archivo = basename($archivo);
		$this->ruta = $archivo;
		$this->errorno = false;
		$this->datos = array('archivo', 'ruta', 'mime', 'ext', 'fuente', 'errorno', 'ancho', 'alto', 'proporcion');
		$this->errores = array(1 => "No se encontró el archivo.", "El archivo no es una imagen.", "La imagen debe ser del tipo jpeg (jpg), gif o png.", "La imagen contiene errores.", "Ocurrió un error inesperado.");

		$this->formatos = array("gif" => array("image/gif", "imagegif"), "jpg" => array("image/jpeg", "imagejpeg"), "jpeg" => array("image/jpeg", "imagejpeg"), "png" => array("image/png", "imagepng"));
		$this->ext = "png";
		$this->mime = $this->formatos['png'];
		if($archivo && file_exists($archivo)) {
			if($archivo_size = getimagesize($this->ruta)) {
				$this->mime = $archivo_size['mime'];
				if($this->mime == "image/gif") {
					$this->ext = "gif";
					if(!$this->fuente = @imagecreatefromgif($this->ruta))
						$this->errorno = self::ERRORES_EN_IMAGEN;
				}
				elseif($this->mime == "image/jpeg") {
					$this->ext = "jpg";
					if(!$this->fuente = @imagecreatefromjpeg($this->ruta))
						$this->errorno = self::ERRORES_EN_IMAGEN;
				}
				elseif($this->mime == "image/png") {
					$this->ext = "png";
					if($this->fuente = @imagecreatefrompng($this->ruta)) {
						imagealphablending($this->fuente, false);
						imagesavealpha($this->fuente, true);
					}
					else
						$this->errorno = self::ERRORES_EN_IMAGEN;
				}
				else
					$this->errorno = self::FORMATO_NO_VALIDO;
			}
			else
				$this->errorno = self::FORMATO_NO_VALIDO;
		}
		else
			$this->errorno = 1;
		
		if($this->fuente) {
			$this->ancho = $archivo_size[0];//@imagesx($this->fuente);
			$this->alto = $archivo_size[1];//@imagesy($this->fuente);
		}
		else {
			if(!$this->errorno)
					$this->errorno = 5;
			$this->ancho = 200;
			$this->alto = 150;
			$this->fuente = imagecreate($this->ancho, $this->alto);
			$bg = imagecolorallocate($this->fuente, 224, 224, 224);
			$txtcolor = imagecolorallocate($this->fuente, 128, 0, 0);
			$texto = wordwrap($this->errores[$this->errorno], 24);
			$texto_arr = explode("\n", $texto);
			$txt_y = 5;
			for($i = 0; $i < count($texto_arr); $i++) {
				imagestring($this->fuente, 3, 10, $txt_y, $texto_arr[$i], $txtcolor);
				$txt_y += 13;
			}
		}
		$this->proporcion = ($this->ancho / $this->alto);
	}
	
	public function __destruct() {
		imagedestroy($this->fuente);
	}

	/**
	 * Getter
	 *
	 * @param string $attr
	 * @return mixed
	 */
	public function __get($attr) {
		return $this->dato($attr);
	}

	public static function crearDesdeArchivo($ruta_archivo) {
		return new self($ruta_archivo);
	}

	/**
	 * Getter
	 *
	 * @param string $dato
	 * @return mixed
	 */
	public function dato($dato = false) {
		if($dato)
			return $this->$dato;
		else {
			foreach($this->datos AS $dato)
				$datos_arr[$dato] = $this->$dato;
			return $datos_arr;
		}
	}

	public function recortar($max_ancho = 0, $max_alto = 0, $ignorado1 = false, $ignorado2 = false) {
		if((!$max_ancho || !$max_alto) || ($this->ancho <= $max_ancho && $this->alto <= $max_alto))
			return false;
		$proporcion_pred = ($max_ancho / $max_alto);
		$xdest = 0;
		$ydest = 0;
		if($this->proporcion >= $proporcion_pred)
			$this->escalar(0, $max_alto);
		else
			$this->escalar($max_ancho);
		$max_ancho = ($this->ancho > $max_ancho) ? $max_ancho : $this->ancho;
		$max_alto = ($this->alto > $max_alto) ? $max_alto : $this->alto;
		$xdest -= round(($this->ancho - $max_ancho) / 2);
		$ydest -= round(($this->alto - $max_alto) / 2);
		$this->crop($max_ancho, $max_alto, $xdest, $ydest);
	}

	public function crop($max_ancho, $max_alto, $xdest, $ydest) {
		$n_imagen = imagecreatetruecolor($max_ancho, $max_alto);
		imagealphablending($n_imagen, false);
		imagesavealpha($n_imagen, true);
		$bg = imagecolorallocatealpha($n_imagen, 255, 255, 255, 127);
		imagefill($n_imagen, 0, 0, $bg);
		imagecopyresampled($n_imagen, $this->fuente, $xdest, $ydest, 0, 0, $this->ancho, $this->alto, $this->ancho, $this->alto);
		$this->ancho = $max_ancho;
		$this->alto = $max_alto;
		$this->fuente = $n_imagen;
		$this->proporcion = ($this->ancho / $this->alto);
	}

	public function escalar($max_ancho = 0, $max_alto = 0, $min_ancho = 0, $min_alto = 0) {
		if(!$max_ancho && !$max_alto)
			return false;
		elseif(!$max_ancho xor !$max_alto)
			$disp = $max_ancho ? "h" : "v";
		else
			$disp = ($this->proporcion >= ($max_ancho / $max_alto)) ? "h" : "v";
		if($disp == "h" && $this->ancho > $max_ancho) {
			$div = ($this->ancho / $max_ancho);
			$ancho = $max_ancho;
			$alto = ceil($this->alto / $div);
		}
		elseif($disp == "v" && $this->alto > $max_alto) {
			$div = ($this->alto / $max_alto);
			$alto = $max_alto;
			$ancho = ceil($this->ancho / $div);
		}
		if($min_ancho && $ancho < $min_ancho) {
			$this->recortar($min_ancho, $alto);
			return;
		}
		elseif($min_alto && $alto < $min_alto) {
			$this->recortar($ancho, $min_alto);
			return;
		}
		if($ancho && $alto) {
			$n_imagen = imagecreatetruecolor($ancho, $alto);
			imagealphablending($n_imagen, false);
			imagesavealpha($n_imagen, true);
			$bg = imagecolorallocatealpha($n_imagen, 255, 255, 255, 127);
			imagefill($n_imagen, 0, 0, $bg);
			imagecopyresampled($n_imagen, $this->fuente, 0, 0, 0, 0, $ancho, $alto, $this->ancho, $this->alto);//
			$this->ancho = $ancho;
			$this->alto = $alto;
			$this->fuente = $n_imagen;
		}
	}
	
	public function marcaDeAgua($archivo, $posx = "centro", $posy = "centro") {
		//$marca = new Imagen($archivo);
		if(!$posx)
			$posx = "centro";
		if(!$posy)
			$posy = "centro";
		$marca = imagecreatefrompng($archivo);
		$ancho = @imagesx($marca);
		$alto = @imagesy($marca);
		//imagealphablending($marca, false);
		//imagesavealpha($marca, true);
		if($posx == "centro")
			$posx = round(($this->ancho / 2) - ($ancho/2));
		elseif($posx < 0)
			$posx = ($this->ancho - $ancho + $posx);
		if($posy == "centro")
			$posy = round(($this->alto / 2) - ($alto/2));
		elseif($posy < 0)
			$posy = ($this->alto - $alto + $posy);
		imagecopy($this->fuente, $marca, $posx, $posy, 0, 0, $ancho, $alto);
		imagedestroy($marca);
		/*
		if($posx == "centro") $posx = round(($this->ancho / 2) - ($marca->ancho/2));
		elseif($posx < 0) $posx = ($this->ancho - $marca->ancho + $posx);
		if($posy == "centro") $posy = round(($this->alto / 2) - ($marca->alto/2));
		elseif($posy < 0) $posy = ($this->alto - $marca->alto + $posy);
		imagecopymerge($this->fuente, $marca->fuente, $posx, $posy, 0, 0, $marca->ancho, $marca->alto, 100);
		imagedestroy($marca->fuente);
		*/
	}
	
	public function imprimir($formato = false) {
		if(!$this->formatos[$formato])
			$formato = $this->ext;
		header('content-type: '.$this->formatos[$formato][0]);
		$this->formatos[$formato][1]($this->fuente);
	}
	
	public function imagen($formato = false) {
		if(!$this->formatos[$formato])
			$formato = $this->ext;
		ob_start();
		$this->formatos[$formato][1]($this->fuente);
		$contenido = ob_get_contents();
		ob_end_clean();
		return $contenido;
	}
	
	public function guardar($ruta = "./", $nombre = FALSE, $formato = FALSE, $remplazar = false) {
		$enthtml = array (" " => "_", "?" => "", "#" => "", "\\" => "", "/" => "");
		if(substr($ruta, -1) != "/")
			$ruta .= "/";
		if(!$nombre)
			$nombre = $this->archivo;
		$nombre = strtr($nombre, $enthtml);

		$nombre_arr = explode(".", $nombre);
		$bsqformato = array($formato, end($nombre_arr), $this->ext);
		$i = 0;
		do {
			$formato = array_shift($bsqformato);
			$i++;
		}while(!$this->formatos[$formato]);
		$ext = ($this->formatos[end($nombre_arr)][0] == $this->formatos[$formato][0]) ? ".".array_pop($nombre_arr) : ".".$formato;
		$nombre = implode(".", $nombre_arr);

		if(strlen($nombre) > 28)
			$nombre = substr($nombre, 0, 28);
		if(file_exists($ruta.$nombre.$ext)) {
			if($remplazar) {
				unlink($ruta.$nombre.$ext);
			}
			else {
				if(strlen($nombre) > 22)
					$nombre = substr($nombre, 0, 22);
				$nombre = basename(tempnam($ruta, $nombre));
				$tmpname = true;
			}
		}
		$this->formatos[$formato][1]($this->fuente, $ruta.$nombre.$ext);
		if($tmpname)
			unlink($ruta.$nombre);
		return $ruta.$nombre.$ext;
	}
}
