<?php

class Item_publicar extends Publicar {

    function __construct($seccion) {
        global $seccion_id;
        if (!$seccion->id)
            $seccion->id = $seccion_id;
        $this->seccion = $seccion;
        $this->seccion_id = $seccion->id;
        $this->modificadas = 0;
        $this->leng_poromision = false;
        $this->lengs = array();

        $this->atributos = array();
        //$this->subatributos = array();
        $this->listado = array();
        $this->strc_sqlite = array();
        $this->enlaces_protocolos = array(1 => "http://", "https://", "ftp://", "gopher://", "mailto:");

        $mysqli = BaseDatos::Conectar();
        $lenguajes = $mysqli->query("SELECT id, codigo FROM `lenguajes` WHERE `estado` = 1 ORDER BY `leng_poromision` DESC");
        if ($fila_leng = $lenguajes->fetch_row()) {
            do {
                $this->lengs[$fila_leng[0]]->id = $fila_leng[0];
                $this->lengs[$fila_leng[0]]->codigo = $fila_leng[1];
                if ($this->leng_poromision == false)
                    $this->leng_poromision = $fila_leng[0];
            }while ($fila_leng = $lenguajes->fetch_row());
            $lenguajes->close();
        }

        $tipos = array(
            1 => 'string',
            2 => 'img',
            3 => 'arch',
            5 => 'text',
            7 => 'date',
            10 => 'int',
            11 => 'int',
        );

        if (!$cons_attrs = $mysqli->query("SELECT ia.id, ia.identificador, ia.unico, ia.tipo_id, ia.extra, isaa.en_listado, isaa.salida, isaa.superior, ia.tipo_id FROM items_atributos ia, items_secciones_a_atributos isaa WHERE ia.id = isaa.atributo_id AND isaa.seccion_id = " . $this->seccion_id . " ORDER BY isaa.orden, ia.id"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_attrs = $cons_attrs->fetch_assoc()) {
            do {
                $atributo_id = array_shift($fila_attrs);
                $this->atributos[$atributo_id] = $fila_attrs;
                //$this->atributos[$atributo_id]['extra'] = unserialize($fila_attrs['extra']);
                $this->atributos[$atributo_id]['extra'] = unserialize($fila_attrs['extra']);


                if ($fila_attrs['en_listado'] == 1 && !$this->listado[$atributo_id]) {
                    $this->listado[$atributo_id] = $atributo_id;
                    $s_pref = $fila_attrs['tipo'];
                    $s_tipo = "VARCHAR(300)";

                    $s_pref = $tipos[$fila_attrs['tipo_id']];

                    /*
                      if($fila_attrs['tipo_id'] == 2 || $fila_attrs['tipo_id'] == 3) {
                      if($fila_attrs['salida'] == 1) {
                      $this->strc_sqlite[] = "`".$s_pref."__".$fila_attrs['identificador']."` integer";
                      $s_pref = "string";
                      }
                      else
                      {
                      $s_tipo = "integer";
                      }

                      }
                     */
                    /* elseif($fila_attrs['tipo'] == "num")
                      {
                      if($fila_attrs['salida'] == 1)
                      {
                      $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` DECIMAL(15,2)";
                      $s_pref = "string";
                      }
                      else
                      {
                      $s_tipo = "DECIMAL(15,2)";
                      }
                      }
                     */
                    //else
                    if ($fila_attrs['tipo_id'] == 5) {
                        $s_tipo = "TEXT";
                        //if($fila_attrs['subtipo'] == 1)
                        //  $s_pref = "link";
                    } elseif ($fila_attrs['tipo_id'] == 7) {
                        $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` DATETIME";
                        $s_pref = "string";
                    }
                    $this->strc_sqlite[] = "`{$s_pref}__{$fila_attrs['identificador']}` {$s_tipo}";
                }
            } while ($fila_attrs = $cons_attrs->fetch_assoc());
            $cons_attrs->close();
        }

        if (!$cons_etseccion = $mysqli->query("SELECT leng_id, titulo FROM secciones_nombres WHERE id = {$this->seccion_id}"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_etseccion = $cons_etseccion->fetch_row()) {
            do {
                $etsecccion[$fila_etseccion[0]] = $fila_etseccion[1];
            } while ($fila_etseccion = $cons_etseccion->fetch_row());
            $cons_etseccion->close();
            foreach ($this->lengs AS $leng_k => $sinUso) {
                $et = $etsecccion[$leng_k] ? $etsecccion[$leng_k] : $etsecccion[$this->leng_poromision];
                //$this->sqlite->queryExec("insert into seccion VALUES ('{$leng_v}', '{$et}')");
            }
        }

        $los_campos = count($this->strc_sqlite) ? implode(" DEFAULT NULL,\n ", $this->strc_sqlite) . " DEFAULT NULL,\n " : "";
        $mysqli->query("CREATE TABLE `pub__{$this->seccion_id}` (
 `id` INT UNSIGNED NOT NULL,
 `leng_cod` VARCHAR(5)  CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
 `creado` DATETIME  NOT NULL,
 `modificado` DATETIME  NOT NULL,
 `orden` TINYINT UNSIGNED DEFAULT NULL,
 `superior` TINYINT UNSIGNED NOT NULL DEFAULT 0,
 {$los_campos}PRIMARY KEY(`id`, `leng_cod`)
)
ENGINE = MYISAM
CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    function Item($id) {
        $mysqli = BaseDatos::Conectar();
        if (!$cons_item = $mysqli->query("SELECT f_creado, f_modificado, orden, superior_id FROM items WHERE id = {$id} LIMIT 1"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_item = $cons_item->fetch_row()) {
            $a_sqlite_base = array('creado' => "'{$fila_item[0]}'", 'modificado' => "'{$fila_item[1]}'", 'orden' => ($fila_item[2] ? $fila_item[2] : 'NULL'), 'superior' => (int) $fila_item[3]);
            //'seccion' => $this->seccion,
            $item = array('seccion_id' => $this->seccion_id, 'creado' => $fila_item[0], 'modificado' => $fila_item[1], 'orden' => (int) $fila_item[2], 'superior' => (int) $fila_item[3], 'valores' => array());
            $cons_item->close();
        }
        else
            exit("No se encontró el item");
        $valores = array();
        if (!$cons_valores = $mysqli->query("SELECT atributo_id, iv.leng_id, iv.`string`, iv.`date`, iv.`text`, iv.`int`, iv.`num` FROM items_valores iv WHERE iv.item_id = " . $id . " ORDER BY iv.leng_id"))
            echo __LINE__ . " - " . $mysqli->error;
        if ($fila_valores = $cons_valores->fetch_assoc()) {
            do {
                $atributo_id = array_shift($fila_valores);
                $leng_id = array_shift($fila_valores);
                //$leng_id = $fila_valores['leng_id'];
                if ($this->atributos[$atributo_id]['unico'] == 0)
                    $valores[$atributo_id][] = $fila_valores;
                elseif ($this->atributos[$atributo_id]['unico'] == 1)
                    $valores[$atributo_id] = $fila_valores;
                else
                    $valores[$atributo_id][$leng_id] = $fila_valores;
            }while ($fila_valores = $cons_valores->fetch_assoc());
            $cons_valores->close();
        }

        $bsq_texto = '';
        $tipos = array('string' => 'texto', 'int' => 'texto', 'text' => 'areadetexto', 'date' => 'fecha');

        global $bng5_texto;
        foreach ($this->lengs AS $leng_id => $leng) {
            @include_once(RUTA_CARPETA . 'leng/fechas.' . $leng->codigo);

            $this->niveles = array(0);
            $this->nodos = array();
            $this->nodos[0] = ''; //$root->appendChild($this->nodos[0]);
            $a_sqlite = $a_sqlite_base;

            $this->acondicionar($leng, $valores, $item, $a_sqlite);

            file_put_contents(RUTA_CARPETA . "bng5/datos/item/" . $id . "." . $leng->codigo . ".php", "<?php\nreturn " . var_export($item, true) . ";\n?>");
            @$mysqli->query("DELETE FROM `pub__{$this->seccion_id}` WHERE id = {$id} AND leng_cod = '{$leng->codigo}'");
            @$mysqli->query("DELETE FROM `buscador` WHERE item_id = {$id}");
            $mysqli->query("INSERT INTO `buscador` (item_id, texto) VALUES ({$id}, '{$bsq_texto}')");

            $mysqli->query("INSERT INTO `pub__{$this->seccion_id}` (`id`, `leng_cod`, `" . implode("`, `", array_keys($a_sqlite)) . "`) VALUES ({$id}, '" . $leng->codigo . "', " . implode(",", $a_sqlite) . ")");
        }
        $this->modificadas++;

    }

    /*
      private function _inutil() {
      if($attr_v['tipo'] == "int") {
      // Dato externo
      if($attr_v['subtipo'] == 1) {
      if($this->listado[$attr_k])
      $a_sqlite['int__'.$attr_v['identificador']] = $valores[$attr_k]['int'] ? $valores[$attr_k]['int'] : 'NULL';
      if($attr_v['salida'] == 0)
      continue;
      $this->nodos[$attr_k] = '';//$doc->createElement('dato');
      $e_valor = $valores[$attr_k]['int'];
      if(!$cons_valores = $mysqli->query($attr_v['extra'].$leng_id." AND i.id = '{$valores[$attr_k]['int']}' LIMIT 1"))
      echo __LINE__." - ".$mysqli->error;
      if($fila_valores = $cons_valores->fetch_row()) {
      if(empty($valor_leng[$e_valor]))
      $valor_leng[$e_valor] = $fila_valores[1];
      //$this->nodos[$attr_k]->appendChild($doc->createTextNode($fila_valores[1]));
      $item['valores'][$attr_k]['id'] = $valores[$attr_k]['int'];
      $item['valores'][$attr_k]['desc'] = $fila_valores[1];
      $bsq_texto .= $fila_valores[1]." ";
      $cons_valores->close();
      }
      $string_valor = $fila_valores[1] ? $fila_valores[1] : $valor_leng[$e_valor];
      if($this->listado[$attr_k])
      $a_sqlite['string__'.$attr_v['identificador']] = $string_valor ? "'".$mysqli->real_escape_string($string_valor)."'" : 'NULL';
      //if(empty($valores[$attr_k]['int']))
      continue;
      }

      elseif($attr_v['subtipo'] == 3) {

      }
      // etiquetas
      elseif($attr_v['subtipo'] == 10) {
      if($this->listado[$attr_k])
      $a_sqlite['int__'.$attr_v['identificador']] = 0;
      if($attr_v['salida'] == 0)
      continue;
      $this->nodos[$attr_k] = '';//$doc->createElement('dato');
      $ets_arr = array();
      $ets = $mysqli->query("SELECT co.id, co.texto FROM campos_opciones_sel c LEFT JOIN campos_opciones_textos co ON c.opcion_id = co.id WHERE c.item_id = {$id} AND c.campo_id = {$attr_k} ORDER BY co.texto");
      if($et_fila = $ets->fetch_row()) {
      do {
      $ets_arr[$et_fila[0]] = $et_fila[1];
      }while($et_fila = $ets->fetch_row());
      }
      $item['valores'][$attr_k] = $ets_arr;
      $bsq_texto .= implode(" ", $ets_arr)." ";
      $a_sqlite['string__'.$attr_v['identificador']] = "'".addslashes(serialize($ets_arr))."'";
      continue;
      }
      }
      elseif($attr_v['tipo'] == "text") {
      // enlace externo
      if($attr_v['subtipo'] == 1) {
      // Ver Publicacion_Atributo8
      continue;
      }
      }
      }
     */
}
