<?php

//echo filetype('./imgsitio');
//symlink('../img', './imgsitio');

define('ERROR', '<span class="error">ERROR</span>');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
 <title>Instalación</title>
 <style type="text/css">
:root {
    font-family: sans-serif;
}
.error {
    background-color: #800000;
    color: white;
    padding: 0.1em 0.5em;
}
 </style>
</head>
<body>

<?php
include('inc/iniciar.php');
?>


    <ul>
        <li>img/ <?php echo is_writable(RUTA_CARPETA.'img/') ? 'Correcto' : ERROR; ?></li>
        <li>iacache/ <?php echo is_writable(RUTA_CARPETA.'iacache/') ? 'Correcto' : ERROR; ?></li>
        <li>bng5/datos/ <?php echo is_writable(RUTA_CARPETA.'bng5/datos/') ? 'Correcto' : ERROR; ?></li>
        <li>public_html/img/ 
            <ul>
                <li>public_html/img/0/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/0/') ? 'Correcto' : ERROR; ?></li>
                <li>public_html/img/1/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/1/') ? 'Correcto' : ERROR; ?></li>
                <li>public_html/img/2/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/2/') ? 'Correcto' : ERROR; ?></li>
                <li>public_html/img/3/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/3/') ? 'Correcto' : ERROR; ?></li>
                <li>public_html/img/4/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/4/') ? 'Correcto' : ERROR; ?></li>
                <li>public_html/img/5/ <?php echo is_writable(RUTA_CARPETA.'public_html/img/5/') ? 'Correcto' : ERROR; ?></li>
            </ul>
        </li>
    </ul>


</body>
</html>