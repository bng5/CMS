<?php

class XML_Arbol {

    private $handler;

    function __construct(& $root) {
        //$this->items = array();
        $this->items_ref = array();
        //$this->items['items'] = array();
        $this->items_ref[0] = & $root;
        $this->huerfanos = array();
        //$this->superiores = array();
    }

    function RegistrarHandler($obj) {
        $this->handler = $obj;
    }

    function agregar($item, $id, $superior) {
        $nodo = $this->handler->nodo($item); //, $id, $this->items_ref[$superior]
        if (isset($this->items_ref[$superior])) {
            $this->items_ref[$id] = & $this->items_ref[$superior]->appendChild($nodo); //$this->handler->agregar($item, $id, $this->items_ref[$superior]);
        } else {
            $this->huerfanos[$superior][$id] = $nodo;
            $this->items_ref[$id] = & $this->huerfanos[$superior][$id]; //['items'];
        }
        if (isset($this->huerfanos[$id])) {
            foreach ($this->huerfanos[$id] AS $k => $huerfano)
                $this->items_ref[$k] = $this->items_ref[$id]->appendChild($huerfano);
            unset($this->huerfanos[$id]);
        }
    }

}

/*
// admin secciones_const.php

include_once('inc/iniciar.php');

function subsecciones($nivel = 0, $nodoseccion = false, &$info) {
    global $mysqli, $doc, $leng_id, $leng_cod, $leng_poromision;
    if ($nodoseccion == false) {
        global $root;
    } else {
        $root = $nodoseccion;
    }
    $consultasrt = "SELECT se.id, ads.identificador, se.tipo, se.icono, ads.info, ads.items, ads.categorias, se.permiso_min FROM `secciones` se JOIN `admin_secciones` ads ON se.id = ads.id WHERE superior_id = '{$nivel}' AND estado = '1' ORDER BY se.orden, se.id";
    if (!$consulta = $mysqli->query($consultasrt))
        die(__LINE__); //die(xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
    if ($fila = $consulta->fetch_row()) {
        do {
            if ($indice == $fila[0])
                continue;
            $indice = $fila[0];

            $info[$nivel][$fila[0]] = array('identificador' => $fila[1], 'info' => $fila[4], 'items' => $fila[5], 'categorias' => $fila[6], 'permiso_min' => $fila[7]);

            if ($fila[1] == 1) {
                
            }

            subsecciones($fila[0], $nodoseccion, $info);
        } while ($fila = $consulta->fetch_row());
        $consulta->close();
    } else {
        return false;
    }
}

if ($lengs) {
    $bsq_lengs = "AND ";
    if (count($lengs) > 1)
        $bsq_lengs .= "(";
    $bsq_lengs .= "leng_id = '";
    $bsq_lengs .= implode("' OR leng_id = '", $lengs);
    $bsq_lengs .= "'";
    if (count($lengs) > 1)
        $bsq_lengs .= ")";
}
unset($lengs);

$mysqli = BaseDatos::Conectar();
$lenguajes = $mysqli->query("SELECT id, codigo, leng_poromision FROM `lenguajes` WHERE `estado` > 0 AND estado < 5 {$bsq_lengs}");
if ($fila_leng = $lenguajes->fetch_row()) {
    do {
        $lengs[$fila_leng[0]] = $fila_leng[1];
        if ($fila_leng[2] == 1)
            $leng_poromision = $fila_leng[0];
    }while ($fila_leng = $lenguajes->fetch_row());
    $lenguajes->close();
}

if (!$leng_poromision) {
    $leng_porom = $mysqli->query("SELECT id FROM lenguajes WHERE leng_poromision = '1' LIMIT 1");
    if ($fila = $leng_porom->fetch_row())
        $leng_poromision = $fila[0];
    $leng_porom->close();
}

$secciones = array();
$secciones_ref = array();
$secciones['secciones'] = array();
$secciones_ref[0] = & $secciones['secciones'];
$huerfanos = array();
$superiores = array();

//$consultasrt = "SELECT se.id AS db_id, superior_id, ads.identificador AS id, se.tipo, se.icono, ads.info, ads.items, ads.categorias, se.permiso_min FROM `secciones` se JOIN `admin_secciones` ads ON se.id = ads.id WHERE estado = '1' ORDER BY superior_id, se.orden, se.id";
$consultasrt = "SELECT id, superior_id, identificador, tipo, info, items, categorias, menu FROM `secciones` WHERE salida_sitio = '1' ORDER BY superior_id, orden, id";
if (!$consulta = $mysqli->query($consultasrt))
    die($consultasrt); //xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
if ($fila = $consulta->fetch_assoc()) {
    do {
        $sec_id = (int) array_shift($fila);
        $sec_sup = (int) array_shift($fila);
        $superiores[$sec_id] = $sec_sup;

        $fila['nombre'] = array();
        $fila['secciones'] = array();

        $fila['info'] = (bool) $fila['info'];
        $fila['items'] = (bool) $fila['items'];
        //  $fila['categorias'] = (bool) $fila['categorias'];
        $fila['permiso_min'] = (int) $fila['permiso_min'];

        $fila['categorias'] = $fila['categorias'] ? '/xml/categorias/' . $fila['id'] : false;

        if (is_array($secciones_ref[$sec_sup])) {
            $secciones_ref[$sec_sup][$sec_id] = $fila;
            $secciones_ref[$sec_id] = & $secciones_ref[$sec_sup][$sec_id]['secciones'];
        } else {
            $huerfanos[$sec_sup][$sec_id] = $fila;
            $secciones_ref[$sec_id] = & $huerfanos[$sec_sup][$sec_id]['secciones'];
        }
        if ($huerfanos[$sec_id]) {
            $secciones_ref[$sec_id] = $huerfanos[$sec_id];
            unset($huerfanos[$sec_id]);
        }
    } while ($fila = $consulta->fetch_assoc());
    $consulta->close();
}


$nombres = array();
$cons_nombres = $mysqli->query("SELECT sn.id, l.codigo, sn.titulo FROM `secciones_nombres` sn JOIN lenguajes l ON sn.leng_id = l.id"); // {$bsq_lengs}");
if ($fila_nombres = $cons_nombres->fetch_row()) {
    do {
        $secciones_ref[$superiores[$fila_nombres[0]]][$fila_nombres[0]]['nombre'][$fila_nombres[1]] = $fila_nombres[2];
    } while ($fila_nombres = $cons_nombres->fetch_row());
}


eval("\$buffer2 = '" . $xhtml_template . "';");

file_put_contents(RUTA_CARPETA . 'cms2/datos/secciones.php', "<?php\nreturn " . var_export($secciones['secciones'], true) . ";\n?>");
*/
