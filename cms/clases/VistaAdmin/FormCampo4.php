<?php

/**
 * Galería
 *
 * @author pablo
 */
class VistaAdmin_FormCampo4 extends VistaAdmin_Form {

	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {

		$mysqli = BaseDatos::Conectar();
		$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		$valores = array();
		$imagenes = array();
		$retorno = '<td colspan="2"><input type="hidden" name="'.$this->campo_nombre_pref.$nombre_campo.'" value="'.$this->valores[0]['int'].'" /><div id="galeria">';
		if(!$consulta_imgs = $mysqli->query("SELECT gi.imagen_id, gi.estado, io.archivo, io.formato, gi.orden FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id WHERE gi.galeria_id = '".$this->valores[0]['int']."' ORDER BY gi.orden"))
			die("\n".__LINE__." mySql: ".$mysqli->error);
		if($total_imgs = $consulta_imgs->num_rows) {
			while($fila_imgs = $consulta_imgs->fetch_row()) {
				$imagenes[$fila_imgs[0]] = array();
				$htmlsc = htmlspecialchars($fila_imgs[2]);
				//echo "<input type=\"hidden\" name=\"img_estado[{$fila_imgs[0]}]\" value=\"{$fila_imgs[5]}\" /><input type=\"hidden\" name=\"img_titulo[{$fila_imgs[0]}]\" value=\"{$fila_imgs[2]}\" /><input type=\"hidden\" name=\"img_fecha[{$fila_imgs[0]}]\" id=\"img_fecha{$fila_imgs[0]}\" value=\"{$fila_imgs[4]}\" /><textarea name=\"img_texto[{$fila_imgs[0]}]\" rows=\"6\" cols=\"40\" class=\"oculto\">{$fila_imgs[3]}</textarea>";
				$retorno .= "<span></span><input type=\"image\" name=\"imagen[]\" src=\"icono/4/{$htmlsc}\" value=\"{$fila_imgs[0]}\" onclick=\"return false\" onmousedown=\"mover(event);\" onmouseup=\"desplegarImg(this)\" title=\"{$htmlsc}\" alt=\"{$htmlsc}\" />";
			}
			$consulta_imgs->close();

			if(!$cons_valores = $mysqli->query("SELECT imagen_id, atributo_id, leng_id, id, `string`, `date`, `text`, `int`, `num` FROM galerias_imagenes_valores g WHERE galeria_id = {$this->valores[0]['int']}"))
				echo __LINE__." - ".$mysqli->error;
			if($fila_valores = $cons_valores->fetch_assoc()) {
				do {
					$imagen = array_shift($fila_valores);
					$img_atributo_id = array_shift($fila_valores);
					$img_leng_id = array_shift($fila_valores);
					if($img_leng_id)
						$imagenes[$imagen][$img_atributo_id][$img_leng_id] = $fila_valores;
					else
						$imagenes[$imagen][$img_atributo_id][] = $fila_valores;
				}while($fila_valores = $cons_valores->fetch_assoc());
				$cons_valores->close();
			}
		}
		//SELECT gi.imagen_id, gi.estado, i.archivo, i.formato, i.peso, gi.orden FROM galerias_imagenes gi JOIN imagenes i ON gi.imagen_id = i.id WHERE gi.galeria_id = '{$this->valores[0]['int']}' ORDER BY gi.orden
		$retorno .= "<span></span></div><input type=\"image\" name=\"eliminarImg\" src=\"img/papelera\" alt=\"Eliminar\" title=\"Arrastre hasta aquí para eliminar\" style=\"background:none;border:none;\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, 'subir_imagen_gal', {$this->indice})\"><span>Agregar</span></button><fieldset style=\"display:none;\"><legend></legend><img src=\"img/trans\" alt=\"\"	/>";
		/*********************************************************************/
		//
		$atributos = array();
		/*	  if(!$atributos_tipos = $this->mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, subitems_supatributos_a_atributos isaa LEFT JOIN subitems_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND sup_atributo_id = '{$this->id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
		if($fila_at = $atributos_tipos->fetch_assoc())
		{
		do
		{
		$attr_id = array_shift($fila_at);
		$atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
		}while($fila_at = $atributos_tipos->fetch_assoc());
		$atributos_tipos->close();
		}
		//print_r($atributos);
		*/
		if(count($atributos)) {
			$js_leng = array();
			foreach($this->lengs AS $leng_id => $leng_cod)
				$js_leng[] = "${leng_id} : '${leng_cod}'";
			$retorno .= "<script type=\"text/javascript\">\n//<![CDATA[
			var lenguajes = {".implode(", ", $js_leng)."}
			ImgGalObj.prototype.ImgGalTablaInfo = function()
			{
			this.tabla = document.createElement('table');
			this.tabla.setAttribute('id', 'muestra_'+this.id);
			tbody = document.createElement('tbody');
			this.tabla.appendChild(tbody);
			this.tabla.style.display = 'none';\n";
			$this->js = new jsCampo('galimg', $valores_k);
			foreach($atributos AS $k => $a) {
				$this->js->id = $k;
				//$this->js->sugerido = $a['sugerido'];
				//$this->js->unico = $a['unico'];
				$this->js->tipo = $a['tipo'];
				//$this->js->subtipo = $a['subtipo'];
				$this->js->nombre = $a['nombre'];
				//$this->js->poromision = $a['poromision'];
				//$this->js->extra = $a['extra'];
				//$this->js->valores = $valores_arr[$k];
				$retorno .= $this->js->imprimir();
			}
			$retorno .= "\n}\n//]]>\n</script>";

			while(list($valores_k, $valores_arr) = each($imagenes)) {
				$this->subitem = new formCampo2('galimg', $valores_k);//$this->id);
				//<pre>".var_export($valores_arr, true)."\nmuestra{$this->valores[0]['int']}_${valores_k}</pre>
				$retorno .= "<table id=\"muestra_${valores_k}\" style=\"display:none;\">";
				foreach($atributos AS $k => $a) {
					$this->subitem->id = $k;
					$this->subitem->sugerido = $a['sugerido'];
					$this->subitem->unico = $a['unico'];
					$this->subitem->tipo = $a['tipo'];
					$this->subitem->subtipo = $a['subtipo'];
					$this->subitem->nombre = $a['nombre'];
					$this->subitem->poromision = $a['poromision'];
					$this->subitem->extra = $a['extra'];
					//$formcampo->identificador = $a['identificador'];
					$this->subitem->valores = $valores_arr[$k];
					$retorno .= "<tr>".$this->subitem->imprimir()."</tr>";
				}
				$retorno .= "</table>";
			}
		}
		/*********************************************************************/

		/*
		foreach($this->lengs AS $leng_id => $leng_cod)
		{
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
		if($multi_l) $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"etiqueta_idioma\"><tt>(${leng_cod})</tt></label>&nbsp;";
		$retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"100\" />";
		if($multi_l) $retorno .= "</li>";
		$i++;
		}
		*/

		$retorno .= "</fieldset></td>";
		return $retorno;
	}
}

?>