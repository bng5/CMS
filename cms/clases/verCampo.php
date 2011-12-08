<?php

class verCampo {

    public $id, $nombre, $sugerido, $unico, $tipo, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra;
    private $item, $indice = 0, $label, $v, $pref, $campo_nombre_pref; //, $x = array("id" => 0)

    public function __construct($pref = '', $area = false) {
        global $mysqli, $id;
        $this->campo_id_pref = $this->campo_nombre_pref = $pref . "dato";
        if ($pref != '') {
            $this->campo_nombre_pref .= "[{$area}]";
            $this->campo_id_pref .= "_{$area}_";
        }
        $this->pref = $pref;
        $this->item = $id;
        $this->lengs = array();
        $this->lenguajes = array();
        $cons_lengs = $mysqli->query("SELECT id, leng_cod, dir FROM lenguajes l ORDER BY leng_poromision DESC");
        if ($fila_lengs = $cons_lengs->fetch_row()) {
            $this->leng_poromision = $fila_lengs[0];
            do {
                $this->lengs[$fila_lengs[0]] = $fila_lengs[1];
                $this->lenguajes[$fila_lengs[0]] = array($fila_lengs[1], $fila_lengs[2]);
            } while ($fila_lengs = $cons_lengs->fetch_row());
            $cons_lengs->close();
        }
    }

    private function label($tipo = 0, $for = '') {
        if ($tipo == 1)
            return "<td><label>{$this->nombre}:</label></td>\n";
        else {
            //$for = empty($for) ? $this->campo_id_pref.$this->indice : $for;
            return "<td><label for=\"{$this->campo_id_pref}{$this->indice}\">{$this->nombre}:</label></td>\n";
        }
    }

    public function imprimir() {
        $this->indice++;
        $campo_tipo = "campo" . $this->tipo;
        //$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
        return $this->$campo_tipo();
        /*
          [sugerido] => 2
          [unico] => 1
          [tipo] => string
          [subtipo] =>
          [nombre] => Nombre
          [identificador] => nombre

          +----+--------+---------+-----------------------+---------------------------+-------------+
          | id | tipo   | subtipo | nombre                | tabla valores por omisión | Multilingüe |
          +----+--------+---------+-----------------------+---------------------------+-------------+
          |  1 | string |    NULL | Campo de texto        | items_valores             | Si          |
          |  2 | string |       1 | Color                 | items_valores             |             |
          |  3 | string |       2 | Contraseña            | NO                        |             |
          | 13 | string |       3 | Selector múltiple     | campos_opciones           |             |
          | 12 | string |       4 | Checkbox              | campos_opciones           |             |
          |  4 | date   |    NULL | Fecha y hora          | items_valores             |             |
          |  5 | date   |       1 | Fecha                 | items_valores             |             |
          | 15 | text   |    NULL | Texto                 | items_valores             | Si          |
          |  6 | int    |    NULL | Número natural (ℕ)    |                           |             |
          |  7 | int    |       1 | Dato externo          |                           |             |
          |  8 | int    |       2 | Imagen                | imagenes                  |             |
          |  9 | int    |       3 | Archivo               | archivos                  |             |
          | 10 | int    |       4 | Set de imágenes       |                           |             |
          | 14 | int    |       5 | Selector              | campos_opciones           |             |
          | 11 | int    |       8 | Radio                 | campos_opciones           |             |
          | 16 | num    |    NULL | Precio                | items_valores             |             |
          | 17 | num    |       1 | Número entero (ℤ)     | items_valores             |             |
          | 18 | int    |       6 | Rango                 | DEFINIR                   |             |
          +----+--------+---------+-----------------------+---------------------------+-------------+

         */
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

    private function campo() {
        return "<tr><td colspan=\"2\">No se ha especificado el tipo de campo.</td></tr>";
    }

    private function campostring() {
        /*
         * 	    +---------------------------------------------------------------------------+
         *  	|  1 | string |    NULL | Campo de texto        | items_valores             |
         *  	|  2 | string |       1 | Color                 | items_valores             |
         *  	|  3 | string |       2 | Contraseña            | NO                        |
         *  	| 13 | string |       3 | Selector múltiple     | campos_opciones           |
         *  	| 12 | string |       4 | Checkbox              | campos_opciones           |
         * 	    +---------------------------------------------------------------------------+
         */
        if ($this->subtipo == 1) {
            $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            $retorno = $this->label(1) . "<td>";
//
            if ($this->unico == 1)
                $retorno .= "<img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$this->valores[0]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[0]['string']}\" />";
            else {
                $retorno .= "<ul>";
                $k = @each($this->valores);
                do {
                    $nombre_campo = $this->valores[$k[0]] ? "[m][{$this->id}][{$this->valores[$k[0]]['id']}]" : "[n][{$this->id}][]";
                    $idJS = $this->valores[$k[0]] ? $this->valores[$k[0]]['id'] : "null";
                    $retorno .= "<li><img src=\"/img/trans\" onclick=\"paletaDeColores(this, this.nextSibling).mostrar()\" class=\"muestraColor\" style=\"background-color:#{$k[1]['string']};\" width=\"22\" height=\"22\" alt=\"{$this->valores[$k[0]]['string']}\" /></li>";
                } while ($k = @each($this->valores));
                $pref = $this->pref ? $this->pref : "sup";
                $retorno .= "</ul>";
            }
            return $retorno . "</td>";
        } elseif ($this->subtipo == 2) {
            $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
            return $this->label(2) . "<td>******</td>"; // tabindex=\"2\"
        } elseif ($this->subtipo == 4) {
            global $mysqli;
            $nombre_campo = $this->valores[0] ? "[m][{$this->valores[0]['id']}][]" : "[n][{$this->id}][][]";
            if (!$cons = $mysqli->query("SELECT id FROM campos_opciones WHERE campo_id = {$this->id}"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila = $cons->fetch_row()) {
                $i = 0;
                do {
                    $retorno .= "<li><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}{$i}\" /> <label for=\"{$this->campo_nombre_pref}{$this->indice}{$i}\">{$fila[0]}</label></li>";
                    $i++;
                } while ($fila = $cons->fetch_row());
            }
            return $this->label(2) . "<td>{$this->id} " . var_export($this->valores, true) . " {$this->valores[0]['string']} <ul>{$retorno}</ul></td>"; // tabindex=\"2\"
        } elseif ($this->subtipo == 5) {
            $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
            return $this->label(2) . "<td><input type=\"hidden\" name=\"\" value=\"\" /><input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" size=\"20\" maxlength=\"45\" /> -&gt; <input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" size=\"20\" maxlength=\"45\" /></td>"; // tabindex=\"2\"
        } elseif ($this->subtipo == 6) {
            $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            return $this->label(0, $this->campo_id_pref . $this->indice) . "<td>" . htmlspecialchars($this->valores[0]['string']) . "</td>"; // tabindex=\"2\"
        } else {
            //if(empty($this->subtipo)) $this->subtipo = "";
            $i = 1;
            $multi_l = (count($this->lengs) > 1) ? true : false;
            $retorno = $this->label(1) . "<td>";
            if ($multi_l)
                $retorno .= "<ul class=\"campo_lista\">";
            foreach ($this->lenguajes AS $leng_id => $leng) {
                $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
                if ($multi_l)
                    $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng[0]})</tt></label>&nbsp;";
                $retorno .= htmlspecialchars($this->valores[$leng_id]['string']);
                if ($multi_l)
                    $retorno .= "</li>";
                $i++;
            }
            // tabindex=\"2\"
            if ($multi_l)
                $retorno .= "</ul>";
            return $retorno . "</td>";
        }
    }

    private function campodate() {
        global $texto;
        // fecha
        if ($this->subtipo == 1) {
            //$fecha = $this->valor ? date("Y-m-d", $this->valor) : "";
            $this->valores[0]['date'] = substr($this->valores[0]['date'], 0, 10);
            $fechaMst = formato_fecha($this->valores[0]['date'], true, false);
            $formato = "%Y-%m-%d";
            $formatoMst = "%A, %d de %B de %Y";
            $mostrarHora = "false";
        }
        // fecha y hora
        else {
            //$fecha = $this->valor ? date("Y-m-d G:i", $this->valor) : "";
            $fechaMst = formato_fecha($this->valores[0]['date']);
            $formato = "%Y-%m-%d %H:%m";
            $formatoMst = "%A, %d de %B de %Y, %H:%m hs.";
            $mostrarHora = "true";
        }
        // onclick=\"return showCalendar('fecha_fin', '%A, %B %e, %Y');\"

        $nombre_campo = $this->valores[0]['date'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
        return $this->label(1) . "<td><span id=\"mostrar_fecha{$this->indice}\">{$fechaMst}</span></td>";
    }

    private function campoint() {
        global $mysqli;
        if ($this->subtipo == 1) {
            $ret = $this->label(1) . "<td>";
            if (!$cons_vista = $mysqli->query($this->extra . "1 ORDER BY 2"))
                $ret .= "Existe un error en la configuración de este campo</td>";
            else {
                if ($fila_vista = $cons_vista->fetch_row()) {
                    $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
                    $ret .= "<select name=\"{$this->campo_nombre_pref}{$nombre_campo}\"><option value=\"\"> </option>";
                    do {
                        $ret .= "<option value=\"{$fila_vista[0]}\"";
                        if ($fila_vista[0] == $this->valores[0]['int'])
                            $ret .= " selected=\"selected\"";
                        $ret .= ">" . htmlspecialchars($fila_vista[1]) . "</option>";
                    }while ($fila_vista = $cons_vista->fetch_row());
                    $ret .= "</select></td>";
                    $cons_vista->close();
                }
            }
            return $ret;
        }
        // imagen
        elseif ($this->subtipo == 2) {
            //$ret = $label;
            //global $mysqli;//, $p_seccion_id;
            $valor = $this->valores[0]['int'] ? $this->valores[0]['int'] : $this->poromision;
            if (!$cons = $mysqli->query("SELECT id, `archivo` FROM `imagenes_orig` WHERE `id` = '{$valor}' LIMIT 1"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila = $cons->fetch_row()) {
                $img_id = $fila[0];
                $img = "/icono/0/{$this->id}/{$fila[1]}";
                $etiqueta = "Cambiar";
                $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            } else {
                $img_id = false;
                $img = "/img/trans";
                $etiqueta = "Agregar";
                $nombre_campo = "[n][{$this->id}][]";
            }
            $retorno = $this->label(1) . "<td><img src=\"{$img}\" id=\"img{$this->indice}\" alt=\"{$fila[0]}\" /></td>";
            return $retorno;
            //$bsq = ($_SESSION['permisos'][$p_seccion_id] < 4) ? "AND bsq = '".$_SESSION['usuario_id']."'" : null;
            //$bsq = false;
        }
        // archivo
        elseif ($this->subtipo == 3) {
            $retorno = "";
            if (is_array($this->valores))
                foreach ($this->valores AS $nu_v)
                    $acons[$nu_v['int']] = $nu_v['id'];
            if (is_array($acons)) {
                //sort($acons);
                $valor = implode("' OR id = '", array_keys($acons));
            }
            else
                $valor = $this->poromision;
            // }
            if (!$cons = $mysqli->query("SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' ORDER BY id"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila = $cons->fetch_row()) {
                $etiqueta = "Cambiar";
                if ($this->unico) {
                    $img_id = $fila[0];
                    $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
                    $retorno .= "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"borrarOpArch(this)\" />";
                } else {
                    $retorno = "<ul id=\"lista_{$this->indice}\">";
                    $i = 1;
                    do {
                        $img_id = $fila[0];
                        //$img = "/icono/imagenes/{$fila[1]}";
                        //$archivo_nombre = "{$fila[2]}<img src=\"/img/b_drop_ch.png\" alt=\"Eliminar\" title=\"Eliminar\" onclick=\"alert('esto va a eliminar el archivo')\" />";
                        $nombre_campo = "[m][{$this->id}][{$acons[$img_id]}]";
                        $retorno .= "<li><span id=\"archivo{$this->indice}_{$i}\">{$fila[2]}</span></li>";
                        $i++;
                    } while ($fila = $cons->fetch_row());
                    $retorno .= "</ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>"; //onclick=\"agregarOp('{$this->tipo}', {$this->subtipo}, this, {$this->id}, '{$this->campo_nombre_pref}', '{$pref}')\"
                }
            } else {
                $img_id = false;
                //$img = "/img/trans";
                $etiqueta = "Agregar";
                $nombre_campo = "[n][{$this->id}][]";
            }
            //$retorno = $this->label(1)."<td><pre>SELECT id, archivo, nombre FROM `archivos` WHERE `id` = '{$valor}' LIMIT 1\n".var_export($this->valores, true)."</pre><span id=\"archivo{$this->indice}\">{$archivo_nombre}</span> <button type=\"button\" onclick=\"agAdjunto(this, {$this->id}, '/subir_archivo', {$this->indice})\"><span>{$etiqueta}</span></button><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" /></td>";
            if (empty($retorno))
                $retorno = $this->unico ? "<span id=\"archivo{$this->indice}\">{$fila[2]}</span><img src=\"img/trans\" alt=\"\" /><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" value=\"{$img_id}\" />" : "<ul id=\"lista_{$this->indice}\"></ul><a onclick=\"agAdjunto(this.previousSibling, {$this->id}, '/subir_archivo', {$this->indice})\">Agregar</a>";

            return $this->label(1) . "<td>{$retorno}</td>";
            //return $retorno;
        }
        // galería
        elseif ($this->subtipo == 4) {
            $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            $valores = array();
            $imagenes = array();
            $retorno = "<td colspan=\"2\"><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" value=\"{$this->valores[0]['int']}\" /><div id=\"galeria\">";
            if (!$consulta_imgs = $mysqli->query("SELECT gi.imagen_id, gi.estado, io.archivo, io.formato, gi.orden FROM galerias_imagenes gi JOIN imagenes_orig io ON gi.imagen_id = io.id WHERE gi.galeria_id = '{$this->valores[0]['int']}' ORDER BY gi.orden"))
                die("\n" . __LINE__ . " mySql: " . $mysqli->error);
            if ($total_imgs = $consulta_imgs->num_rows) {
                while ($fila_imgs = $consulta_imgs->fetch_row()) {
                    $imagenes[$fila_imgs[0]] = array();
                    //echo "<input type=\"hidden\" name=\"img_estado[{$fila_imgs[0]}]\" value=\"{$fila_imgs[5]}\" /><input type=\"hidden\" name=\"img_titulo[{$fila_imgs[0]}]\" value=\"{$fila_imgs[2]}\" /><input type=\"hidden\" name=\"img_fecha[{$fila_imgs[0]}]\" id=\"img_fecha{$fila_imgs[0]}\" value=\"{$fila_imgs[4]}\" /><textarea name=\"img_texto[{$fila_imgs[0]}]\" rows=\"6\" cols=\"40\" class=\"oculto\">{$fila_imgs[3]}</textarea>";
                    $retorno .= "<span></span><input type=\"image\" name=\"imagen[]\" src=\"icono/4/{$fila_imgs[2]}\" value=\"{$fila_imgs[0]}\" onclick=\"return false\" onmousedown=\"mover(this, event);\" onmouseup=\"desplegarImg(this)\" title=\"{$fila_imgs[2]}\" alt=\"{$fila_imgs[2]}\" />";
                }
                $consulta_imgs->close();

                if (!$cons_valores = $mysqli->query("SELECT imagen_id, atributo_id, leng_id, id, `string`, `date`, `text`, `int`, `num` FROM galerias_imagenes_valores g WHERE galeria_id = {$this->valores[0]['int']}"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_valores = $cons_valores->fetch_assoc()) {
                    do {
                        $imagen = array_shift($fila_valores);
                        $img_atributo_id = array_shift($fila_valores);
                        $img_leng_id = array_shift($fila_valores);
                        if ($img_leng_id)
                            $imagenes[$imagen][$img_atributo_id][$img_leng_id] = $fila_valores;
                        else
                            $imagenes[$imagen][$img_atributo_id][] = $fila_valores;
                    }while ($fila_valores = $cons_valores->fetch_assoc());
                    $cons_valores->close();
                }
            }
            //SELECT gi.imagen_id, gi.estado, i.archivo, i.formato, i.peso, gi.orden FROM galerias_imagenes gi JOIN imagenes i ON gi.imagen_id = i.id WHERE gi.galeria_id = '{$this->valores[0]['int']}' ORDER BY gi.orden
            $retorno .= "<span></span></div><input type=\"image\" name=\"eliminarImg\" src=\"img/papelera\" alt=\"Eliminar\" title=\"Arrastre hasta aquí para eliminar\" style=\"background:none;border:none;\" /> <fieldset style=\"display:none;\"
	     ><legend></legend
	      ><img src=\"img/trans\" alt=\"\"
	      />";
            /*             * ****************************************************************** */
//
            $atributos = array();
            if (!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, subitems_supatributos_a_atributos isaa LEFT JOIN subitems_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND sup_atributo_id = '{$this->id}' ORDER BY orden"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila_at = $atributos_tipos->fetch_assoc()) {
                do {
                    $attr_id = array_shift($fila_at);
                    $atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
                } while ($fila_at = $atributos_tipos->fetch_assoc());
                $atributos_tipos->close();
            }
//print_r($atributos);

            if (count($atributos)) {
                $js_leng = array();
                foreach ($this->lengs AS $leng_id => $leng_cod)
                    $js_leng[] = "{$leng_id} : '{$leng_cod}'";
                $retorno .= "<script type=\"text/javascript\">\n//<![CDATA[
		var lenguajes = {" . implode(", ", $js_leng) . "}
		ImgGalObj.prototype.ImgGalTablaInfo = function()
		 {
		  this.tabla = document.createElement('table');
		  this.tabla.setAttribute('id', 'muestra_'+this.id);
		  tbody = document.createElement('tbody');
		  this.tabla.appendChild(tbody);
		  this.tabla.style.display = 'none';\n";
                $this->js = new jsCampo('galimg', $valores_k);
                foreach ($atributos AS $k => $a) {
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

                while (list($valores_k, $valores_arr) = each($imagenes)) {
                    $this->subitem = new formCampo('galimg', $valores_k); //$this->id);
//<pre>".var_export($valores_arr, true)."\nmuestra{$this->valores[0]['int']}_{$valores_k}</pre>
                    $retorno .= "<table id=\"muestra_{$valores_k}\" style=\"display:none;\">";
                    foreach ($atributos AS $k => $a) {
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
                        $retorno .= "<tr>" . $this->subitem->imprimir() . "</tr>";
                    }
                    $retorno .= "</table>";
                }
            }
            /*             * ****************************************************************** */

            /*
              foreach($this->lengs AS $leng_id => $leng_cod)
              {
              $nombre_campo = $this->valores[$leng_id] ? "[m][{$this->id}][{$this->valores[$leng_id]['id']}]" : "[n][{$this->id}][{$leng_id}][]";
              if($multi_l) $retorno .= "<li><label for=\"{$this->campo_id_pref}{$this->indice}_{$i}\" class=\"etiqueta_idioma\"><tt>({$leng_cod})</tt></label>&nbsp;";
              $retorno .= "<input type=\"text\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}_{$i}\" value=\"".htmlspecialchars($this->valores[$leng_id]['string'])."\" size=\"45\" maxlength=\"100\" />";
              if($multi_l) $retorno .= "</li>";
              $i++;
              }
             */

            $retorno .= "</fieldset
		></td>";
            return $retorno;
        }
        // selector
        elseif ($this->subtipo == 5) {
            if (!$cons_vista = $mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = 1 WHERE co.campo_id = {$this->id} ORDER BY co.id"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila_vista = $cons_vista->fetch_row()) {
                $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
                $ret = $this->label(1) . "<td><select name=\"{$this->campo_nombre_pref}{$nombre_campo}\">";
                do {
                    $ret .= "<option value=\"{$fila_vista[0]}\"";
                    if ($fila_vista[0] == $this->valores[0]['int'])
                        $ret .= " selected=\"selected\"";
                    $ret .= ">{$fila_vista[1]}</option>";
                }while ($fila_vista = $cons_vista->fetch_row());
                $ret .= "</select></td>";
                $cons_vista->close();
            }
            return $ret;
        }
        // rango
        elseif ($this->subtipo == 6) {
            if ($this->valores[0]['int']) {
                $ret = $this->label(1) . "<td>";
                $cons_img = $mysqli->query("SELECT id, codigo FROM subitems WHERE item_id = {$this->item} AND atributo_id = {$this->id} ORDER BY codigo");
                if ($img = $cons_img->fetch_row()) {
                    $ret .= "<ul class=\"campo_lista\">";
                    do {
                        $ret .= "<li>{$img[1]}</li>";
                    } while ($img = $cons_img->fetch_row());
                    $ret .= "</ul>";
                }
                return $ret . "</td>";
            } else {
                //$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
                $nombre_campo = "[m][{$this->id}][{$this->valores[0]['id']}]";
                return $this->label(1) . "<td>{$this->valores[0]['int']} <select name=\"extra[{$this->id}]\"><option value=\"0\"> </option><option value=\"1\">Impares</option><option value=\"2\">Pares</option></select></td>";
            }
        }
        // área
        elseif ($this->subtipo == 7) {
            // if($this->valores[0]['int'])
            //  {
            $ret = "<td colspan=\"2\"><fieldset><legend>{$this->nombre}</legend><table>";

            $atributos = array();
            if (!$atributos_tipos = $mysqli->query("SELECT ia.id, ia.sugerido, ia.unico, at.tipo, at.subtipo, ian.atributo, ia.identificador, isaa.por_omision AS poromision, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num`, extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id AND leng_id = '1', atributos_tipos at, subitems_supatributos_a_atributos isaa LEFT JOIN subitems_valores iv ON isaa.atributo_id = iv.atributo_id AND iv.`item_id` IS NULL WHERE ia.tipo_id = at.id AND ia.id = isaa.atributo_id AND sup_atributo_id = '{$this->id}' ORDER BY orden"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila_at = $atributos_tipos->fetch_assoc()) {
                do {
                    $attr_id = array_shift($fila_at);
                    $atributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
                } while ($fila_at = $atributos_tipos->fetch_assoc());
                $atributos_tipos->close();
            }
            /*             * */
            if ($this->item) {
                $valores = array();
                if (!$cons_valores = $mysqli->query("SELECT atributo_id, id, `string`, `date`, `text`, `int`, `num`, leng_id FROM subitems_valores WHERE item_id = {$this->item} AND area_id = {$this->id}"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_valores = $cons_valores->fetch_row()) {
                    do {
                        $valor = $fila_valores[0];
                        if ($fila_valores[7])
                            $valores[$valor][$fila_valores[7]] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
                        else
                            $valores[$valor][] = array('id' => $fila_valores[1], 'string' => $fila_valores[2], 'date' => $fila_valores[3], 'text' => $fila_valores[4], 'int' => $fila_valores[5], 'num' => $fila_valores[6]);
                    }while ($fila_valores = $cons_valores->fetch_row());
                    $cons_valores->close();
                }
            }
            /*             * */
            /*
              $ret .= "
              <pre>".var_export($atributos, true)."
              ".var_export($valores, true)."
              </pre>";
             */
            $this->subitem = new formCampo('sub', $this->id);


            foreach ($atributos AS $k => $a) {
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
                $ret .= "<tr>" . $this->subitem->imprimir() . "</tr>";
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
            return $ret . "</table></fieldset></td>";
            // }
            //else
            // {
            //$nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            $nombre_campo = "[m][{$this->id}][{$this->valores[0]['id']}]";
            return $this->label(1) . "<td>{$this->valores[0]['int']} <select name=\"extra[{$this->id}]\"><option value=\"0\"> </option><option value=\"1\">Impares</option><option value=\"2\">Pares</option></select></td>";
            // }
        }
        // radio
        elseif ($this->subtipo == 8) {
            $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            $ret = $this->label(1) . "<td><ul class=\"campo_lista\">";
            $i = 1;
            eval('$ops = ' . $this->extra . ';');
            foreach ($ops AS $k => $v) {
                $ret .= "<li><input type=\"radio\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\" value=\"{$k}\"";
                if ($this->valores[0]['int'] == $k)
                    $ret .= " checked=\"checked\"";
                $ret .= " /> <label for=\"{$this->campo_nombre_pref}{$this->indice}_{$i}\">{$v}</label></li>";
                $i++;
            }
            return $ret . "</ul></td>";
        }
        // int no definido
        else {
            $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            return $this->label(2) . "<td>{$this->valores[0]['int']}</td>";
        }
    }

    private function campotext() {
        //if(empty($this->subtipo)) $this->subtipo = "";
        if ($this->subtipo == 1) {
            $sel_prot[$this->valores[0]['int']] = " selected=\"selected\"";
            $prot_arr = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");
            //<option value=\"2\"{$sel_prot[2]}>https://</option><option value=\"3\"{$sel_prot[3]}>ftp://</option><option value=\"4\"{$sel_prot[4]}>gopher://</option><option value=\"5\"{$sel_prot[5]}>mailto:</option><!-- option value=\"6\">wais</option --></select>
            if ($this->extra)
                eval('$prot = ' . $this->extra . ';');
            else
                $prot = array(1);
            $tot_prot = count($prot);
            if (!$tot_prot) {
                $prot = array(1);
                $tot_prot = 1;
            }
            if ($tot_prot == 1)
                $prot_str = $prot_arr[current($prot)];
            else {
                $prot_str = "<select name=\"prot[{$this->id}]\">";
                foreach ($prot AS $prot_k => $prot_v)
                    $prot_str .= "<option value=\"{$prot_k}\"{$sel_prot[$prot_k]}>{$prot_arr[$prot_k]}</option>";
                $prot_str .= "</select>";
            }

            $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
            return $this->label(0, $this->campo_id_pref . $this->indice) . "<td>{$prot_str} " . htmlspecialchars($this->valores[0]['text']) . " <a href=\"#\" target=\"_blank\" onclick=\"return abrirEnlace(this)\"><img src=\"/img/externo\" alt=\"Abrir enlace\" class=\"enlace_img\" /></a></td>"; // tabindex=\"2\"
        } else {
            $retorno = $this->label(1) . "<td>";
            $i = 1;
            $estilo = " style=\"display:block;\"";
            $seleccionado = " seleccionado";
            //$leng = each($this->lenguajes);
            // {
            reset($this->lenguajes);
            list($leng_id, $leng) = each($this->lenguajes);

            return $retorno . "<p>" . nl2br(htmlspecialchars($this->valores[$leng_id]['text'])) . "</p></td>";
        }

        //return $this->label(2)."<td><textarea name=\"_{$this->campo_nombre_pref}{$this->v}\" id=\"{$this->campo_nombre_pref}{$this->indice}\" rows=\"20\" cols=\"55\">{$this->valor}</textarea></td>";
    }

    private function camponum() {
        global $mysqli;
        $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
        if (empty($this->subtipo)) {
            if (!$cons_moneda = $mysqli->query("SELECT simbolo_izq, simbolo_der, decimales FROM monedas WHERE id = '{$this->extra}' LIMIT 1"))
                echo __LINE__ . " - " . $mysqli->error;
            if ($fila_moneda = $cons_moneda->fetch_assoc()) {
                $simbolo_izq = $fila_moneda['simbolo_izq'] ? $fila_moneda['simbolo_izq'] . " " : "";
                $simbolo_der = $fila_moneda['simbolo_der'] ? " " . $fila_moneda['simbolo_der'] : "";
                $decimales = $fila_moneda['decimales'];
                $cons_moneda->close();
            }
            else
                $decimales = 2;
            $valor = $this->valores[0] ? number_format($this->valores[0]['num'], $decimales, '.', '') : false;
        }
        else
            $valor = $this->valores[0]['num'];
        return $this->label(2) . "<td>{$simbolo_izq}{$valor}{$simbolo_der}</td>"; // tabindex=\"2\"
    }

}

?>