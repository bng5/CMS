<?php

/**
 * Description of Str
 *
 * @author pablo
 */
class View_Admin_Field_Str extends View_Admin {


    
    public function label() {}
    
    public function show() {

var_dump(CMS::getInstance());

            $i = 1;
            $multi_l = (count($this->lenguajes) > 1) ? true : false;
            $retorno = $this->label(1) . "<td>";
            if ($multi_l)
                $retorno .= "<ul class=\"campo_lista\">";
            foreach ($this->lenguajes AS $leng_id => $leng) {
                $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
                if ($multi_l)
                    $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
                $retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" value=\"" . htmlspecialchars($this->valores[$leng_id]['string']) . "\" size=\"45\" maxlength=\"200\" lang=\"{$leng[0]}\" xml:lang=\"{$leng[0]}\" dir=\"{$leng[1]}\" />";
                if ($multi_l)
                    $retorno .= "</li>";
                $i++;
            }
            // tabindex=\"2\"
            if ($multi_l)
                $retorno .= "</ul>";
            echo  $retorno . "</td>";

    }
}
