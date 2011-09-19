<?php

require('../../inc/configuracion.php');
require('../../inc/ad_sesiones.php');

$id = "false";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  $mk_fecha = mktime(0, 0, 0, $_POST['mes'], $_POST['dia'], $_POST['anyo']);
  $fecha = date("Y-m-d", $mk_fecha);

  if(empty($_POST['id']))
   {
    if(!$mysqli->query("INSERT INTO `novedades` (`novedad_estado`, `novedad_fecha`) VALUES ('".$_POST['estado']."', '".$fecha."')")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    $id = $mysqli->insert_id;
    //$modif++;
   }
  else
   {
    $id = $_POST['id'];
    if(!$mysqli->query("UPDATE `novedades` SET `novedad_estado` = '".$_POST['estado']."', `novedad_fecha` = '".$fecha."' WHERE `novedad_id` = '".$_POST['id']."'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
   }
  for($i = 0; $i < count($_POST['leng']); $i++)
   {
    if(empty($_POST['leng'][$i])) continue;
    if(!$consulta = $mysqli->query("SELECT novedad_id FROM `novedades_textos` WHERE `novedad_id` = '".$id."' AND `leng_id` = '".$_POST['leng'][$i]."' AND `nov_version_us` = '".$_SESSION['usuario_id']."' AND `nov_version_ses` = '".session_id()."' LIMIT 1")) die ("\n".__LINE__." mySql: ".$mysqli->error);
    if($consulta->num_rows == 1)
     { if(!$mysqli->query("UPDATE `novedades_textos` SET `novedad_titulo` = '".$_POST['titulo'][$i]."', `novedad_subtitulo` = '".$_POST['subtitulo'][$i]."', `novedad_texto` = '".$_POST['texto'][$i]."' WHERE `novedad_id` = '".$id."' AND `leng_id` = '".$_POST['leng'][$i]."' AND `nov_version_us` = '".$_SESSION['usuario_id']."' AND `nov_version_ses` = '".session_id()."'")) die ("\n".__LINE__." mySql: ".$mysqli->error); }
    else
     {
      if(!$mysqli->query("UPDATE `novedades_textos` SET `nov_version_act` = NULL WHERE `novedad_id` = '".$id."' AND `leng_id` = '".$_POST['leng'][$i]."'")) die ("\n".__LINE__." mySql: ".$mysqli->error);
      if(!$mysqli->query("INSERT INTO `novedades_textos` (`novedad_id`, `leng_id`, `novedad_titulo`, `novedad_subtitulo`, `novedad_texto`, `nov_version_act`, `nov_version_us`, `nov_version_ses`) VALUES ('".$id."', '".$_POST['leng'][$i]."', '".$_POST['titulo'][$i]."', '".$_POST['subtitulo'][$i]."', '".$_POST['texto'][$i]."', '1', '".$_SESSION['usuario_id']."', '".session_id()."')")) die ("\n".__LINE__." mySql: ".$mysqli->error);
     }
    $modif += $mysqli->affected_rows;
   }
 }

echo "<?xml version=\"1.0\"?>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Novedades</title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";
//echo "<body>";

echo "<pre>";
print_r($_POST);
echo "</pre>";


?>

</body>
</html>