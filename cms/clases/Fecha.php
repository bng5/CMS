<?php

class Fecha extends DateTime {
	public function __construct($fecha) {
		parent::__construct($fecha);
		$this->anyo = $this->format('Y');
		$this->anyo2d = $this->format('y');
		$this->mes = $this->format('n');
		$this->dia = $this->format('j');
		$this->horas12 = $this->format('g');
		$this->horas24 = $this->format('G');
		$this->minutos = $this->format('i');
		$this->segundos = $this->format('s');
		$this->ampm = $this->format("a");
		$this->AMPM = $this->format("A");
		$this->diasemana = $this->format("w");
	}
	
	public function Formatear($formato, $leng_codigo) {
		//include_once(RUTA_CARPETA.'leng/fechas.es');
		global $bng5_texto;
		$texto = is_array($leng_codigo) ? $leng_codigo : $bng5_texto[$leng_codigo];
		//$recibe = array('%d', '%j', '%D', '%l', '%m', '%n', '%F', '%M', '%Y', '%y', '%G', '%H', '%g', '%h', '%i', '%s', '%a', '%A');
		//$devuelve = array('%1$s', '%1$d', '%2$s', '%3$s', '%4$s', '%4$d', '%5$s', '%6$s', '%7$d', '%8$s', '%9$d', '%9$s', '%10$d', '%10$s', '%11$s', '%12$s',	'%13$s', '%14$s');
		$recibe = array('%d', '%D', '%j', '%l', '%F', '%m', '%M', '%n', '%Y', '%y', '%a', '%A', '%g', '%G', '%h', '%H', '%i', '%s');
		$devuelve = array('%3$02d', '%11$s', '%3$d', '%9$s', '%8$s', '%2$02d', '%10$s', '%2$d', '%1$d', '%12$d', '%13$s', '%14$s', '%4$d', '%5$d', '%4$02d', '%5$02d', '%6$d', '%7$d');

		$formato = str_replace($recibe, $devuelve, $formato);
		$diasemana = $texto['dias'][$this->diasemana];
		$nombremes = $texto['meses'][$this->mes];
		$diasemanacorto = $texto['diasCorto'][$this->diasemana];
		$nombremescorto = $texto['mesesCorto'][$this->mes];
		return sprintf($formato, $this->anyo, $this->mes, $this->dia, $this->horas12, $this->horas24, $this->minutos, $this->segundos, $nombremes, $diasemana, $nombremescorto, $diasemanacorto, $this->anyo2d, $this->ampm, $this->AMPM);
	}
	
//	public static function Formatear($formato, $texto) {
//		include_once(RUTA_CARPETA.'leng/fechas.es');
//		$recibe = array('%d', '%j', '%D', '%l', '%m', '%n', '%F', '%M', '%Y', '%y', '%G', '%H', '%g', '%h', '%i', '%s', '%a', '%A');
//		$devuelve = array('%1$s', '%1$d', '%2$s', '%3$s', '%4$s', '%4$d', '%5$s', '%6$s', '%7$d', '%8$s', '%9$d', '%9$s', '%10$d', '%10$s', '%11$s', '%12$s',	'%13$s', '%14$s');
//		$formato = str_replace($recibe, $devuelve, $formato);
//	}

	public function AdFormato($formato) {
		$recibe = array('%d', '%j', '%D', '%l', '%m', '%n', '%F', '%M', '%Y', '%y', '%G', '%H', '%g', '%h', '%i', '%s', '%a', '%A');
		$devuelve = array('%1$s', '%1$d', '%2$s', '%3$s', '%4$s', '%4$d', '%5$s', '%6$s', '%7$d', '%8$s', '%9$d', '%9$s', '%10$d', '%10$s', '%11$s', '%12$s',	'%13$s', '%14$s');
		$this->formato = str_replace($recibe, $devuelve, $formato);
	}

	/**
    * remplaza la funcion global formato_fecha
    */
	public static function formato($fecha, $formato = TRUE, $hora = TRUE) {
		global $texto;
		if(empty($fecha))
			$form = "No especificada";
		else {
			$meses = $texto['meses'];
			$dias = $texto['dias'];
			$mk_fecha = @mktime(0, 0, 0, mb_substr($fecha, 5, 2), mb_substr($fecha, 8, 2), mb_substr($fecha, 0, 4));
			if($formato == TRUE)
				$form = $dias[date(w, $mk_fecha)]." ".date(j, $mk_fecha)." de ".$meses[date(n, $mk_fecha)]." de ".date(Y,$mk_fecha);
			else
				$form = mb_substr($dias[date(w, $mk_fecha)], 0, 3)." ".date(j, $mk_fecha)."-".mb_substr($meses[date(n, $mk_fecha)], 0, 3)."-".date(Y,$mk_fecha);
			if($hora == TRUE && strlen($fecha) > 10) {
				$mk_fecha += mktime(substr($fecha, 11, 2)-3, substr($fecha, 14, 2), substr($fecha, 17, 2), 0, 0, 0);
				$form .= ", ".date("G:i", $mk_fecha)." hs.";
			}
		}
		return $form;
	}

}

/*
$formato1 = $_POST['formato1'] ? $_POST['formato1'] : '%1$s %2$s de %3 de %4';
$formato2 = $_POST['formato2'] ? $_POST['formato2'] : '%1$s %2$s de %3 de %4';
$formatos = array(1 => $formato1, 2 => $formato2, 3 => "c", 4 => "r", 5 => "U");
$sel_formato[$_POST['formato']] = " selected=\"selected\"";
*/
