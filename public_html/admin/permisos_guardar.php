<?php

$seccion = "permisos";
$seccion_id = 2;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');


//header("Content-type: text/plain; charset=utf-8");

$id = $_POST['id'] ? $_POST['id'] : "false";
$modif = 0;
$mensaje = "false";





/*
Array
(
    [permisos] => 1
    [id] => 2
    [confirmar2] => Guardar
    [seccion] => Array
        (
            [85] => Array
                (
                    [2] => 0
                )

            [86] => Array
                (
                    [2] => 0
                )

            [10] => Array
                (
                    [2] => 0
                )

            [11] => Array
                (
                    [2] => 0
                )

            [12] => Array
                (
                    [2] => 0
                )

            [13] => Array
                (
                    [2] => 0
                )

            [14] => Array
                (
                    [2] => 0
                )

            [15] => Array
                (
                    [2] => 0
                )

            [16] => Array
                (
                    [2] => 0
                )

            [17] => Array
                (
                    [2] => 0
                )

            [18] => Array
                (
                    [2] => 0
                )

            [19] => Array
                (
                    [2] => 0
                    [3] => 4
                )
*/




/*
if($_POST["ia"] == "modificar" && $_POST["confirmar"])
 {
  if(empty($_POST['id']))
   {
	if(!empty($_POST['nombre'])) $modificar[] = "`nombre` = '".$_POST['nombre']."'";
	if(!empty($_POST['apellido'])) $modificar[] = "`apellido` = '".$_POST['apellido']."'";
	if(!empty($_POST['admin_mail'])) $modificar[] = "`admin_mail` = '".$_POST['admin_mail']."'";
	if(!empty($_POST['adclave']) || !empty($_POST['adclave2']))
	 {
	  if((empty($_POST['adclave']) xor empty($_POST['adclave2'])) || ($_POST['adclave'] != $_POST['adclave2']))
	   { $mensaje = "Para cambiar la contrase&ntilde;a debe ingresarla dos veces."; }
	  else
	   { $modificar[] = "`clave` = PASSWORD('".$_POST['adclave']."')"; }
	 }
	if(is_array($modificar))
	 {
	  $modificarstr = implode(", ", $modificar);
	  if(!$mysqli->query("UPDATE `admin` SET $modificarstr WHERE `admin_id` = '".$_POST['id']."' AND `usuario` = '".$_POST['usuario']."'")) echo __LINE__." - mySQL: ".  $mysqli->error."<br />\n";
	  $modif = $mysqli->affected_rows;
	 }
   }
 }
else
*/
if($_POST['permisos'] == "1" && $_POST['confirmar2'] && $_POST['id'])
 {
  $mysqli = BaseDatos::Conectar();
  $admin_permisos = array();
  $permisos = $mysqli->query("SELECT item_id, area_id, permiso_id FROM `usuarios_permisos` ap WHERE usuario_id = {$_POST['id']}");
  if($fila_perm = $permisos->fetch_row())
   {
	do
	 {
	  $admin_permisos[$fila_perm[0]][$fila_perm[1]] = $fila_perm[2];
	 }while($fila_perm = $permisos->fetch_row());
	$permisos->close();
   }


// seccion_id = indice
// areas = valor
$modif = 0;
$areas_identif = array(2 => 'admin_seccion', 'admin_seccion_c');
  foreach($_POST['seccion'] as $seccion_id => $areas)
   {

   	foreach($areas as $area_id => $valor)
	 {
	  if(!$_SESSION['permisos'][$areas_identif[$area_id]][$seccion_id] || $admin_permisos[$seccion_id][$area_id] > $_SESSION['permisos'][$areas_identif[$area_id]][$seccion_id] || $admin_permisos[$seccion_id][$area_id] == $valor)
		continue;
	  if($valor == 0 && $admin_permisos[$seccion_id][$area_id])
		$mysqli->query("DELETE FROM `usuarios_permisos` WHERE `usuario_id` = {$_POST['id']} AND `area_id` = ${area_id} AND `item_id` = ${seccion_id}");
	  elseif($admin_permisos[$seccion_id][$area_id])
		$mysqli->query("UPDATE `usuarios_permisos` SET `permiso_id` = ${valor} WHERE `usuario_id` = {$_POST['id']} AND `area_id` = ${area_id} AND `item_id` = ${seccion_id}");
	  elseif($valor > 0)
		$mysqli->query("INSERT INTO `usuarios_permisos` (`usuario_id`, `area_id`, `item_id`, `permiso_id`) VALUES ({$_POST['id']}, ${area_id}, ${seccion_id}, ${valor})");//) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
	  else
	    continue;
	  $modif++;
	 }
   }

/*
  foreach($_POST['seccion'] as $seccion_id => $areas)
   {
    if(is_array($admin_permisos[$indice]) && $_SESSION['permisos'][$admin_permisos[$indice][1]] <= $admin_permisos[$indice][0]) continue;
    if($valor == "0" && is_array($admin_permisos[$indice]))
     {
      $mysqli->query("DELETE FROM `admin_permisos` WHERE `admin_id` = '".$_POST['id']."' AND `seccion_id` = '$indice' LIMIT 1");
	  if($mysqli->affected_rows) $modif++;
     }
	if($valor >= 1)
	 {
	  if(is_array($admin_permisos[$indice]))
	   {
		if(!$mysqli->query("UPDATE `admin_permisos` SET `permiso` = '$valor' WHERE `admin_id` = '".$_POST['id']."' AND `seccion_id` = '$indice' LIMIT 1")) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
		else { if($mysqli->affected_rows) $modif++; }
	   }
	  else
	   {
	    if(!$mysqli->query("INSERT INTO `usuarios_permisos` (`usuario_id`, `item_id`, `permiso_id`) VALUES ('".$_POST['id']."', '$indice', '$valor')")) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
	    else { if($mysqli->affected_rows) $modif++; }
	   }
	 }
   }
*/
 }

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<respuesta><modificados>${modif}</modificados></respuesta>";
exit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $seccion; ?></title>
</head>
<?php

//echo "<body onload=\"parent.cambiosGuardados(".$id.", ".$modif.");\">";

echo "<body>";

echo "<pre>";
print_r($_POST);
echo "</pre>";


?>

</body>
</html>