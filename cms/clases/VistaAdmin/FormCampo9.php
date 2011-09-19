<?php

/**
 * Booleano
 *
 * @author pablo
 */
class VistaAdmin_FormCampo9 extends VistaAdmin_Form {


	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {

		$nombre_campo = $this->valores[0]['int'] ? "[m][".$this->id."][".$this->valores[0]['id']."]" : "[n][".$this->id."][]";
		/*if(!$cons = $this->mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co LEFT JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = 1 WHERE co.campo_id = ".$this->id." ORDER BY co.id"))
			echo __LINE__." - ".$mysqli->error;
		if($fila = $cons->fetch_row()) {
			$valores_op = explode(";", $this->valores[0]['string']);
			$retorno .= "<ul>";
			do {
				$retorno .= "<li><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".(in_array($fila[0], $valores_op) ? ' checked="checked"' : '')." /> <label for=\"{$this->campo_nombre_pref}{$this->indice}${i}\">{$fila[1]}</label></li>";
				$i++;
			}while($fila = $cons->fetch_row());
			$retorno .= "</ul>";
		*/
		return $this->label(0, $this->campo_id_pref.$this->indice)."
				<td><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".($this->valores[0]['int'] ? ' checked="checked"' : '')." /></td>";
	}

}

?>