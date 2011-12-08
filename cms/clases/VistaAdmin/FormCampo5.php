<?php

/**
 * Description of Formstring
 *
 * @author pablo
 */
class VistaAdmin_FormCampo5 extends VistaAdmin_Form {

    public $id, $nombre, $indice = 0, $sugerido, $unico, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log;
    private $item, $label, $v, $pref, $campo_nombre_pref, $niveles; //, $x = array("id" => 0)
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
        if ($fila_lengs = $cons_lengs->fetch_row()) {
            $this->leng_poromision = $fila_lengs[0];
            do {
                $this->lenguajes[$fila_lengs[0]] = array($fila_lengs[1], $fila_lengs[2]);
            } while ($fila_lengs = $cons_lengs->fetch_row());
            $cons_lengs->close();
        }
    }

    function __destruct() {
        $pop = end($this->niveles);
        while ($pop != 0) {
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

    /*  protected function label($tipo = 0, $for = '')
      {
      if($tipo == 1)
      return "<td><label>{$this->nombre}:</label></td>\n";
      else
      {
      //$for = empty($for) ? $this->campo_id_pref.$this->indice : $for;
      return "<td><label for=\"{$this->campo_id_pref}{$this->indice}\">{$this->nombre}:</label></td>";
      }
      }
     */

    public function mostrar() {
        $this->log .= "\n\nid: {$this->id}\nsuperior: {$this->superior}\n";
        //if($this->superior_niv != $this->superior)
        // {
        if (in_array($this->superior, $this->niveles)) {
            $retorno = '';
            $ii = count($this->niveles);
            $pop = end($this->niveles);
            while ($pop != $this->superior) {
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
        $this->log .= var_export($this->niveles, true) . "\n";
        $this->log .= var_export($this->valores, true) . "\n";
        //$this->superior_niv = $this->superior;
        $this->superior_niv = $this->id;

        $campo_tipo = "campo" . $this->tipo;
        //$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
        $this->indice++;

        $retorno = $this->label(1) . "<td>";
        if ($this->extra['f']) {
            $retorno .= '
  <div>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'**\',\'**\',\'Texto en negrita\')" title="Texto en negrita"><img src="img/silk/text_bold" alt="Texto en negrita" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'//\',\'//\',\'Texto en cursiva\')" title="Texto en cursiva"><img src="img/silk/text_italic" alt="Texto en cursiva" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'__\',\'__\',\'Texto subrayado\')" title="Texto subrayado"><img src="img/silk/text_underline" alt="Texto subrayado" /></button>
   <button type="button" onclick="insertarEnlace(actual[\''.$this->campo_id_pref.$this->indice.'_\'])" title="Enlace externo"><img src="img/silk/link" alt="Enlace externo" /></button>';
            if ($this->extra['largo']) {
                $retorno .= '
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'\n== \',\' ==\n\',\'Texto de titular\')" title="Titular de nivel 2"><img src="img/silk/text_heading_2" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'\n=== \',\' ===\n\',\'Texto de titular\')" title="Titular de nivel 3"><img src="img/silk/text_heading_3" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'\n==== \',\' ====\n\',\'Texto de titular\')" title="Titular de nivel 4"><img src="img/silk/text_heading_4" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'\n===== \',\' =====\n\',\'Texto de titular\')" title="Titular de nivel 5"><img src="img/silk/text_heading_5" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'\n====== \',\' ======\n\',\'Texto de titular\')" title="Titular de nivel 6"><img src="img/silk/text_heading_6" alt="Titular de nivel 2" /></button>
   <!-- button type="button" onclick="agAdjunto(this, 3, \'/subir_imagen\', actual[\''.$this->campo_id_pref.$this->indice.'_\'], \'imgAreaTexto\')" title="Imagen incorporada"><img src="img/silk/image" alt="Imagen incorporada" /></button --><!-- abrirModal(\'./examinar/3/2\', 680, 450) -->
   <button type="button" onclick="insertTags(actual[\''.$this->campo_id_pref.$this->indice.'_\'] ,\'¶\' ,\'\',\'\')" title="Marca de corte"><img src="img/silk/pilcrow" alt="Marca de corte" /></button>';
            }
            $retorno .= '
  </div>';
//  <div>
//   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'],\'**\',\'**\',\'Texto en negrita\')" title="Texto en negrita"><img src="/img/silk/text_bold" alt="Texto en negrita" /></button>
//   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'//\',\'//\',\'Texto en cursiva\')" title="Texto en cursiva"><img src="/img/silk/text_italic" alt="Texto en cursiva" /></button>
//   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'__\',\'__\',\'Texto subrayado\')" title="Texto subrayado"><img src="/img/silk/text_underline" alt="Texto subrayado" /></button>
//  </div>';

            //insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'[[\',\']]\',\'http://www.ejemplo.com|Título del enlace\')
            //insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'{{\',\'}}\',\'Ejemplo.jpg\')
        }
        if($this->unico == 1) {
            $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            $retorno .= "
	<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" rows=\"20\" cols=\"55\">" . htmlspecialchars($this->valores[0]['text']) . "</textarea>
    <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}'; </script>";
        }
        elseif ($this->unico == 2) {
            $retorno .= '<ul class="lista_idiomas">';
            $i = 1;
            $estilo = " style=\"display:block;\"";
            $seleccionado = " seleccionado";
            $tot_lengs = count($this->lenguajes);
            foreach ($this->lenguajes AS $leng_id => $leng) {
                $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
                $retorno .= "<li>";
                if ($tot_lengs > 1)
                    $retorno .= "<label id=\"p{$this->campo_id_pref}{$this->indice}_{$i}\" for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma{$seleccionado}\" onclick=\"mostrarTxt('{$this->campo_id_pref}{$this->indice}_', {$i})\">{$leng[0]}</label>";
                $retorno .= "
<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"areadetexto\" rows=\"20\" cols=\"55\"{$estilo} lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">" . htmlspecialchars($this->valores[$leng_id]['text']) . "</textarea></li>";
                $i++;
                $estilo = " style=\"display:none;\"";
                $seleccionado = "";
            }
            $retorno .= "</ul>
	 <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}_1'; </script>"; // tabindex=\"2\"
        }
        echo $retorno . '</td>';
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

    private function campo($retorno = '') {
        return "<td colspan=\"2\">No se ha especificado el tipo de campo.</td>";
    }

    private function campotext() {

        $retorno = $this->label(1) . "<td>";
        if ($this->extra['f']) {
            $retorno .= '
  <div>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'],\'**\',\'**\',\'Texto en negrita\')" title="Texto en negrita"><img src="img/silk/text_bold" alt="Texto en negrita" /></button>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'//\',\'//\',\'Texto en cursiva\')" title="Texto en cursiva"><img src="img/silk/text_italic" alt="Texto en cursiva" /></button>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'__\',\'__\',\'Texto subrayado\')" title="Texto subrayado"><img src="img/silk/text_underline" alt="Texto subrayado" /></button>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'\n== \',\' ==\n\',\'Texto de titular\')" title="Titular de nivel 2"><img src="img/silk/text_heading_2" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'\n=== \',\' ===\n\',\'Texto de titular\')" title="Titular de nivel 3"><img src="img/silk/text_heading_3" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(actual[\'' . $this->campo_id_pref . $this->indice . '_\'] ,\'\n==== \',\' ====\n\',\'Texto de titular\')" title="Titular de nivel 4"><img src="img/silk/text_heading_4" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\'' . $this->campo_id_pref . $this->indice . '_' . $i . '\' ,\'\n===== \',\' =====\n\',\'Texto de titular\')" title="Titular de nivel 5"><img src="img/silk/text_heading_5" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertTags(\'' . $this->campo_id_pref . $this->indice . '_' . $i . '\' ,\'\n====== \',\' ======\n\',\'Texto de titular\')" title="Titular de nivel 6"><img src="img/silk/text_heading_6" alt="Titular de nivel 2" /></button>
   <button type="button" onclick="insertarEnlace(\'' . $this->campo_id_pref . $this->indice . '_' . $i . '\')" title="Enlace externo"><img src="img/silk/link" alt="Enlace externo" /></button>
   <button type="button" onclick="agAdjunto(this, 3, \'/subir_imagen\', ' . $this->indice . ', \'imgAreaTexto\')" title="Imagen incorporada"><img src="img/silk/image" alt="Imagen incorporada" /></button><!-- abrirModal(\'./examinar/3/2\', 680, 450) -->
   <button type="button" onclick="insertTags(\'' . $this->campo_id_pref . $this->indice . '_' . $i . '\' ,\'¶\' ,\'\',\'\')" title="Marca de corte"><img src="img/silk/pilcrow" alt="Marca de corte" /></button>
  </div>';

            //insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'[[\',\']]\',\'http://www.ejemplo.com|Título del enlace\')
            //insertTags(\''.$this->campo_id_pref.$this->indice.'_'.$i.'\' ,\'{{\',\'}}\',\'Ejemplo.jpg\')
        }
        $retorno .= '<ul class="lista_idiomas">';
        $i = 1;
        $estilo = " style=\"display:block;\"";
        $seleccionado = " seleccionado";
        $tot_lengs = count($this->lenguajes);
        foreach ($this->lenguajes AS $leng_id => $leng) {
            $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
            $retorno .= "<li>";
            if ($tot_lengs > 1)
                $retorno .= "<label id=\"p{$this->campo_id_pref}{$this->indice}_{$i}\" for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma{$seleccionado}\" onclick=\"mostrarTxt('{$this->campo_id_pref}{$this->indice}_', {$i})\">{$leng[0]}</label>";
            $retorno .= "
<textarea name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"areadetexto\" rows=\"20\" cols=\"55\"{$estilo} lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\">" . htmlspecialchars($this->valores[$leng_id]['text']) . "</textarea></li>";
            $i++;
            $estilo = " style=\"display:none;\"";
            $seleccionado = "";
        }
        $retorno .= "</ul>
	 <script type=\"text/javascript\"> actual['{$this->campo_id_pref}{$this->indice}_'] = '{$this->campo_id_pref}{$this->indice}_1'; </script></td>"; // tabindex=\"2\"
        return $retorno;
    }

}

