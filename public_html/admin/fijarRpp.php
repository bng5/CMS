<?php

include('inc/iniciar.php');
if(is_numeric($_POST['seccion']) && is_numeric($_POST['rpp'])) {
	file_put_contents(RUTA_CARPETA.'bng5/datos/rpp'.$_POST['seccion'].'.php', '<?php
return '.$_POST['rpp'].';');
}
else {
	header("HTTP/1.1 400 Bad Request", NULL, 400);
}
echo $_SERVER['REQUEST_METHOD']."\n";
print_r($_POST);




?>