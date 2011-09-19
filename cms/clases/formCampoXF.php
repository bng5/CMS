<?php

class formCampoXF
 {
  public $id, $nombre, $sugerido, $unico, $tipo, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log, $formato;
  private $item, $indice = 0, $label, $v, $pref, $campo_nombre_pref, $niveles;//, $x = array("id" => 0)
  public function __construct($doc, $root, $modelo, $instancia, $idioma)
   {
   	global $mysqli;
	$this->doc = $doc;
	$this->root = $root;
	$this->modelo = $modelo;
	$this->instancia = $instancia;
	$this->idioma = $idioma;
$this->log = '';
   	$this->mysqli = $mysqli;
   	$this->campo_id_pref = $this->campo_nombre_pref = "dato";
   	$this->item = $item_id;
   	$this->lenguajes = array();
   	$this->niveles = array(0);
   	$this->niveles_cierres = array();
   	$this->superior_niv = 0;
	$cons_lengs = $mysqli->query("SELECT id, codigo, dir FROM lenguajes l WHERE estado >= 1 AND estado <= 4 ORDER BY leng_poromision DESC");
	if($fila_lengs = $cons_lengs->fetch_row())
	 {
	  $this->leng_poromision = $fila_lengs[0];
	  do
	   {
		$this->lenguajes[$fila_lengs[0]] = array($fila_lengs[1], $fila_lengs[2]);
	   }while($fila_lengs = $cons_lengs->fetch_row());
	  $cons_lengs->close();
	 }
   }

  function __destruct()
   {
	$pop = end($this->niveles);
	while($pop != 0)
	 {
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

  private function label($tipo = 0, $for = '')
   {
	if($tipo == 1) return "<td><label>{$this->nombre}:</label></td>\n";
	else
	 {
	  //$for = empty($for) ? $this->campo_id_pref.$this->indice : $for;
	  return "<td><label for=\"{$this->campo_id_pref}{$this->indice}\">{$this->nombre}:</label></td>";
	 }
   }

  public function agregar()
   {
	$campo_tipo = "campo".$this->tipo;
	$this->$campo_tipo();
   }

  public function imprimir()
   {
	  if(in_array($this->superior, $this->niveles))
	   {
		$retorno = '';
		$ii = count($this->niveles);
		$pop = end($this->niveles);
		while($pop != $this->superior)
		 {
		  array_pop($this->niveles);
		  $retorno .= $this->niveles_cierres[$pop];
		  unset($this->niveles_cierres[$pop]);
		  $ii--;
		  $pop = end($this->niveles);
		 }
	   }

	array_push($this->niveles, $this->id);
	//$this->superior_niv = $this->superior;
	$this->superior_niv = $this->id;

   	$campo_tipo = "campo".$this->tipo;
	//$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
	$this->indice++;
	//return $retorno."\n	  <tr>".$this->$campo_tipo();
	return array($this->$campo_tipo(), $this->doc->createElement($this->identificador));



	return "<td colspan=\"2\"><pre>
id: ".var_export($this->id, true)."
sugerido: ".var_export($this->sugerido, true)."
unico: ".var_export($this->unico, true)."
tipo: ".var_export($this->tipo, true)."
subtipo: ".var_export($this->subtipo, true)."
nombre: ".var_export($this->nombre, true)."
identificador: ".var_export($this->identificador, true)."
poromision: ".var_export($this->poromision, true)."
string: ".var_export($this->string, true)."
date: ".var_export($this->date, true)."
text: ".var_export($this->text, true)."
int: ".var_export($this->int, true)."
num: ".var_export($this->num, true)."
extra: ".var_export($this->extra, true)."
superior: ".var_export($this->superior, true)."
nodo_tipo: ".var_export($this->nodo_tipo, true)."
valores: ".var_export($this->valores, true)."
	</pre></td>";


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
	if($nm == "id") $this->v = '['.$val.'][]'; //$v = $v_id ? "[m][${tipo}][${v_id}]" : "[${id}][]";
   }
*/

  private function campo($retorno = '')
   {
	return "<td colspan=\"2\">No se ha especificado el tipo de campo.</td></tr>";
   }

  private function campostring($retorno = '')
   {
   	// color
   	if($this->subtipo == 1)
     {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $retorno = $this->label(1)."<td>";
	  if($this->unico == 1) $retorno .= "#<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['string']}\" size=\"6\" maxlength=\"6\" onkeyup=\"contar6rgb(this);\" /><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.previousSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" />";
	  else
	   {
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
	  return $retorno."</td></tr>";
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
			$retorno .= "<li><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".(in_array($fila[0], $valores_op) ? ' checked="checked"' : '')." /> <label for=\"{$this->campo_nombre_pref}{$this->indice}${i}\">{$fila[1]}</label></li>";
			$i++;
		   }while($fila = $cons->fetch_row());
		  $retorno .= "</ul>";
		 }
	   }
	  else
		$retorno .= "No hay opciones configuradas.<br />Campo id: ".$this->id;
	  return $this->label(2)."<td>${retorno}</td></tr>";// tabindex=\"2\"
     }
    // alineación asociativa
	elseif($this->subtipo == 5)
     {
	  $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
	  return $this->label(2)."<td><input type=\"hidden\" name=\"\" value=\"\" /><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" size=\"20\" maxlength=\"45\" /> -&gt; <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" size=\"20\" maxlength=\"45\" /></td></tr>";// tabindex=\"2\"
     }
	elseif($this->subtipo == 6)
     {
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:input');
	  $nodo->setAttribute('ref', $this->identificador);//"dato[n][{$this->id}][]");
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label');
	  $label->appendChild($this->doc->createTextNode($this->nombre));
	  $label = $nodo->appendChild($label);
	  $this->root->appendChild($nodo);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);

return;

	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(0, $this->campo_id_pref.$this->indice)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['string'])."\" size=\"45\" /></td></tr>";// tabindex=\"2\"
     }
	// campo de texto multilingüe
	elseif(!$this->subtipo)
     {
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:input');
	  $nodo->setAttribute('ref', $this->identificador);//.'/'.$this->idioma['cod']);
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $this->nombre);
	  $label = $nodo->appendChild($label);
	  $this->root->appendChild($nodo);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);
	  //$modelo->appendChild($this->doc->createElement($this->idioma['cod']));
return;

	  $i = 1;
	  $multi_l = (count($this->lenguajes) > 1) ? true : false;
	  //$retorno = $this->label(1)."<td>";
	  //if($multi_l)
	  //  $retorno .= "<ul class=\"campo_lista\">";
	  foreach($this->lenguajes AS $leng_id => $leng)
	   {
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
		//if($multi_l)
		//  $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
		$modelo->appendChild($this->doc->createElement($leng[0]));
		 /*
		 * Parche Zooko
		 * evento
		if($this->id == 22)
		  $retorno .= "<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" cols=\"43\" rows=\"2\" maxlength=\"200\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">".htmlspecialchars($this->valores[$leng_id]['string'])."</textarea>";
		else
		  $retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"200\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
		 * FIN Parche Zooko
		 * evento
		 */
		//if($multi_l)
		//  $retorno .= "</li>";
		$i++;
	   }
	  // tabindex=\"2\"
	  //if($multi_l)
	  //  $retorno .= "</ul>";
	  //return $retorno."</td></tr>";
	 }
	else
	 return "<td colspan=\"2\">string {$this->subtipo}</td></tr>";
   }

  private function campostring2()
   {
    if($this->subtipo == 1)
     {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $retorno = $this->label(1)."<td>";
//
	  if($this->unico == 1) $retorno .= "<img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['string']}\" />";
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
	  $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
	  return $this->label(2)."<td><input type=\"password\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" size=\"45\" maxlength=\"45\" /></td>";// tabindex=\"2\"
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
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
		if($multi_l) $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
		$retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"100\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
		if($multi_l) $retorno .= "</li>";
		$i++;
	   }
	  // tabindex=\"2\"
	  if($multi_l) $retorno .= "</ul>";
	  return $retorno."</td>";
	 }
   }

  private function campodate()
   {
	global $texto;
	// fecha
	if($this->valores[0]['date'] == null && $this->extra['current'])
	  $valor = date("Y-m-d G:i:s");
	else
	  $valor = $this->valores[0]['date'];
    if($this->subtipo == 1)
     {
	  //$fecha = $this->valor ? date("Y-m-d", $this->valor) : "";
	  $valor = substr($valor, 0, 10);
	  $fechaMst = formato_fecha($valor, true, false);
	  $formato = "%Y-%m-%d";
	  $formatoMst = "%A, %d de %B de %Y";
	  $mostrarHora = "false";
     }
    // fecha y hora
    else
     {
	  //$fecha = $this->valor ? date("Y-m-d G:i", $this->valor) : "";
	  $fechaMst = formato_fecha($valor);
	  $formato = "%Y-%m-%d %H:%M";
	  $formatoMst = "%A, %d de %B de %Y, %H:%M hs.";
	  $mostrarHora = "true";
     }
	// onclick=\"return showCalendar('fecha_fin', '%A, %B %e, %Y');\"

	$nombre_campo = $this->valores[0]['date'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	return $this->label(1)."<td><span id=\"mostrar_fecha{$this->indice}\">${fechaMst}</span>&nbsp;&nbsp;<img src=\"/img/icono_calendario\" id=\"tn_calendario{$this->indice}\" style=\"cursor: pointer;\" title=\"Abrir calendario\" alt=\"Abrir calendario\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$valor}\" id=\"fecha{$this->indice}\" />
<script type=\"text/javascript\">
//<![CDATA[
Calendar.setup({inputField : \"fecha{$this->indice}\", ifFormat : \"${formato}\", displayArea : \"mostrar_fecha{$this->indice}\", daFormat : \"${formatoMst}\", button : \"tn_calendario{$this->indice}\", showsTime : ${mostrarHora}});
//]]>
</script></td>
</tr>";
   }

  private function campoint()
   {
	// externo
    if($this->subtipo == 1)
     {
      //$ret = $this->label(1)."<td>";
	  if(!$cons_vista = $this->mysqli->query($this->extra.$this->idioma['id']." ORDER BY 2"))
	   {
		$cons = $this->doc->createElement('consulta');
		$cons = $this->root->appendChild($cons);
		$cons->appendChild($this->doc->createTextNode($this->extra.$this->idioma['id']." ORDER BY 2"));
		return;//$ret .= "Existe un error en la configuración de este campo";
	   }
	  else
	   {
		if($fila_vista = $cons_vista->fetch_row())
		 {
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:select1');
	  $nodo->setAttribute('ref', $this->identificador);//dato[n][{$this->id}][]");
	  $nodo = $this->root->appendChild($nodo);
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $this->nombre);
	  $label = $nodo->appendChild($label);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);
		  $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		  //$ret .= "<select name=\"{$this->campo_nombre_pref}${nombre_campo}\"><option value=\"\"> </option>";
		  do
		   {
			$opcion = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:item');
			$opcion = $nodo->appendChild($opcion);
			$label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $fila_vista[1]);
			$opcion->appendChild($label);
			$value = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:value', $fila_vista[0]);
			$opcion->appendChild($value);

			/*$ret .= "<option value=\"{$fila_vista[0]}\"";
			if($fila_vista[0] == $this->valores[0]['int'])
			  $ret .= " selected=\"selected\"";
			$ret .= ">".htmlspecialchars($fila_vista[1])."</option>";*/
		   }while($fila_vista = $cons_vista->fetch_row());
		  //$ret .= "</select>";
		  $cons_vista->close();
		 }
		else
		  $ret .= "No hay opciones disponibles";
	   }
	  //return $ret."</td></tr>";
return;
     }


    // imagen
    if($this->subtipo == 2)
     {
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:upload');
	  $nodo->setAttribute('ref', $this->identificador);
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $this->nombre);
	  $label = $nodo->appendChild($label);
	  $filename = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:filename');
	  $filename->setAttribute('ref', '@nombre');
	  $nodo->appendChild($filename);
	  $mediatype = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:mediatype');
	  $mediatype->setAttribute('ref', '@tipo');
	  $nodo->appendChild($mediatype);
	  $this->root->appendChild($nodo);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);
	  $modelo->setAttribute('tipo', '');
	  $modelo->setAttribute('nombre', '');
	  //$modelo->setAttribute('type', 'xs:base64Binary');
	  $bind = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:bind');
	  $bind->setAttribute('nodeset', $this->identificador);
	  $bind->setAttribute('type', 'xs:base64Binary');
	  $this->modelo->appendChild($bind);
return;

	  //$ret = $label;
      //global $mysqli;//, $p_seccion_id;
      $valor = $this->valores[0]['int'] ? $this->valores[0]['int'] : $this->poromision;
      if(!$cons = $this->mysqli->query("SELECT id, `archivo` FROM `imagenes_orig` WHERE `id` = '{$valor}' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
	  if($fila = $cons->fetch_row())
	   {
		$img_id = $fila[0];
		$img = "/icono/0/{$this->id}/{$fila[1]}";
		$etiqueta = "Cambiar";
		$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	   }
	  else
	   {
		$img_id = false;
		$img = "/img/trans";
		$etiqueta = "Agregar";
		$nombre_campo = "[n][{$this->id}][]";
	   }
	  $retorno = $this->label(1)."<td><img src=\"{$img}\" id=\"img{$this->indice}\" alt=\"{$fila[0]}\" /><button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_imagen', {$this->indice})\"><span>${etiqueta}</span></button> <!-- button type=\"button\" onclick=\"abrirModal('./examinar/{$this->id}/{$this->indice}', 680, 450)\"><span>Examinar servidor...</span></button --><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" />";
if($this->extra['prot'])
 {

$retorno .= "
<table>
";

$nombres_anon = array(
  'string' => 'Texto',
  'string1' => 'Color',
  'string6' => 'Texto',
  'date' => 'Fecha y hora',
  'date1' => 'Fecha',
  'text' => 'Texto',
  'text1' => 'Enlace',
  'string5' => 'Alineación asociativa',
  'string3' => 'Selector múltiple',
  'string4' => 'Checkbox',
  'string2' => 'Contraseña',
  'date' => 'Fecha y hora',
  'int7' => 'Área',
  'int6' => 'Rango',
  'int' => 'Número natural (ℕ)',
  'int5' => 'Selector',
  'int8' => 'Radio',
  'int4' => 'Galería de imágenes',
  'int3' => 'Archivo',
  'int2' => 'Imagen',
  'int1' => 'Dato externo',
  'int9' => 'Formulario',
  'num' => 'Precio',
  'num1' => 'Número entero (ℤ)'
 );
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

$retorno .= "</td></tr>";
	  return $retorno;
      //$bsq = ($_SESSION['permisos'][$p_seccion_id] < 4) ? "AND bsq = '".$_SESSION['usuario_id']."'" : null;
      //$bsq = false;
     }
   	// archivo
   	elseif($this->subtipo == 3)
     {
	  //$this->niveles_cierres[$this->id] = "</table></td></tr>";
	  if($this->unico)
	   {
	  //$nodo = $this->doc->createElement('upload');
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:upload');
	  $nodo->setAttribute('ref', $this->identificador);
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $this->nombre);
	  $label = $nodo->appendChild($label);
	  $filename = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:filename');
	  $filename->setAttribute('ref', '@nombre');
	  $nodo->appendChild($filename);
	  $mediatype = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:mediatype');
	  $mediatype->setAttribute('ref', '@tipo');
	  $nodo->appendChild($mediatype);
	  $this->root->appendChild($nodo);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);
	  $modelo->setAttribute('tipo', '');
	  $modelo->setAttribute('nombre', '');
	  $bind = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:bind');
	  $bind->setAttribute('nodeset', $this->identificador);
	  $bind->setAttribute('type', 'xs:base64Binary');
	  $this->modelo->appendChild($bind);
	   }
return;
	  //$retorno = "";
	  if(is_array($this->valores))
	   {
	    foreach($this->valores AS $nu_v)
		  $acons[$nu_v['int']] = $nu_v['id'];
	   }
	  if(is_array($acons))
	   {
	   	$valor = implode("' OR id = '", array_keys($acons));
	   }
	  else $valor = $this->poromision;
      if(!$cons = $this->mysqli->query("SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' ORDER BY id")) echo __LINE__." - ".$mysqli->error;
	  if($fila = $cons->fetch_row())
	   {
		$etiqueta = "Cambiar";
		if($this->unico)
		 {
		  $img_id = $fila[0];
		  $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		  $retorno .= "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button>";
		 }
		else
		 {
		  $retorno = "<ul id=\"lista_{$this->indice}\">";
		  $i = 1;
		  do
		   {
			$img_id = $fila[0];
			$nombre_campo = "[m][{$this->id}][{$acons[$img_id]}]";
			$retorno .= "<li><span id=\"archivo{$this->indice}_${i}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" value=\"{$img_id}\" /></li>";
			$i++;
		   }while($fila = $cons->fetch_row());
		  $retorno .= "</ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";//onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\"
		 }
	   }
	  else
	   {
		$img_id = false;
		$etiqueta = "Agregar";
		$nombre_campo = "[n][{$this->id}][]";
	   }
	  if(empty($retorno))
		$retorno = $this->unico ? "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"img/trans\" alt=\"\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button>" : "<ul id=\"lista_{$this->indice}\"></ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";

	  return $this->label(1)."<td>${retorno}</td></tr>";

     }

     // galería
    elseif($this->subtipo == 4)
     {
      $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
      $valores = array();
      $imagenes = array();
	  $retorno = "<td colspan=\"2\"><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" value=\"{$this->valores[0]['int']}\" /><div id=\"galeria\">";
	  if(!$consulta_imgs = $this->mysqli->query("SELECT gi.imagen_id, gi.estado, io.archivo, io.formato, gi.orden FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id WHERE gi.galeria_id = '{$this->valores[0]['int']}' ORDER BY gi.orden")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($total_imgs = $consulta_imgs->num_rows)
	   {
		while($fila_imgs = $consulta_imgs->fetch_row())
		 {
		  $imagenes[$fila_imgs[0]] = array();
		  //echo "<input type=\"hidden\" name=\"img_estado[{$fila_imgs[0]}]\" value=\"{$fila_imgs[5]}\" /><input type=\"hidden\" name=\"img_titulo[{$fila_imgs[0]}]\" value=\"{$fila_imgs[2]}\" /><input type=\"hidden\" name=\"img_fecha[{$fila_imgs[0]}]\" id=\"img_fecha{$fila_imgs[0]}\" value=\"{$fila_imgs[4]}\" /><textarea name=\"img_texto[{$fila_imgs[0]}]\" rows=\"6\" cols=\"40\" class=\"oculto\">{$fila_imgs[3]}</textarea>";
		  $retorno .= "<span></span><input type=\"image\" name=\"imagen[]\" src=\"icono/4/{$fila_imgs[2]}\" value=\"{$fila_imgs[0]}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg(this)\" title=\"{$fila_imgs[2]}\" alt=\"{$fila_imgs[2]}\" />";
		 }
		$consulta_imgs->close();

		if(!$cons_valores = $this->mysqli->query("SELECT imagen_id, atributo_id, leng_id, id, `string`, `date`, `text`, `int`, `num` FROM galerias_imagenes_valores g WHERE galeria_id = {$this->valores[0]['int']}")) echo __LINE__." - ".$mysqli->error;
		if($fila_valores = $cons_valores->fetch_assoc())
		 {
		  do
		   {
			$imagen = array_shift($fila_valores);
			$img_atributo_id = array_shift($fila_valores);
			$img_leng_id = array_shift($fila_valores);
			if($img_leng_id) $imagenes[$imagen][$img_atributo_id][$img_leng_id] = $fila_valores;
			else $imagenes[$imagen][$img_atributo_id][] = $fila_valores;
	       }while($fila_valores = $cons_valores->fetch_assoc());
	 	  $cons_valores->close();
	 	 }
	   }
	  //SELECT gi.imagen_id, gi.estado, i.archivo, i.formato, i.peso, gi.orden FROM galerias_imagenes gi JOIN imagenes i ON gi.imagen_id = i.id WHERE gi.galeria_id = '{$this->valores[0]['int']}' ORDER BY gi.orden
	  $retorno .= "<span></span></div><input type=\"image\" name=\"eliminarImg\" src=\"img/papelera\" alt=\"Eliminar\" title=\"Arrastre hasta aquí para eliminar\" style=\"background:none;border:none;\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_imagen_gal', {$this->indice})\"><span>Agregar</span></button><fieldset style=\"display:none;\"
	     ><legend></legend
	      ><img src=\"img/trans\" alt=\"\"
	      />";
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
	  if(count($atributos))
	   {
		$js_leng = array();
		foreach($this->lengs AS $leng_id => $leng_cod) $js_leng[] = "${leng_id} : '${leng_cod}'";
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
		foreach($atributos AS $k => $a)
		 {
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

		while(list($valores_k, $valores_arr) = each($imagenes))
		 {
		  $this->subitem = new formCampo2('galimg', $valores_k);//$this->id);
//<pre>".var_export($valores_arr, true)."\nmuestra{$this->valores[0]['int']}_${valores_k}</pre>
		  $retorno .= "<table id=\"muestra_${valores_k}\" style=\"display:none;\">";
		  foreach($atributos AS $k => $a)
		   {
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

	  $retorno .= "</fieldset
		></td></tr>";
	  return $retorno;
	 }

	// área
    elseif($this->subtipo == 7)
     {
	  $this->niveles_cierres[$this->id] = "</tbody></table></fieldset></td></tr>";
       	$ret = "
	   <td colspan=\"2\"><fieldset><legend>{$this->nombre}</legend>
		<table>
		 <tbody>";
	  return $ret;
     }
    // radio
    elseif($this->subtipo == 8)
     {
	  $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $ret = $this->label(1)."<td><ul class=\"campo_lista\">";
	  $i = 1;
	  //$ops = unserialize();
	  foreach($this->extra AS $k => $v)
	   {
	   	$ret .= "<li><input type=\"radio\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" value=\"${k}\"";
	   	if($this->valores[0]['int'] == $k) $ret .= " checked=\"checked\"";
	   	$ret .= " /> <label for=\"{$this->campo_nombre_pref}{$this->indice}_${i}\">${v}</label></li>";
	   	$i++;
	   }
	  return $ret."</ul></td></tr>";
     }
	elseif($this->subtipo == 10)
     {


	  //foreach($this->valores AS $valor)
	  // {
	  //  $vals[$valor['int']] = true;
	  // }
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}][]" : "[n][{$this->id}][]";
	  $js = '';
	  //if(!$cons = $this->mysqli->query("SELECT c.id, ct.texto, cs.item_id IS NOT NULL AS sel FROM campos_opciones c LEFT JOIN campos_opciones_sel cs ON c.id = cs.opcion_id AND cs.item_id = {$this->item}, campos_opciones_textos ct WHERE c.campo_id = {$this->id} AND  c.id = ct.id")) echo __LINE__." - ".$mysqli->error;// AND ct.leng_id = 76
	  $retorno .= "<div class=\"etiquetas\">";
	  if($this->item)
	   {
	  if(!$cons = $this->mysqli->query("SELECT c.id, ct.texto FROM campos_opciones c JOIN campos_opciones_sel cs ON c.id = cs.opcion_id AND cs.item_id = {$this->item}, campos_opciones_textos ct WHERE c.campo_id = {$this->id} AND  c.id = ct.id ORDER BY ct.texto")) echo __LINE__."SELECT c.id, ct.texto FROM campos_opciones c JOIN campos_opciones_sel cs ON c.id = cs.opcion_id AND cs.item_id = {$this->item}, campos_opciones_textos ct WHERE c.campo_id = {$this->id} AND  c.id = ct.id ORDER BY ct.texto - ".$mysqli->error;// AND ct.leng_id = 76
	  if($fila = $cons->fetch_row())
	   {
		  //$retorno .= "<ul>";
		  do
		   {
		    $retorno .= "<span>".htmlspecialchars($fila[1])."<input type=\"hidden\" name=\"dato[o][{$this->id}][id][]\" value=\"{$fila[0]}\"/><sup onclick=\"borrarEtiqueta(this)\">X</sup></span> ";
			$js .= "etiquetas['es-uy']['{$fila[1]}'] = ".strlen($fila[1]).";\n";
//			<li><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$fila[0]}\" id=\"{$this->campo_nombre_pref}{$this->indice}${i}\"".(in_array($fila[0], $valores_op) ? ' checked="checked"' : '')." /> <label for=\"{$this->campo_nombre_pref}{$this->indice}${i}\">{$fila[1]}</label></li>";
			$i++;
		   }while($fila = $cons->fetch_row());
	   }
	   }
	  $retorno .= "<input type=\"text\" name=\"dato[o][{$this->id}][n][]\" id=\"etiquetas_{$this->id}\" onkeypress=\"return mestadoPress(event, {$this->id})\" maxlength=\"250\" />";
	  // $retorno .= "</ul>";
	  $retorno .= "</div><script type=\"text/javascript\" defer=\"defer\">
var metaActivo = 'es-uy';
etiquetas['es-uy'] = {};
{$js}
</script>";
//	  else
//		$retorno .= "No hay opciones configuradas.";
	  return $this->label(1)."<td>${retorno}</td></tr>";// tabindex=\"2\"
	 }





else
  return "<td colspan=\"2\">int {$this->subtipo}</td></tr>";
   }

  private function campoint2()
   {
	//global $mysqli;
    if($this->subtipo == 1)
     {
      $ret = $this->label(1)."<td>";
	  if(!$cons_vista = $this->mysqli->query($this->extra."1 ORDER BY 2")) $ret .= "Existe un error en la configuración de este campo</td>";
	  else
	   {
		if($fila_vista = $cons_vista->fetch_row())
		 {
		  $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		  $ret .= "<select name=\"{$this->campo_nombre_pref}${nombre_campo}\"><option value=\"\"> </option>";
		  do
		   {
			$ret .= "<option value=\"{$fila_vista[0]}\"";
			if($fila_vista[0] == $this->valores[0]['int']) $ret .= " selected=\"selected\"";
			$ret .= ">".htmlspecialchars($fila_vista[1])."</option>";
		   }while($fila_vista = $cons_vista->fetch_row());
		  $ret .= "</select></td>";
		  $cons_vista->close();
		 }
	   }
	  return $ret;
     }

    // archivo
    elseif($this->subtipo == 3)
     {
/*
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  $retorno = $this->label(1)."<td>";

	  if($this->unico == 1) $retorno .= "<img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['string']}\" />";
	  else
	   {
	   	$retorno .= "<ul>";
		$k = @each($this->valores);
		do
		 {
		  $nombre_campo = $this->valores[$k[0]] ? "[m][{$this->id}][{$this->valores[$k[0]]['id']}]" : "[n][{$this->id}][]";
		  $idJS = $this->valores[$k[0]] ? $this->valores[$k[0]]['id'] : "null";
		  $retorno .= "<li><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$k[1]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[$k[0]]['string']}\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[$k[0]]['string']}\" /><img src=\"img/b_drop_ch\" onclick=\"borrarOp(this.parentNode, {$idJS}, '{$this->pref}')\" alt=\"Eliminar\" /></li>";
		 }while($k = @each($this->valores));
		$pref = $this->pref ? $this->pref : "sup";
		$retorno .= "</ul><a onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\">Agregar</a>";
	   }
	  return $retorno."</td>";
*/

	  //if($this->unico == 1) $valor = $this->valores[0]['int'] ? $this->valores[0]['int'] : $this->poromision;
	  //else
	  // {
	  $retorno = "";
	  if(is_array($this->valores)) foreach($this->valores AS $nu_v) $acons[$nu_v['int']] = $nu_v['id'];
	  if(is_array($acons))
	   {
	   	//sort($acons);
	   	$valor = implode("' OR id = '", array_keys($acons));
	   }
	  else $valor = $this->poromision;
	  // }
      if(!$cons = $this->mysqli->query("SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' ORDER BY id")) echo __LINE__." - ".$mysqli->error;
	  if($fila = $cons->fetch_row())
	   {
		$etiqueta = "Cambiar";
		if($this->unico)
		 {
		  $img_id = $fila[0];
		  $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		  $retorno .= "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button>";
		 }
		else
		 {
		  $retorno = "<ul id=\"lista_{$this->indice}\">";
		  $i = 1;
		  do
		   {
			$img_id = $fila[0];
			//$img = "/icono/imagenes/{$fila[1]}";
			//$archivo_nombre = "{$fila[2]}<img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"alert('esto va a eliminar el archivo')\" />";
			$nombre_campo = "[m][{$this->id}][{$acons[$img_id]}]";
			$retorno .= "<li><span id=\"archivo{$this->indice}_${i}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_${i}\" value=\"{$img_id}\" /></li>";
			$i++;
		   }while($fila = $cons->fetch_row());
		  $retorno .= "</ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";//onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\"
		 }
	   }
	  else
	   {
		$img_id = false;
		//$img = "/img/trans";
		$etiqueta = "Agregar";
		$nombre_campo = "[n][{$this->id}][]";
	   }
	  //$retorno = $this->label(1)."<td><pre>SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' LIMIT 1\n".var_export($this->valores, true)."</pre><span id=\"archivo{$this->indice}\">${archivo_nombre}</span> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /></td>";
	  if(empty($retorno))
		$retorno = $this->unico ? "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"img/trans\" alt=\"\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>${etiqueta}</span></button>" : "<ul id=\"lista_{$this->indice}\"></ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";

	  return $this->label(1)."<td>${retorno}</td>";
	  //return $retorno;
     }

	// selector
	elseif($this->subtipo == 5)
     {
	  if(!$cons_vista = $mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = 1 WHERE co.campo_id = {$this->id} ORDER BY co.id")) echo __LINE__." - ".$mysqli->error;
	  if($fila_vista = $cons_vista->fetch_row())
	   {
	   	$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	    $ret = $this->label(1)."<td><select name=\"{$this->campo_nombre_pref}${nombre_campo}\">";
	    do
	     {
		  $ret .= "<option value=\"{$fila_vista[0]}\"";
		  if($fila_vista[0] == $this->valores[0]['int']) $ret .= " selected=\"selected\"";
		  $ret .= ">{$fila_vista[1]}</option>";
		 }while($fila_vista = $cons_vista->fetch_row());
		$ret .= "</select></td>";
		$cons_vista->close();
	   }
	  return $ret;
     }
    // rango
    elseif($this->subtipo == 6)
     {
      if($this->valores[0]['int'])
       {
       	$ret = $this->label(1)."<td>";
		$cons_img = $mysqli->query("SELECT id, codigo FROM subitems WHERE item_id = {$this->item} AND atributo_id = {$this->id} ORDER BY codigo");
		if($img = $cons_img->fetch_row())
		 {
		  $ret .= "<ul class=\"campo_lista\">";
		  do
		   {
			$ret .= "<li>{$img[1]}</li>";
		   }while($img = $cons_img->fetch_row());
		  $ret .= "</ul>";
		 }
		return $ret."</td>";
	   }
	  else
	   {
		//$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		$nombre_campo = "[m][{$this->id}][{$this->valores[0]['id']}]";
		return $this->label(1)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$this->valores[0]['int']}\" /> <select name=\"extra[{$this->id}]\"><option value=\"0\"> </option><option value=\"1\">Impares</option><option value=\"2\">Pares</option></select></td>";
	   }
     }
    // área
    elseif($this->subtipo == 7)
     {
     // if($this->valores[0]['int'])
     //  {
       	$ret = "<td colspan=\"2\"><fieldset><legend>{$this->nombre}</legend><table>";

	  $atributos = array();
	  if(!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, subitems_supatributos_a_atributos isaa LEFT JOIN subitems_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND sup_atributo_id = '{$this->id}' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
	  if($fila_at = $atributos_tipos->fetch_assoc())
	   {
		do
		 {
		  $attr_id = array_shift($fila_at);
		  $atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
		 }while($fila_at = $atributos_tipos->fetch_assoc());
		$atributos_tipos->close();
	   }
/***/
	  if($this->item)
	   {
		$valores = array();
		if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, `string`, `date`, `text`, `int`, `num`, leng_id FROM subitems_valores WHERE item_id = {$this->item} AND area_id = {$this->id}")) echo __LINE__." - ".$mysqli->error;
		if($fila_valores = $cons_valores->fetch_row())
		 {
		  do
		   {
			$valor = $fila_valores[0];
			if($fila_valores[7]) $valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
			else $valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
	       }while($fila_valores = $cons_valores->fetch_row());
	 	  $cons_valores->close();
	 	 }
	   }
/***/
/*
$ret .= "
<pre>".var_export($atributos, true)."
".var_export($valores, true)."
</pre>";
*/
	$this->subitem = new formCampo2('sub', $this->id);


		foreach($atributos AS $k => $a)
		 {
		  $this->subitem->id = $k;
		  $this->subitem->sugerido = $a['sugerido'];
		  $this->subitem->unico = $a['unico'];
		  $this->subitem->tipo = $a['tipo'];
		  $this->subitem->subtipo = $a['subtipo'];
		  $this->subitem->nombre = $a['nombre'];
		  $this->subitem->poromision = $a['poromision'];
		  $this->subitem->extra = $a['extra'];
		  //$formcampo->identificador = $a['identificador'];
		  $this->subitem->valores = $valores[$k];
		  $ret .= "<tr>".$this->subitem->imprimir()."</tr>";
		 }

		//$cons_img = $mysqli->query("SELECT id, codigo FROM subitems WHERE item_id = {$this->item} AND atributo_id = {$this->id} ORDER BY codigo");
		//if($img = $cons_img->fetch_row())
		// {
		//  $ret .= "<ul class=\"campo_lista\">";
		//  do
		//   {
		//	$ret .= "<li>{$img[1]}</li>";
		//   }while($img = $cons_img->fetch_row());
		//  $ret .= "</ul>";
		// }
		return $ret."</table></fieldset></td>";
	  // }
	  //else
	  // {
		//$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
		$nombre_campo = "[m][{$this->id}][{$this->valores[0]['id']}]";
		return $this->label(1)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$this->valores[0]['int']}\" /> <select name=\"extra[{$this->id}]\"><option value=\"0\"> </option><option value=\"1\">Impares</option><option value=\"2\">Pares</option></select></td>";
	  // }
     }

	 // int no definido
	else
	 {
	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(2)."<td><input type=\"text\" name=\"{$this->campo_nombre_pref}${nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"{$this->valores[0]['int']}\" size=\"5\" maxlength=\"9\" /></td>";
	 }
   }

  private function campotext()
   {
   	// enlace externo
	if($this->subtipo == 1)
	 {
	  $sel_prot[$this->valores[0]['int']] = " selected=\"selected\"";
	  $prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
	  //<option value=\"2\"{$sel_prot[2]}>https://</option><option value=\"3\"{$sel_prot[3]}>ftp://</option><option value=\"4\"{$sel_prot[4]}>gopher://</option><option value=\"5\"{$sel_prot[5]}>mailto:</option><!-- option value=\"6\">wais</option --></select>
	  $prot = $this->extra ? $this->extra : array(1);
	  $tot_prot = count($prot);
	  if(!$tot_prot)
	   {
	   	$prot = array(1);
	   	$tot_prot = 1;
	   }
	  if($tot_prot == 1)
		$prot_str = "<input type=\"hidden\" value=\"{$prot_arr[current($prot)]}\" /><input type=\"hidden\" name=\"prot[{$this->id}]\" value=\"".current($prot)."\" />".$prot_arr[current($prot)];
	  else
	   {
	    $prot_str = "<select name=\"prot[{$this->id}]\">";
	    foreach($prot AS $prot_k => $prot_v) $prot_str .= "<option value=\"${prot_k}\"{$sel_prot[$prot_k]}>${prot_arr[$prot_k]}</option>";
	    $prot_str .= "</select>";
	   }

	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(0, $this->campo_id_pref.$this->indice)."<td>{$prot_str} <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['text'])."\" size=\"45\" /> <a href=\"#\" target=\"_blank\" onclick=\"return abrirEnlace(this)\"><img src=\"/img/externo\" alt=\"Abrir enlace\" class=\"enlace_img\" /></a></td></tr>";// tabindex=\"2\"
	 }
	// textarea
	else
	 {
	  $nodo = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:textarea');
	  $nodo->setAttribute('ref', $this->identificador);//.'/'.$this->idioma['cod']);//dato[n][{$this->id}][]");
	  $nodo = $this->root->appendChild($nodo);
	  $label = $this->doc->createElementNS('http://www.w3.org/2002/xforms', 'xf:label', $this->nombre);
	  $label = $nodo->appendChild($label);
	  $modelo = $this->doc->createElement($this->identificador);
	  $modelo = $this->instancia->appendChild($modelo);
	  //$modelo->appendChild($this->doc->createElement($this->idioma['cod']));

return;

	  $retorno = $this->label(1)."<td><ul class=\"lista_idiomas\">";
	  $i = 1;
	  $estilo = " style=\"display:block;\"";
	  $seleccionado = " seleccionado";
	  $tot_lengs = count($this->lenguajes);
	  foreach($this->lenguajes AS $leng_id => $leng)
	   {
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
		$retorno .= "<li>";
		if($tot_lengs > 1)
		  $retorno .= "<label id=\"p{$this->campo_id_pref}{$this->indice}_${i}\" for=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"etiqueta_idioma${seleccionado}\" onclick=\"mostrarTxt('{$this->campo_id_pref}{$this->indice}_', ${i})\">{$leng[0]}</label>";

if($this->formato)
 {
 		$retorno .= '
  <div>';
		if($this->item == 'privacy_policy')
		 {
		/*
		 *  Parche Zooko encabezados para seccion Privacy Policy
		 */
		$retorno .= '
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'**\',\'**\',\'Texto en negrita\')" title="Texto en negrita"><img src="http://admin.'.DOMINIO.'/img/silk/text_bold" alt="Texto en negrita" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'//\',\'//\',\'Texto en cursiva\')" title="Texto en cursiva"><img src="http://admin.'.DOMINIO.'/img/silk/text_italic" alt="Texto en cursiva" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'__\',\'__\',\'Texto subrayado\')" title="Texto subrayado"><img src="http://admin.'.DOMINIO.'/img/silk/text_underline" alt="Texto subrayado" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n== \',\' ==\n\',\'Texto de titular\')" title="Titular de nivel 2"><img src="http://admin.'.DOMINIO.'/img/silk/text_heading_2" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n=== \',\' ===\n\',\'Texto de titular\')" title="Titular de nivel 3"><img src="http://admin.'.DOMINIO.'/img/silk/text_heading_3" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n==== \',\' ====\n\',\'Texto de titular\')" title="Titular de nivel 4"><img src="http://admin.'.DOMINIO.'/img/silk/text_heading_4" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n===== \',\' =====\n\',\'Texto de titular\')" title="Titular de nivel 5"><img src="http://admin.'.DOMINIO.'/img/silk/text_heading_5" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n====== \',\' ======\n\',\'Texto de titular\')" title="Titular de nivel 6"><img src="http://admin.'.DOMINIO.'/img/silk/text_heading_6" alt="Titular de nivel 2" /></button>';
		 }
		$retorno .= '
   <button type="button" onclick="insertarEnlace(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\')" title="Enlace externo"><img src="http://admin.'.DOMINIO.'/img/silk/link" alt="Enlace externo" /></button>
   <button type="button" onclick="agAdjunto(this, 3, \'/subir_imagen\', '.$this->indice.', \'imgAreaTexto\')" title="Imagen incorporada"><img src="http://admin.'.DOMINIO.'/img/silk/image" alt="Imagen incorporada" /></button><!-- abrirModal(\'./examinar/3/2\', 680, 450) -->
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'¶\' ,\'\',\'\')" title="Marca de corte"><img src="http://admin.'.DOMINIO.'/img/silk/pilcrow" alt="Marca de corte" /></button>
</div>';
//insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'[[\',\']]\',\'http://www.ejemplo.com|Título del enlace\')
//insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'{{\',\'}}\',\'Ejemplo.jpg\')
 }
		$retorno .= "
<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"areadetexto\" rows=\"20\" cols=\"55\"{$estilo} lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">".htmlspecialchars($this->valores[$leng_id]['text'])."</textarea></li>";
		$i++;
		$estilo = " style=\"display:none;\"";
		$seleccionado = "";
	   }
	  $retorno .= "</ul>
	 <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}_1'; </script></td></tr>";// tabindex=\"2\"
	  return $retorno;
	 }
   }
  private function campotext2()
   {
	//if(empty($this->subtipo)) $this->subtipo = "";
	if($this->subtipo == 1)
	 {
	  $sel_prot[$this->valores[0]['int']] = " selected=\"selected\"";
	  $prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
	  //<option value=\"2\"{$sel_prot[2]}>https://</option><option value=\"3\"{$sel_prot[3]}>ftp://</option><option value=\"4\"{$sel_prot[4]}>gopher://</option><option value=\"5\"{$sel_prot[5]}>mailto:</option><!-- option value=\"6\">wais</option --></select>
	  if($this->extra) eval('$prot = '.$this->extra.';');
	  else $prot = array(1);
	  $tot_prot = count($prot);
	  if(!$tot_prot)
	   {
	   	$prot = array(1);
	   	$tot_prot = 1;
	   }
	  if($tot_prot == 1)
		$prot_str = "<input type=\"hidden\" name=\"prot[{$this->id}]\" value=\"{$prot_arr[current($prot)]}\" />".$prot_arr[current($prot)];
	  else
	   {
	    $prot_str = "<select name=\"prot[{$this->id}]\">";
	    foreach($prot AS $prot_k => $prot_v) $prot_str .= "<option value=\"${prot_k}\"{$sel_prot[$prot_k]}>${prot_arr[$prot_k]}</option>";
	    $prot_str .= "</select>";
	   }

	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(0, $this->campo_id_pref.$this->indice)."<td>{$prot_str} <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['text'])."\" size=\"45\" /> <a href=\"#\" target=\"_blank\" onclick=\"return abrirEnlace(this)\"><img src=\"/img/externo\" alt=\"Abrir enlace\" class=\"enlace_img\" /></a></td>";// tabindex=\"2\"
	 }
	else
	 {
	  $retorno = $this->label(1)."<td><ul class=\"lista_idiomas\">";
	  $i = 1;
	  $estilo = " style=\"display:block;\"";
	  $seleccionado = " seleccionado";
	  foreach($this->lenguajes AS $leng_id => $leng)
	   {
		$nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][${leng_id}][]";
		$retorno .= "<li><label id=\"p{$this->campo_id_pref}{$this->indice}_${i}\" for=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"etiqueta_idioma${seleccionado}\" onclick=\"mostrarTxt('{$this->campo_id_pref}{$this->indice}_', ${i})\">{$leng[0]}</label><textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"areadetexto\" rows=\"20\" cols=\"55\"{$estilo} lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">".htmlspecialchars($this->valores[$leng_id]['text'])."</textarea></li>";
		$i++;
		$estilo = " style=\"display:none;\"";
		$seleccionado = "";
	   }
	  $retorno .= "</ul>
	 <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}_1'; </script></td>";// tabindex=\"2\"
	  return $retorno;
	 }

	//return $this->label(2)."<td><textarea name=\"_{$this->campo_nombre_pref}{$this->v}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" rows=\"20\" cols=\"55\">{$this->valor}</textarea></td>";
   }

  private function camponum()
   {
return "<td colspan=\"2\">num {$this->subtipo}</td></tr>";
   	 }
  private function camponum2()
   {
   	global $mysqli;
	$nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	if(empty($this->subtipo))
	 {
	  if(!$cons_moneda = $mysqli->query("SELECT simbolo_izq, simbolo_der, decimales FROM monedas WHERE id = '{$this->extra}' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
	  if($fila_moneda = $cons_moneda->fetch_assoc())
	   {
		$simbolo_izq = $fila_moneda['simbolo_izq'] ? $fila_moneda['simbolo_izq']." " : "";
		$simbolo_der = $fila_moneda['simbolo_der'] ? " ".$fila_moneda['simbolo_der'] : "";
		$decimales = $fila_moneda['decimales'];
		$cons_moneda->close();
	   }
	  else $decimales = 2;
	  $valor = $this->valores[0] ? number_format($this->valores[0]['num'], $decimales, '.', '') : false;
	 }
	else
	  $valor = $this->valores[0]['num'];
	return $this->label(2)."<td>${simbolo_izq}<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$valor}\" size=\"10\" />${simbolo_der}</td>";// tabindex=\"2\"
   }
 }

?>