<?php

/**
 * Imagen
 *
 * @author pablo
 */
class VistaAdmin_FormCampo2 extends VistaAdmin_Form {

	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {

		if($this->extra[0]['m'] == 'recortar')
			$this->nombre .= ' <span>('.$this->extra[0]['an'].'x'.$this->extra[0]['al'].' px)</span>';
		
		$this->indice++;
		$mysqli = BaseDatos::Conectar();
		//$ret = $label;
		//global $mysqli;//, $p_seccion_id;
		$valor = $this->valores[0]['int'] ? $this->valores[0]['int'] : $this->poromision;
		if(!$cons = $mysqli->query("SELECT id, `archivo` FROM `imagenes_orig` WHERE `id` = '{$valor}' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
		if($fila = $cons->fetch_row()) {
			$img_id = $fila[0];
			$img = "icono/0/{$this->id}/{$fila[1]}";
			$etiqueta = "Cambiar";
			$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		}
		else {
			$img_id = false;
			$img = "/img/trans";
			$etiqueta = "Agregar";
			$nombre_campo = "[n][{$this->id}][]";
		}
		$retorno = $this->label(1)."<td><img src=\"".htmlspecialchars($img)."\" id=\"img{$this->indice}\" alt=\"{$fila[0]}\" />
		<div>";
		//<button type=\"button\" onclick=\"modalCrop({imagenId: document.getElementById('{$this->campo_nombre_pref}{$this->indice}').value, atributo: {$this->id}, indice: {$this->indice}})\" id=\"crop{$this->indice}\" ".($img_id ? '' : 'style="display:none;"')." title=\"Cortar imagen\"><img src=\"/img/crop\" alt=\"Cortar imagen\" /></button>
        $retorno .= "
		<button type=\"button\" onclick=\"modalCrop({imagenId: document.getElementById('{$this->campo_nombre_pref}{$this->indice}').value, atributo: {$this->id}, indice: {$this->indice}})\" id=\"crop{$this->indice}\" style=\"display:none;\" title=\"Cortar imagen\"><img src=\"/img/crop\" alt=\"Cortar imagen\" /></button>
		<button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, 'subir_imagen', {$this->indice})\"><span>{$etiqueta}</span></button> <!-- button type=\"button\" onclick=\"abrirModal('./examinar/{$this->id}/{$this->indice}', 680, 450)\"><span>Examinar servidor...</span></button --><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" />
		</div>";
		if($this->extra['prot']) {
			$retorno .= "
		<table>
		";

			$this->campo_id_pref = $this->campo_nombre_pref = "noDato";
			/*
			foreach($this->extra['prot'] AS $prot_k => $prot_v)
			{
			//$this->id = $a['id'];
			$this->sugerido = 1;
			$this->unico = 1;
			$this->tipo = $prot_k;
			$this->subtipo = $prot_v;
			$this->nombre = $nombres_anon[$prot_k.$prot_v];
			//$this->identificador = $a['identificador'];
			//$this->poromision = $a['poromision'];
			//$this->string = $a['string'];
			//$this->date = $a['date'];
			//$this->text = $a['text'];
			//$this->int = $a['int'];
			//$this->num = $a['num'];
			//$this->extra = $a['extra'];
			//$this->superior = $this->id;
			//$this->nodo_tipo = $a['nodo_tipo'];
			//$this->valores = $valores[$a['id']];

			$retorno .= $this->imprimir();
			}
			*/
			$this->campo_id_pref = $this->campo_nombre_pref = "dato";
			$retorno .= "</table>";
		}

		$retorno .= "</td>";
		return $retorno;
		//$bsq = ($_SESSION['permisos'][$p_seccion_id] < 4) ? "AND bsq = '".$_SESSION['usuario_id']."'" : null;
		//$bsq = false;
	}
}
