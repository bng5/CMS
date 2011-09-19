<?php

/**
 * Enlace
 *
 * @author pablo
 */
class VistaAdmin_FormCampo8 extends VistaAdmin_Form {


	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {
		
		$prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
		$sel_prot[$this->valores[0]['int']] = " selected=\"selected\"";

		$nombre_campo = $this->valores[0]['text'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		//<option value=\"2\"{$sel_prot[2]}>https://</option><option value=\"3\"{$sel_prot[3]}>ftp://</option><option value=\"4\"{$sel_prot[4]}>gopher://</option><option value=\"5\"{$sel_prot[5]}>mailto:</option><!-- option value=\"6\">wais</option --></select>
		$tot_prot = count($prot);
		if(!$tot_prot) {
			$prot = array(1);
			$tot_prot = 1;
		}
		if($tot_prot == 1)
			$prot_str = "<input type=\"hidden\" value=\"{$prot_arr[current($prot)]}\" /><input type=\"hidden\" name=\"prot[{$this->id}]\" value=\"".current($prot)."\" />".$prot_arr[current($prot)];
		else {
			$prot_str = "<select name=\"prot[{$this->id}]\">";
			foreach($prot AS $prot_k => $prot_v)
				$prot_str .= "<option value=\"${prot_k}\"{$sel_prot[$prot_k]}>${prot_arr[$prot_k]}</option>";
			$prot_str .= "</select>";
		}

		return $this->label(0, $this->campo_id_pref.$this->indice)."<td>{$prot_str} <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['text'])."\" size=\"45\" /> <a href=\"#\" target=\"_blank\" onclick=\"return abrirEnlace(this)\"><img src=\"/img/externo\" alt=\"Abrir enlace\" class=\"enlace_img\" /></a></td>";// tabindex=\"2\"
	}

}

?>