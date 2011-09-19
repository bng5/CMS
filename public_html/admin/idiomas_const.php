<?php

$seccion_id = 1;
require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');
// header('Content-type: application/xml; charset=utf-8');
$mysqli = BaseDatos::Conectar();
$idiomas = array();
$idiomas_hab = array();
$consultasrt = "SELECT `codigo`, `dir`, `leng_poromision`, `nombre_nativo`, ln.nombre, l.estado, l.id FROM `lenguajes` l LEFT JOIN lenguajes_nombres ln ON l.id = ln.id AND ln.leng_id = 65 WHERE `estado` != 0 ORDER BY `codigo`";
if (!$consulta = $mysqli->query($consultasrt))
    die(xml_sqlerror($mysqli->errno, $consultasrt, $mysqli->error));
if ($fila = $consulta->fetch_row()) {
    do {
        $idiomas_hab[$fila[0]] = $fila[5];
        if ($fila[5] == 1) {
            $idiomas[$fila[0]] = array('cod' => $fila[0], 'etiqueta' => $fila[3], 'dir' => $fila[1], 'id' => (int) $fila[6]);
            //$idiomas[$fila[0]]->etiqueta = $fila[3];
            //$idiomas[$fila[0]]->dir = $fila[1];
            //$idiomas[$fila[0]]->id = (int) $fila[6];
        }
        else
            continue;
        if ($fila[2] == 1) {
            $poromision = $fila[0];
            $idiomas[$fila[0]]['poromision'] = true;
        }
    } while ($fila = $consulta->fetch_row());
}

/* file_put_contents(RUTA_CARPETA.'public_html/inc_xhtml/idiomas.php', "<?php\n\$idiomas = ".var_export($idiomas, true).";\n\$poromision = '${poromision}';\n?>"); */
file_put_contents(RUTA_CARPETA . 'bng5/datos/idiomas.php', "<?php\n\$poromision = '{$poromision}';\nreturn " . var_export($idiomas, true) . ";\n?>");
