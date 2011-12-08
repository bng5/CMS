<?php

/**
 * Fecha y hora
 *
 * @author pablo
 */
class VistaAdmin_FormCampo7 extends VistaAdmin_Form {

	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {
		global $bng5_texto;
		include_once 'leng/fechas.es';
		// fecha
		if(!$this->valores)// && $this->extra['current'])
			$valor = date("Y-m-d G:i:s");
		else
			$valor = $this->valores[0]['date'];

		$fecha = new Fecha($valor);
		$formatoMst = $this->extra['f'] ? $this->extra['f'] : "%l, %d de %F de %Y";
		include_once 'leng/fechas.es';
		$fechaMst = $fecha->Formatear($formatoMst, 'es');
		$mostrarHora = "false";
		$formato = "%Y-%m-%d";
		//$formatoMst = "%A, %d de %B de %Y";

		/*
		if($this->subtipo == 1) {
			//$fecha = $this->valor ? date("Y-m-d", $this->valor) : "";
			$valor = substr($valor, 0, 10);
			$fechaMst = formato_fecha($valor, true, false);
			$formato = "%Y-%m-%d";
			$formatoMst = "%A, %d de %B de %Y";
			$mostrarHora = "false";
		}
		// fecha y hora
		else {
			//$fecha = $this->valor ? date("Y-m-d G:i", $this->valor) : "";
			$fechaMst = formato_fecha($valor);
			$formato = "%Y-%m-%d %H:%M";
			$formatoMst = "%A, %d de %B de %Y, %H:%M hs.";
			$mostrarHora = "true";
		}
		*/
		// onclick=\"return showCalendar('fecha_fin', '%A, %B %e, %Y');\"

		$nombre_campo = $this->valores ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		return $this->label(1).'<td><span id="mostrar_fecha'.$this->indice.'">'.$fechaMst.'</span>&nbsp;&nbsp;<img src="img/icono_calendario" id="tn_calendario'.$this->indice.'" style="cursor: pointer;" title="Abrir calendario" alt="Abrir calendario" /><input type="hidden" name="'.$this->campo_nombre_pref.$nombre_campo.'" value="'.$valor.'" id="fecha'.$this->indice.'" />
<script type="text/javascript">
//<![CDATA[
Calendar.setup({inputField : "fecha'.$this->indice.'", ifFormat : "'.$formato.'", displayArea : "mostrar_fecha'.$this->indice.'", daFormat : "'.$formatoMst.'", button : "tn_calendario'.$this->indice.'", showsTime : '.$mostrarHora.'});
//]]>
</script></td>
';
	}

}

?>