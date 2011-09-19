<?php

$archivo = '../img/5/'.trim($_SERVER['PATH_INFO'], " /");
//$archivo_size = @getimagesize($archivo);
header('content-type: image/png');
//echo 'content-type: '.$archivo_size['mime'];
readfile($archivo);
exit;

?>