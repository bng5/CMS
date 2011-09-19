<?php

require('inc/iniciar.php');
require('inc/ad_sesiones.php');


header("Content-Type: text/plain; charset=utf-8");

/*
Array
(
    [id] =>
    [rev] =>
    [pos_actual] =>
    [superior] => 0
    [btGuardar] => Guardar
    [nombre] => Array
        (
            [1] => Nueva
            [2] => New
        )

    [identificador] => nueva
    [info] => 1
    [items] => 1
    [categorias] => 1
    [prof_categorias] => 3
    [pos] => 1
    [antesde] => 7
    [salida] => 2
    [enmenu] => on

)
*/

// NUEVO


include('./secciones_const.php');
exit;







$id = "false";
$superior = $_POST['superior'] ? $_POST['superior'] : "0";
$subniveles = $_POST['subniveles'] ? "'".$_POST['maxsubniveles']."'" : "NULL";
$modif = 0;
if($_POST["ia"] == "modificar")
 {
  if(empty($_POST['id']))
   {
    if(!$mysqli->query("INSERT INTO `secciones` (`seccion_superior`, `seccion_orden`, `seccion_estado`, `seccion_usartexto`, `seccion_subniveles`, `seccion_tipo`) VALUES ('".$_POST['superior']."', NULL, '".$_POST['estado']."', '".$_POST['usar_texto']."', $subniveles, '".$_POST['tipo']."')")) echo __LINE__." - mySql: ".$mysqli->error."<br />\n";
    $id = $mysqli->insert_id;
    if($id && $_POST['estado'] == "1")
	  $modif_estructura = true;
    $modif++;
   }
  else
   {
    $id = $_POST['id'];
    // , `seccion_orden` = ''
    if(isset($_POST['usar_texto']))
	  $cond_usartexto = ", `seccion_usartexto` = '".$_POST['usar_texto']."'";
    if(isset($_POST['subniveles']))
	  $cond_subniveles = ", `seccion_subniveles` = ${subniveles}";
	if(isset($_POST['tipo']))
	  $cond_tipo = ", `seccion_tipo` = '".$_POST['tipo']."'";
	if(isset($_POST['estado']))
	  $cond_estado = ", `seccion_estado` = '".$_POST['estado']."'";
    if(!$mysqli->query("UPDATE `secciones` SET `seccion_superior` = '$superior' ${cond_estado} ${cond_usartexto} ${cond_subniveles} ${cond_tipo} WHERE `seccion_id` = '".$_POST['id']."'")) echo __LINE__." - mySql: ".$mysqli->error."<br />\n";
	if($mysqli->affected_rows)
	 {
	  $modif_estructura = true;
	  $modif++;
	 }
   }

  for($i = 0; $i < count($_POST['leng']); $i++)
   {
    if(empty($_POST['leng'][$i]))
	  continue;
    if(!$consulta = $mysqli->query("SELECT seccion_id FROM `secciones_textos` WHERE `seccion_id` = '".$id."' AND `leng_id` = '".$_POST['leng'][$i]."'")) echo __LINE__." - mySql: ".$mysqli->error."<br />\n";
    if($consulta->num_rows == 1)
     { if(!$mysqli->query("UPDATE `secciones_textos` SET `seccion_titulo` = '".$_POST['titulo'][$i]."', `seccion_ttitulo` = '".$_POST['ttitulo'][$i]."', `seccion_texto` = '".$_POST['texto'][$i]."', `seccion_modificado` = now(), `seccion_modif_usuario` = '".$_SESSION['usuario_id']."' WHERE `seccion_id` = '".$id."' AND `leng_id` = '".$_POST['leng'][$i]."'")) echo __LINE__." - mySql: ".$mysqli->error."<br />"; }
    else
     { if(!$mysqli->query("INSERT INTO `secciones_textos` (`seccion_id`, `leng_id`, `seccion_titulo`, `seccion_titulo`, `seccion_texto`, `seccion_modificado`, `seccion_modif_usuario`) VALUES ('".$id."', '".$_POST['leng'][$i]."', '".$_POST['titulo'][$i]."', '".$_POST['ttitulo'][$i]."', '".$_POST['texto'][$i]."', now(), '".$_SESSION['usuario_id']."')")) echo __LINE__." - mySql: ".$mysqli->error."<br />"; }
    $modif += $mysqli->affected_rows;
   }
  if($modif_estructura)
    $lengs = false;
  else
    $lengs = $_POST['leng'];
  if($modif)
    include('./secciones_const.php');
 }

?>