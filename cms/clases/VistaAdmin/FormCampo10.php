<?php

/**
 * Selector
 *
 * @author pablo
 */
class VistaAdmin_FormCampo10 extends VistaAdmin_Form {


	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {

		$nombre_campo = $this->valores[0]['int'] ? "[m][".$this->id."][".$this->valores[0]['id']."]" : "[n][".$this->id."][]";
		//$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";

		//return $this->label(0, $this->campo_id_pref.$this->indice)."
		//		<td><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".($this->valores[0]['int'] ? ' checked="checked"' : '')." /></td>";

//		$mysqli = BaseDatos::Conectar();
//		if(!$cons_vista = $mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = 1 WHERE co.campo_id = {$this->id} ORDER BY co.id"))
//			echo __LINE__." - ".$mysqli->error;
//		if($fila_vista = $cons_vista->fetch_row()) {
//			$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
//			$ret = $this->label(1)."<td><select name=\"{$this->campo_nombre_pref}{$nombre_campo}\">";
//			do {
//				$ret .= "<option value=\"{$fila_vista[0]}\"";
//				if($fila_vista[0] == $this->valores[0]['int'])
//					$ret .= " selected=\"selected\"";
//				$ret .= ">{$fila_vista[1]}</option>";
//			}while($fila_vista = $cons_vista->fetch_row());
//			$ret .= "</select></td>";
//			$cons_vista->close();


		$ret = $this->label(1)."<td><select name=\"{$this->campo_nombre_pref}{$nombre_campo}\">";
		foreach($this->extra AS $k => $v) {
			$ret .= "<option value=\"{$k}\"";
			if($k == $this->valores[0]['int'])
				$ret .= " selected=\"selected\"";
			$ret .= ">{$v}</option>";
		}
		return $ret."</select></td>";
	}

}
