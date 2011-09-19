<?php

$seccion_id = 6;
$seccion = "monedas";

require_once('inc/iniciar.php');
require_once('inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  $mysqli = BaseDatos::Conectar();
  if(empty($_POST['id']))
   {
    $mysqli->query("INSERT INTO `monedas` (`codigo`, `simbolo_izq`, `simbolo_der`, `decimales`, `sep_decimales`, `sep_miles`) VALUES (UCASE('{$_POST['codigo']}'), '{$_POST['simbolo_izq']}', '{$_POST['simbolo_der']}', '{$_POST['decimales']}', '{$_POST['sep_decimales']}', '{$_POST['sep_miles']}')");
    if($id = $mysqli->insert_id)
     {
      foreach($_POST['nombre'] as $indice => $valor) $mysqli->query("INSERT INTO `monedas_nombres` (`id`, `leng_id`, `nombre`) VALUES ('${id}', '${indice}', '${valor}')");
      $modif++;
     }
   }
  else
   {
    $id = $_POST['id'];
    if(!$mysqli->query("UPDATE `monedas` SET `codigo` = UCASE('{$_POST['codigo']}'), `simbolo_izq` = '{$_POST['simbolo_izq']}', `simbolo_der` = '{$_POST['simbolo_der']}', `decimales` = '{$_POST['decimales']}', `sep_decimales` = '{$_POST['sep_decimales']}', `sep_miles` = '{$_POST['sep_miles']}' WHERE `id` = '{$_POST['id']}'")) echo "<br />\n".__LINE__." - ".$mysqli->error;
    $modif += $mysqli->affected_rows;
	foreach($_POST['nombre'] as $indice => $valor)
     {
      $mysqli->query("INSERT INTO `monedas_nombres` (`id`, `leng_id`, `nombre`) VALUES ('${id}', '${indice}', '${valor}')");
      if($mysqli->errno) $mysqli->query("UPDATE `monedas_nombres` SET `nombre` = '${valor}' WHERE `id` = '${id}' AND `leng_id` = '${indice}'");
      $modif++;
     }
   }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Idiomas</title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";
/*
echo "<body>";
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

?>
</body>
</html>