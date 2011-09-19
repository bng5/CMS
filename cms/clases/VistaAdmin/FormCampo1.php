<?php

/**
 * Description of Formstring
 *
 * @author pablo
 */
class VistaAdmin_FormCampo1 extends VistaAdmin_Form {

	public $id, $nombre, $indice = 0, $sugerido, $unico, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log, $formato;
	private $item, $label, $v, $pref, $campo_nombre_pref, $niveles;//, $x = array("id" => 0)
	private $tipo = 'string';

	public function __construct($item_id = false) {

        $this->log = '';
        $this->mysqli = $mysqli;
        $this->campo_id_pref = $this->campo_nombre_pref = "dato";
        $this->item = $item_id;
        $this->lenguajes = array();
        $this->niveles = array(0);
        $this->niveles_cierres = array();
        $this->superior_niv = 0;
        $mysqli = BaseDatos::Conectar();
        $cons_lengs = $mysqli->query("SELECT id, codigo, dir FROM lenguajes l WHERE estado >= 1 AND estado <= 4 ORDER BY leng_poromision DESC");
        if($fila_lengs = $cons_lengs->fetch_row()) {
            $this->leng_poromision = $fila_lengs[0];
            do {
                $this->lenguajes[$fila_lengs[0]] = array($fila_lengs[1], $fila_lengs[2]);
            }while($fila_lengs = $cons_lengs->fetch_row());
            $cons_lengs->close();
        }
    }

    function __destruct() {
        $pop = end($this->niveles);
        while($pop != 0) {
            array_pop($this->niveles);
            echo $this->niveles_cierres[$pop];
            unset($this->niveles_cierres[$pop]);
            $pop = end($this->niveles);
        }
    }
/*
  public function ingreso()
   {

   }
*/



	public function mostrar() {
		$this->log .= "\n\nid: {$this->id}\nsuperior: {$this->superior}\n";
		//if($this->superior_niv != $this->superior)
		// {
		if(in_array($this->superior, $this->niveles)) {
			$retorno = '';
			$ii = count($this->niveles);
			$pop = end($this->niveles);
			while($pop != $this->superior) {
				array_pop($this->niveles);
				$retorno .= $this->niveles_cierres[$pop];
				unset($this->niveles_cierres[$pop]);
				$ii--;
				$pop = end($this->niveles);
			}
		}
		// else


		//}
		//array_push($this->niveles, $this->superior);
		array_push($this->niveles, $this->id);
		$this->log .= var_export($this->niveles, true)."\n";
		$this->log .= var_export($this->valores, true)."\n";
		//$this->superior_niv = $this->superior;
		$this->superior_niv = $this->id;

		//$campo_tipo = "campo".$this->tipo;
		$this->indice++;

		if($this->unico == 1) {
			$nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
			echo $this->label(0, $this->campo_id_pref.$this->indice)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['string'])."\" size=\"45\" /></td>";// tabindex=\"2\"

		}
		elseif($this->unico == 2) {
			$i = 1;
			$multi_l = (count($this->lenguajes) > 1) ? true : false;
			$retorno = $this->label(1)."<td>";
			if($multi_l)
				$retorno .= "<ul class=\"campo_lista\">";
			foreach($this->lenguajes AS $leng_id => $leng) {
				$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
				if($multi_l)
					$retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
				$retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"200\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
				if($multi_l)
					$retorno .= "</li>";
				$i++;
			}
			// tabindex=\"2\"
			if($multi_l)
				$retorno .= "</ul>";
			echo $retorno."</td>";
		}

		//$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";

		//$retorno.$this->$campo_tipo();
	}

/*
  private function __set($nm, $val)
   {
	if(isset($this->x[$nm]))
	 {
	  $this->x[$nm] = $val;
	  $this->$nm = $val;
	 }
	//if($nm == "nombre") $this->label = "<label class=\"td\">{$val}:</label>\n";
	//else
	if($nm == "id") $this->v = '['.$val.'][]'; //$v = $v_id ? "[m][{$tipo}][{$v_id}]" : "[{$id}][]";
   }
*/

  private function campo($retorno = '')
   {
	return "<td colspan=\"2\">No se ha especificado el tipo de campo.</td>";
   }

  private function campostring($retorno = '')
   {
   	// color
   	if($this->subtipo == 1)
     {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $retorno = $this->label(1)."<td>";
	  if($this->unico == 1)
			  $retorno .= "#<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['string']}\" size=\"6\" maxlength=\"6\" onkeyup=\"contar6rgb(this);\" /><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.previousSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" />";
	  else {
	   	$retorno .= "<ul>";
		$k = @each($this->valores);
		do
		 {
		  $nombre_campo = $this->valores[$k[0]] ? "[m][{$this->id}][{$this->valores[$k[0]]['id']}]" : "[n][{$this->id}][]";
		  $idJS = $this->valores[$k[0]] ? $this->valores[$k[0]]['id'] : "null";
		  $retorno .= "<li>#<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[$k[0]]['string']}\" size=\"6\" maxlength=\"6\" onkeyup=\"contar6rgb(this);\" /><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.previousSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$k[1]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[$k[0]]['string']}\" /><img src=\"img/b_drop_ch\" onclick=\"borrarOp(this.parentNode, {$idJS}, '".($this->pref ? $this->pref : 'sup')."')\" alt=\"Eliminar\" /></li>";
		 }while($k = @each($this->valores));
		$pref = $this->pref ? $this->pref : "sup";
		$retorno .= "</ul><a onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\">Agregar</a>";
	   }
	  return $retorno."</td>";
     }
    // select / checkbox
	elseif($this->subtipo == 3 xor $this->subtipo == 4)
     {
	  //global $mysqli;
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}][]" : "[n][{$this->id}][]";
	  if(!$cons = $this->mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co LEFT JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = 76 WHERE co.campo_id = {$this->id} ORDER BY co.id")) echo __LINE__." - ".$mysqli->error;
	  if($fila = $cons->fetch_row())
	   {
	   	$valores_op = explode(";", $this->valores[0]['string']);
	   	if($this->subtipo == 3)
	   	 {
		  $retorno .= "<select name=\"{$this->campo_nombre_pref}{$nombre_campo}\" multiple=\"multiple\" id=\"{$this->campo_nombre_pref}{$this->indice}\">";
		  do
		   {
			$retorno .= "<option value=\"{$fila[0]}\"".(in_array($fila[0], $valores_op) ? ' selected="selected"' : '').">{$fila[1]}</option>";
		   }while($fila = $cons->fetch_row());
		  $retorno .= "</select>";
	   	 }
	   	else
	   	 {
		  $retorno .= "<ul>";
		  do
		   {
			$retorno .= "<li><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}{$i}\"".(in_array($fila[0], $valores_op) ? ' checked="checked"' : '')." /> <label for=\"{$this->campo_nombre_pref}{$this->indice}{$i}\">{$fila[1]}</label></li>";
			$i++;
		   }while($fila = $cons->fetch_row());
		  $retorno .= "</ul>";
		 }
	   }
	  else
		$retorno .= "No hay opciones configuradas.<br />Campo id: ".$this->id;
	  return $this->label(2)."<td>{$retorno}</td>";// tabindex=\"2\"
     }
    // alineación asociativa
	elseif($this->subtipo == 5)
     {
	  $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
	  return $this->label(2)."<td><input type=\"hidden\" name=\"\" value=\"\" /><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" size=\"20\" maxlength=\"45\" /> -&gt; <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" size=\"20\" maxlength=\"45\" /></td>";// tabindex=\"2\"
     }
	elseif($this->subtipo == 6)
     {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(0, $this->campo_id_pref.$this->indice)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['string'])."\" size=\"45\" /></td>";// tabindex=\"2\"
     }
	elseif(!$this->subtipo)
     {
	  $i = 1;
	  $multi_l = (count($this->lenguajes) > 1) ? true : false;
	  $retorno = $this->label(1)."<td>";
	  if($multi_l) $retorno .= "<ul class=\"campo_lista\">";
	  foreach($this->lenguajes AS $leng_id => $leng)
	   {
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
		if($multi_l)
		  $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
		$retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"200\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
		if($multi_l)
		  $retorno .= "</li>";
		$i++;
	   }
	  // tabindex=\"2\"
	  if($multi_l)
	    $retorno .= "</ul>";
	  return $retorno."</td>";
	 }
	else
	 return "<td colspan=\"2\">string {$this->subtipo}</td>";
   }

  private function campostring2()
   {
    if($this->subtipo == 1)
     {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $retorno = $this->label(1)."<td>";
//
	  if($this->unico == 1)
		$retorno .= "<img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['string']}\" />";
	  else
	   {
	   	$retorno .= "<ul>";
		$k = @each($this->valores);
		do
		 {
		  $nombre_campo = $this->valores[$k[0]] ? "[m][{$this->id}][{$this->valores[$k[0]]['id']}]" : "[n][{$this->id}][]";
		  $idJS = $this->valores[$k[0]] ? $this->valores[$k[0]]['id'] : "null";
		  $retorno .= "<li><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$k[1]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[$k[0]]['string']}\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[$k[0]]['string']}\" /><img src=\"img/b_drop_ch\" onclick=\"borrarOp(this.parentNode, {$idJS}, '".($this->pref ? $this->pref : 'sup')."')\" alt=\"Eliminar\" /></li>";
		 }while($k = @each($this->valores));
		$pref = $this->pref ? $this->pref : "sup";
		$retorno .= "</ul><a onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\">Agregar</a>";
	   }
	  return $retorno."</td>";
     }
	elseif($this->subtipo == 2)
     {
	  $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
	  return $this->label(2)."<td><input type=\"password\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" size=\"45\" maxlength=\"45\" /></td>";// tabindex=\"2\"
     }



	else
	 {
	  //if(empty($this->subtipo)) $this->subtipo = "";
	  $i = 1;
	  $multi_l = (count($this->lengs) > 1) ? true : false;
	  $retorno = $this->label(1)."<td>";
	  if($multi_l) $retorno .= "<ul class=\"campo_lista\">";
	  foreach($this->lenguajes AS $leng_id => $leng)
	   {
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
		if($multi_l) $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
		$retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"100\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
		if($multi_l) $retorno .= "</li>";
		$i++;
	   }
	  // tabindex=\"2\"
	  if($multi_l) $retorno .= "</ul>";
	  return $retorno."</td>";
	 }
   }




}

?>