<?php

class Categoria_publicar extends Publicar {

    function __construct($seccion) {
        global $mysqli, $seccion_id;
        //$this->mysqli = $mysqli;
        $this->seccion = $seccion;
        $this->seccion_id = $seccion_id;
        $this->modificadas = 0;
        $this->leng_poromision = false;
        $this->lengs = array();
        $this->etiquetas = array();

        $this->atributos = array();
        $this->listado = array();
        $this->strc_sqlite = array();

        $mysqli = BaseDatos::Conectar();
        $lenguajes = $mysqli->query("SELECT id, codigo FROM `lenguajes` WHERE `estado` > 0 AND estado < 5 ORDER BY `leng_poromision` DESC");
        if ($fila_leng = $lenguajes->fetch_row()) {
            do {
                $this->lengs[$fila_leng[0]] = $fila_leng[1];
                if ($this->leng_poromision == false)
                    $this->leng_poromision = $fila_leng[0];
            }while ($fila_leng = $lenguajes->fetch_row());
            $lenguajes->close();
        }

        if (!$cons_attrs = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, ia.tipo_id, ia.extra, ia.unico FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, categorias_secciones_a_atributos isaa WHERE ia.id = isaa.atributo_id AND isaa.seccion_id = {$this->seccion_id} ORDER BY isaa.orden"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_attrs = $cons_attrs->fetch_assoc()) {
            do {
                $atributo_id = array_shift($fila_attrs);
                $leng_id = array_shift($fila_attrs);
                $etiqueta = array_shift($fila_attrs);
                if (!$this->atributos[$atributo_id]) {
                    $this->atributos[$atributo_id] = $fila_attrs;
                    $this->atributos[$atributo_id]['extra'] = unserialize($fila_attrs['extra']);
                    $this->atributos[$atributo_id]['en_listado'] = 1;
                }
                $this->atributos[$atributo_id]['etiquetas'][$leng_id] = $etiqueta;
                if (!$this->listado[$atributo_id]) {
                    $this->listado[$atributo_id] = $atributo_id;
                    $s_pref = Atributos::$almacenamiento[$atributos[$ins_atributo_id]['tipo_id']]; //$fila_attrs['tipo'];
                    $s_tipo = "VARCHAR(200)";

                    /*if ($fila_attrs['tipo'] == "date")
                        $s_tipo = ($fila_attrs['subtipo'] == 1) ? "date" : "datetime";


                    else
                    */if($fila_attrs['tipo_id'] == Atributos::IMG) {
                        $s_pref = "img";
                    }
                    elseif ($fila_attrs['tipo'] == "int") {
                        if ($fila_attrs['subtipo'] == 2)
                            $s_pref = "img";
                        elseif ($fila_attrs['subtipo'] == 3)
                            $s_pref = "arch";
                        else {
                            $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` integer DEFAULT NULL,";
                            $s_pref = "string";
                        }
                    } elseif ($fila_attrs['tipo_id'] == 5)
                        $s_tipo = "text";
                    $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` {$s_tipo} DEFAULT NULL,";
                }
            }while ($fila_attrs = $cons_attrs->fetch_assoc());
            $cons_attrs->close();
        }

        if (!$cons_etseccion = $mysqli->query("SELECT leng_id, titulo FROM secciones_nombres WHERE id = ${seccion_id}"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_etseccion = $cons_etseccion->fetch_row()) {
            do {
                $etsecccion[$fila_etseccion[0]] = $fila_etseccion[1];
            } while ($fila_etseccion = $cons_etseccion->fetch_row());
            $cons_etseccion->close();
            foreach ($this->lengs AS $leng_k => $leng_v) {
                $et = $etsecccion[$leng_k] ? $etsecccion[$leng_k] : $etsecccion[$this->leng_poromision];
                //$this->sqlite->queryExec("insert into seccion VALUES ('${leng_v}', '${et}')");
            }
        }

        $mysqli->query("CREATE TABLE IF NOT EXISTS `pubcats__{$this->seccion_id}` (
 `id` INT UNSIGNED NOT NULL,
 `leng_cod` VARCHAR(3)  CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
 `superior` INT UNSIGNED DEFAULT 0,
 `orden` TINYINT UNSIGNED DEFAULT NULL,
 `titulo` VARCHAR(30)  NOT NULL,
 " . implode("\n ", $this->strc_sqlite) . "
 PRIMARY KEY(`id`, `leng_cod`)
)
ENGINE = MYISAM
CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    function Item($id) {
        $mysqli = BaseDatos::Conectar();
        if (!$cons_item = $mysqli->query("SELECT superior, orden FROM items_categorias WHERE id = ${id} LIMIT 1"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_item = $cons_item->fetch_row()) {
            $a_sqlite_base = array('superior' => $fila_item[0], 'orden' => $fila_item[1]); //, $fila_item[2]);
            $cons_item->close();
        } else {
            echo "No se encontró el item";
            exit;
        }

        $nombres = array();
        if (!$cons_nombre = $mysqli->query("SELECT leng_id, nombre FROM items_categorias_nombres i WHERE id = ${id}"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_nombre = $cons_nombre->fetch_row()) {
            do {
                $nombres[$fila_nombre[0]] = $fila_nombre[1];
            } while ($fila_nombre = $cons_nombre->fetch_row());
            $cons_nombre->close();
        }

        $valores = array();
        if (!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM categorias_valores iv WHERE iv.categoria_id = ${id} ORDER BY iv.leng_id"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_valores = $cons_valores->fetch_assoc()) {
            do {
                $atributo_id = array_shift($fila_valores);
                $leng_id = array_shift($fila_valores);
                if ($leng_id)
                    $valores[$atributo_id][$leng_id] = $fila_valores;
                else
                    $valores[$atributo_id] = $fila_valores;
            }while ($fila_valores = $cons_valores->fetch_assoc());
            $cons_valores->close();
        }
        $valor_leng = array();
        $tipos = array('string' => 'texto', 'text' => 'areadetexto', 'date' => 'texto');
        @$mysqli->query("DELETE FROM `pubcats__{$this->seccion_id}` WHERE id = ${id}");
        foreach ($this->lengs AS $leng_id => $leng_cod) {
            $leng->id = $leng_id;
            $a_sqlite = $a_sqlite_base;
            $a_sqlite['titulo'] = $nombres[$leng_id] ? "'{$nombres[$leng_id]}'" : "'{$nombres[$this->leng_poromision]}'";
            $this->acondicionar($leng, $valores, $item, $a_sqlite);
            $mysqli->query("INSERT INTO `pubcats__{$this->seccion_id}` (`id`, `leng_cod`, `" . implode("`, `", array_keys($a_sqlite)) . "`) VALUES (${id}, '${leng_cod}', " . implode(",", $a_sqlite) . ")");
        }
    }

    function _inutil() {


        if ($attr_v['tipo'] == "int") {
            if ($attr_v['subtipo'] == 1) {
                if ($this->listado[$attr_k])
                    $a_sqlite['int__' . $attr_v['identificador']] = $valores[$attr_k]['int'] ? $valores[$attr_k]['int'] : 0;
                //if($attr_v['salida'] == 0) continue;
                $e_valor = $valores[$attr_k]['int'];
                if (!$cons_valores = $mysqli->query($attr_v['extra'] . $leng_id . " AND i.id = '{$valores[$attr_k]['int']}' LIMIT 1"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_valores = $cons_valores->fetch_row()) {
                    if (empty($valor_leng[$e_valor]))
                        $valor_leng[$e_valor] = $fila_valores[1];
                    $cons_valores->close();
                }
                $string_valor = $fila_valores[1] ? $fila_valores[1] : $valor_leng[$e_valor];
                $a_sqlite['string__' . $attr_v['identificador']] = "'{$string_valor}'"; // ? $string_valor : '';
                //if(empty($valores[$attr_k]['int'])) continue;
            }
            elseif ($attr_v['subtipo'] == 2) {
                if (!empty($valores[$attr_k]['int'])) {
                    $cons_img = $mysqli->query("SELECT io.formato, iaa.peso, io.archivo, iaa.ancho, iaa.alto, iaa.ancho_m, iaa.alto_m, iaa.peso_m FROM imagenes_orig io JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id AND iaa.atributo_id = ${attr_k} WHERE io.id = {$valores[$attr_k]['int']}");
                    $img = $cons_img->fetch_row();
                }
                if ($this->listado[$attr_k])
                    $a_sqlite[] = $img ? "'${attr_k}/" . urlencode($img[2]) . ",{$img[0]},{$img[1]},{$img[3]},{$img[4]},{$img[5]},{$img[6]},{$img[7]}'" : 'NULL';
            }
            elseif ($attr_v['subtipo'] == 3) {
                if (!empty($valores[$attr_k]['int'])) {
                    $cons_img = $mysqli->query("SELECT formato, peso, archivo FROM archivos WHERE id = {$valores[$attr_k]['int']}");
                    $img = $cons_img->fetch_row();
                }
                if ($this->listado[$attr_k])
                    $a_sqlite[] = $img ? "'" . urlencode($img[2]) . ",{$img[0]},{$img[1]}'" : "NULL";
            }
            elseif ($attr_v['subtipo'] == 5) {
                if (!$cons_vista = $mysqli->query("SELECT co.id, cot.texto FROM campos_opciones co JOIN campos_opciones_textos cot ON co.id = cot.id AND cot.leng_id = ${leng_id} WHERE co.id = {$valores[$attr_k]['int']} LIMIT 1"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_vista = $cons_vista->fetch_row()) {
                    $cons_vista->close();
                }
            } elseif ($attr_v['subtipo'] == 6) {
                $dato = $doc->createElement('alineacion');
                if (!empty($valores[$attr_k]['int'])) {

                }
            } elseif ($attr_v['subtipo'] == 7) {

                /*                 * ****************************************************** */
                // estaría bueno que esta consulta no se genere más de una vez
                $subatributos = array();
                if (!$atributos_tipos = $mysqli->query("SELECT ia.id, ian.leng_id, ian.atributo, ia.identificador, at.tipo, at.subtipo, ia.extra FROM items_atributos ia LEFT JOIN items_atributos_n ian ON ia.id = ian.id, subitems_supatributos_a_atributos isaa, atributos_tipos at WHERE at.id = ia.tipo_id AND ia.id = isaa.atributo_id AND isaa.sup_atributo_id = {$attr_k} ORDER BY isaa.orden"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_at = $atributos_tipos->fetch_assoc()) {
                    do {
                        $attr_id = array_shift($fila_at);
                        $subatributos[$attr_id] = array('sugerido' => $fila_at['sugerido'], 'unico' => $fila_at['unico'], 'tipo' => $fila_at['tipo'], 'subtipo' => $fila_at['subtipo'], 'nombre' => $fila_at['atributo'], 'identificador' => $fila_at['identificador'], 'extra' => $fila_at['extra'], 'poromision' => $fila_at[$fila_at['tipo']]);
                    } while ($fila_at = $atributos_tipos->fetch_assoc());
                    $atributos_tipos->close();
                }

                $subvalores = array();
                if (!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM subitems_valores iv WHERE iv.item_id = ${id} AND area_id = {$attr_k} ORDER BY iv.leng_id"))
                    echo __LINE__ . " - " . $mysqli->error;
                if ($fila_valores = $cons_valores->fetch_assoc()) {
                    do {
                        $atributo_id = array_shift($fila_valores);
                        $leng_id = array_shift($fila_valores);
                        if ($leng_id)
                            $subvalores[$atributo_id][$leng_id] = $fila_valores;
                        else
                            $subvalores[$atributo_id] = $fila_valores;
                    }while ($fila_valores = $cons_valores->fetch_assoc());
                    $cons_valores->close();
                }

                foreach ($subatributos AS $subattr_k => $subattr_v) {
                    if ($subattr_v['tipo'] == "int") {
                        if ($subattr_v['subtipo'] == 2) {
                            if (!empty($subvalores[$subattr_k]['int'])) {
                                $cons_img = $mysqli->query("SELECT formato, peso, archivo FROM imagenes WHERE id = {$subvalores[$subattr_k]['int']}");
                                $img = $cons_img->fetch_row();
                            }
                        }
                    } else {

                        if ($attr_v['tipo'] == "date")
                            $valor['date'] = formato_fecha($subvalores[$subattr_k][$subattr_v['tipo']], true, false);
                        //formato_fecha($v, true);
                        else {
                            if (isset($subvalores[$subattr_k][$subattr_v['tipo']]))
                                $valor = $subvalores[$subattr_k];
                            else
                                $valor = $subvalores[$subattr_k][$leng_id] ? $subvalores[$subattr_k][$leng_id] : $subvalores[$subattr_k][$this->leng_poromision];
                        }
                    }
                    //$etiqueta = $subattr_v['etiquetas'][$leng_id] ? $subattr_v['etiquetas'][$leng_id] : $subattr_v['etiquetas'][$this->leng_poromision];
                    $etiqueta = $subattr_v['nombre']; //[$leng_id] ? $subattr_v['nombre'][$leng_id] : $subattr_v['nombre'][$this->leng_poromision];
                }
                /*                 * ****************************************************** */
                /* if(!empty($valores[$attr_k]['int']))
                  {
                  $cons_img = $mysqli->query("SELECT id, codigo FROM subitems WHERE item_id = {$id} AND atributo_id = {$valores[$attr_k]['int']} ORDER BY codigo");
                  if($img = $cons_img->fetch_row())
                  {
                  do
                  {
                  $el = $doc->createElement('elemento');
                  $el->setAttribute("xml:id", $img[0]);
                  $el->appendChild($doc->createTextNode($img[1]));
                  $el = $dato->appendChild($el);
                  }while($img = $cons_img->fetch_row());
                  }
                  } */
            }
            elseif ($attr_v['subtipo'] == 8) {
                $radio_valor = $valores[$attr_k]['int'] ? $valores[$attr_k]['int'] : 0;
                if ($this->listado[$attr_k])
                    $a_sqlite[] = $radio_valor;
                if ($attr_v['salida'] == 0)
                    continue;
                else {
                    eval('$opc = ' . $attr_v['extra'] . ';');
                    $radio_valor_txt = $opc[$radio_valor];
                    if ($this->listado[$attr_k])
                        $a_sqlite[] = "'{$radio_valor_txt}'";
                }
            }
        }
        else {
            if ($attr_v['tipo'] == "date")
                $valor['date'] = formato_fecha($valores[$attr_k][$attr_v['tipo']], true, false);
            //formato_fecha($v, true);
            else {
                if (isset($valores[$attr_k][$attr_v['tipo']]))
                    $valor = $valores[$attr_k];
                else
                    $valor = $valores[$attr_k][$leng_id] ? $valores[$attr_k][$leng_id] : $valores[$attr_k][$this->leng_poromision];
            }
            if ($this->listado[$attr_k])
                $a_sqlite[] = "'" . addslashes($valor[$attr_v['tipo']]) . "'";
        }
        $etiqueta = $attr_v['etiquetas'][$leng_id] ? $attr_v['etiquetas'][$leng_id] : $attr_v['etiquetas'][$this->leng_poromision];
    }

}
