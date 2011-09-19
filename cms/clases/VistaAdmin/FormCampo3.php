<?php

/**
 * Archivo
 *
 * @author pablo
 */
class VistaAdmin_FormCampo3 extends VistaAdmin_Form {

	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {
		
		
		$this->indice++;


		$retorno = "";
		if(is_array($this->valores)) {
			foreach($this->valores AS $nu_v)
				$acons[$nu_v['int']] = $nu_v['id'];
		}
		if(is_array($acons)) {
			$valor = implode("' OR id = '", array_keys($acons));
		}
		else
			$valor = $this->poromision;
		$mysqli = BaseDatos::Conectar();
		if(!$cons = $mysqli->query("SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' ORDER BY id"))
			echo __LINE__." - ".$mysqli->error;
		if($fila = $cons->fetch_row()) {
			$etiqueta = "Cambiar";
			if($this->unico) {
				$img_id = $fila[0];
				$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
				$retorno .= "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button>";
			}
			else {
				$retorno = "<ul id=\"lista_{$this->indice}\">";
				$i = 1;
				do {
					$img_id = $fila[0];
					$nombre_campo = "[m][{$this->id}][{$acons[$img_id]}]";
					$retorno .= "<li><span id=\"archivo{$this->indice}_{$i}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" value=\"{$img_id}\" /></li>";
					$i++;
				}while($fila = $cons->fetch_row());
				$retorno .= "</ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";//onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\"
			}
		}
		else {
			$img_id = false;
			$etiqueta = "Agregar";
			$nombre_campo = "[n][{$this->id}][]";
		}
		if(empty($retorno))
			$retorno = $this->unico ? "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"img/trans\" alt=\"\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>{$etiqueta}</span></button>" : "<ul id=\"lista_{$this->indice}\"></ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";

		return $this->label(1)."<td>{$retorno}</td>";
	}
}

?>