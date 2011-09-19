<?php

/**
 * Entero
 *
 * @author pablo
 */
class VistaAdmin_FormCampo11 extends VistaAdmin_Form {


	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {
		$nombre_campo = $this->valores[0]['int'] ? "[m][".$this->id."][".$this->valores[0]['id']."]" : "[n][".$this->id."][]";
//		return $this->label(0, $this->campo_id_pref.$this->indice)."
//				<td><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".($this->valores[0]['int'] ? ' checked="checked"' : '')." /></td>";

        $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		return $this->label(0, $this->campo_id_pref.$this->indice)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['int'])."\" size=\"5\" maxlength=\"4\" /></td>";// tabindex=\"2\"

	}

}
