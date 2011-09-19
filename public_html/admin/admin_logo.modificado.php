<?php

include('inc/iniciar.php');

if ($fecha = @filemtime(RUTA_CARPETA . 'public_html/img/admin_logo.png')) {
    $archivo = RUTA_CARPETA . 'public_html/img/admin_logo.png';
    $hash = md5_file($archivo);
    $tam = filesize($archivo);
} else {
    $fecha = 1252292400;
    $archivo = './img/logo.png';
    $hash = '68129f71f5da077e798ed899d355feda';
    $tam = 12806;
}

header('Content-type: image/png');
if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $fecha || trim($_SERVER['HTTP_IF_NONE_MATCH']) == $hash) {
    header('Etag: ' . $hash, true, 304);
    exit;
}
header('Last-Modified: ' . date("r", $fecha));
header('Etag: ' . $hash);
header('Content-Length: ' . $tam);
readfile($archivo);

?>