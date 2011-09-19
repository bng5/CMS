<?php

/**
 * Description of Formstring
 *
 * @author pablo
 */
class VistaAdmin_Formtext extends VistaAdmin_Form {
	
  public $id, $nombre, $indice = 0, $sugerido, $unico, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log, $formato;
  private $item, $label, $v, $pref, $campo_nombre_pref, $niveles;//, $x = array("id" => 0)
  private $tipo = 'text';

  public function __construct($item_id = false) {

   	global $mysqli;
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
	if($tipo == 1)
	  return "<td><label>{$this->nombre}:</label></td>\n";
	else
	 {
	  //$for = empty($for) ? $this->campo_id_pref.$this->indice : $for;
	  return "<td><label for=\"{$this->campo_id_pref}{$this->indice}\">{$this->nombre}:</label></td>";
	 }
   }

  public function mostrar() {
	  $this->log .= "\n\nid: {$this->id}\nsuperior: {$this->superior}\n";
	//if($this->superior_niv != $this->superior)
	// {
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
	 // else


	 //}
	//array_push($this->niveles, $this->superior);
	array_push($this->niveles, $this->id);
$this->log .= var_export($this->niveles, true)."\n";
$this->log .= var_export($this->valores, true)."\n";
	//$this->superior_niv = $this->superior;
	$this->superior_niv = $this->id;

   	$campo_tipo = "campo".$this->tipo;
	//$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
	$this->indice++;
	echo $retorno.$this->$campo_tipo();
return;


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
	return "<td colspan=\"2\">No se ha especificado el tipo de campo.</td>";
   }




  private function campotext()
   {
   	// enlace externo
	if($this->subtipo == 1)
	 {
	  $sel_prot[$this->valores[0]['int']] = " selected=\"selected\"";
	  $prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
	  //<option value=\"2\"{$sel_prot[2]}>https://</option><option value=\"3\"{$sel_prot[3]}>ftp://</option><option value=\"4\"{$sel_prot[4]}>gopher://</option><option value=\"5\"{$sel_prot[5]}>mailto:</option><!-- option value=\"6\">wais</option --></select>
	  $prot = $this->extra ? unserialize($this->extra) : array(1);
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
	    foreach($prot AS $prot_k => $prot_v)
		  $prot_str .= "<option value=\"${prot_k}\"{$sel_prot[$prot_k]}>${prot_arr[$prot_k]}</option>";
	    $prot_str .= "</select>";
	   }

	  $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
	  return $this->label(0, $this->campo_id_pref.$this->indice)."<td>{$prot_str} <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['text'])."\" size=\"45\" /> <a href=\"#\" target=\"_blank\" onclick=\"return abrirEnlace(this)\"><img src=\"/img/externo\" alt=\"Abrir enlace\" class=\"enlace_img\" /></a></td>";// tabindex=\"2\"
	 }
	// textarea
	else
	 {
	  $retorno = $this->label(1)."<td>";
	  if($this->formato)
	   {
 		$retorno .= '
  <div>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'],\'**\',\'**\',\'Texto en negrita\')" title="Texto en negrita"><img src="/img/silk/text_bold" alt="Texto en negrita" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'//\',\'//\',\'Texto en cursiva\')" title="Texto en cursiva"><img src="/img/silk/text_italic" alt="Texto en cursiva" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'__\',\'__\',\'Texto subrayado\')" title="Texto subrayado"><img src="/img/silk/text_underline" alt="Texto subrayado" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'\n== \',\' ==\n\',\'Texto de titular\')" title="Titular de nivel 2"><img src="/img/silk/text_heading_2" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'\n=== \',\' ===\n\',\'Texto de titular\')" title="Titular de nivel 3"><img src="/img/silk/text_heading_3" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'\n==== \',\' ====\n\',\'Texto de titular\')" title="Titular de nivel 4"><img src="/img/silk/text_heading_4" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n===== \',\' =====\n\',\'Texto de titular\')" title="Titular de nivel 5"><img src="/img/silk/text_heading_5" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'\n====== \',\' ======\n\',\'Texto de titular\')" title="Titular de nivel 6"><img src="/img/silk/text_heading_6" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertarEnlace(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\')" title="Enlace externo"><img src="/img/silk/link" alt="Enlace externo" /></button>
   <button type="button" onclick="agAdjunto(this, 3, \'/subir_imagen\', '.$this->indice.', \'imgAreaTexto\')" title="Imagen incorporada"><img src="/img/silk/image" alt="Imagen incorporada" /></button><!-- abrirModal(\'./examinar/3/2\', 680, 450) -->
   <button type="button" onclick="insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'¶\' ,\'\',\'\')" title="Marca de corte"><img src="/img/silk/pilcrow" alt="Marca de corte" /></button>
  </div>';


//insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'[[\',\']]\',\'http://www.ejemplo.com|Título del enlace\')
//insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'{{\',\'}}\',\'Ejemplo.jpg\')
		 }
 		$retorno .= '<ul class="lista_idiomas">';
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

		$retorno .= "
<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_${i}\" class=\"areadetexto\" rows=\"20\" cols=\"55\"{$estilo} lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">".htmlspecialchars($this->valores[$leng_id]['text'])."</textarea></li>";
		$i++;
		$estilo = " style=\"display:none;\"";
		$seleccionado = "";
	   }
	  $retorno .= "</ul>
	 <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}_1'; </script></td>";// tabindex=\"2\"
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



}

?>