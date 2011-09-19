<?php

$seccion = "permisos";

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

header("Content-type: text/html; charset=utf-8");

//print_r($_POST);

/*
    [id] => 3
    [ia] => modificar
    [confirmar] => Guardar
    [usuario] => profesionales
    [nombre] => Profesionales
    [adclave] =>
    [adclave2] =>
    [email] => p_bangueses@eltorodepicasso.es
*/

$mysqli = BaseDatos::Conectar();
if($_POST['ia'] == "agregar" || $_POST['ia'] == "modificar")
 {
  if($_POST['ia'] == "modificar") $cond = "AND id != '{$_POST['id']}'";
  $resultado = $mysqli->query("SELECT `usuario`, `email` FROM `usuarios` WHERE (`usuario` = '{$_POST['usuario']}' OR `email` = '{$_POST['email']}') ${cond} LIMIT 1");
  $fila = $resultado->fetch_row();
  $resultado->close();
  if(empty($_POST['usuario']))
   { $mensaje_error[] = "Debe completar el campo 'Nombre de usuario'."; }
  else
   {
    if(strlen($_POST['usuario']) < 5 || strlen($_POST['usuario']) > 22)
     { $mensaje_error[] = "El nombre de usuario debe contener entre 5 y 22 caracteres."; }
    else
     {
	  if($fila[0] == strtolower($_POST['usuario']))
	   { $mensaje_error[] = "El nombre de usuario '".$_POST['usuario']."' no est&aacute; disponible."; }
	 }
   }

  if(empty($_POST['email']))
   { $mensaje_error[] = "Debe ingresar una casilla de correos."; }
  else
   {
    if(!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $_POST['email']))
	 { $mensaje_error[] = "La casilla ingresada no parece ser una casilla v&aacute;lida."; }
	else
	 {
	  if($fila[1] == strtolower($_POST['email'])) $mensaje_error[] = "La casilla de correo ya se encuentra registrada en otro usuario.";
	  else $casilla = $_POST['email'];
	 }
   }

  if(($_POST['ia'] == "agregar" && (empty($_POST['adclave']) || empty($_POST['adclave2']))) || ($_POST['ia'] == "modificar" && (empty($_POST['adclave']) xor empty($_POST['adclave2']))))
   { $mensaje_error[] = "Ingrese la nueva contrase&ntilde;a dos veces."; }
  elseif($_POST['ia'] == "agregar" || ($_POST['ia'] == "modificar" && (!empty($_POST['adclave']) && !empty($_POST['adclave2']))))
   {
	if(strlen($_POST['adclave']) < 6)
	 { $mensaje_error[] = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres."; }
	else
	 {
	  if($_POST['adclave'] !== $_POST['adclave2']) $mensaje_error[] = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden.";
	  else $clave = $_POST['adclave'];
	 }
   }

  // mysql_errno()
  // 1062

  // substr(mysql_error(), -1)
  // 2 = usuario
  // 3 = admin_mail
  if($mensaje_error && $_POST['ia'] == "agregar")
   {
    $ia = "editar";
    $fila = $_POST;
   }
  elseif($_POST['ia'] == "agregar")
   {
	$nombre = $_POST['nombre'] ? "'".$_POST['nombre']."'" : "null";
	$apellido = $_POST['apellido'] ? "'".$_POST['apellido']."'" : "null";
	$nombre_mostrar = $_POST['nombre_mostrar'] ? $_POST['nombre_mostrar'] : $_POST['usuario'];
	$mysqli->query("INSERT INTO `usuarios` (`usuario`, `nombre_mostrar`, `clave`, `email`, `creado`, `creado_por`, `admin`) VALUES ('".$_POST['usuario']."', '${nombre_mostrar}', SHA1('".$_POST['adclave']."'), LCASE('".$_POST['email']."'), now(), {$_SESSION['usuario_id']}, 0)");
	if(!$id = $mysqli->insert_id)
	 {
echo "INSERT INTO usuarios_datos (id, nombre, apellido, pais_id, estado, ciudad, direccion, telefono, celular) VALUES (${id}, '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['pais']}', '{$_POST['estado']}', '{$_POST['ciudad']}', '{$_POST['direccion']}', '{$_POST['telefono']}', '{$_POST['celular']}')";
	  $mysqli->query("INSERT INTO usuarios_datos (id, nombre, apellido, pais_id, estado, ciudad, direccion, telefono, celular) VALUES (${id}, '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['pais']}', '{$_POST['estado']}', '{$_POST['ciudad']}', '{$_POST['direccion']}', '{$_POST['telefono']}', '{$_POST['celular']}')");
	  $ia = "editar";
	  $fila = $_POST;
	 }
else
  echo "que pasó";
echo "<h1>${id}</h1>";
   }
  elseif($_POST['ia'] == "modificar")
   {
    $id = $_POST['id'];
	if($casilla) $mod[] = "`email` = LCASE('${casilla}')";
	if($clave) $mod[] = "`clave` = SHA1('${clave}')";

	if($mod) $mysqli->query("UPDATE `usuarios` SET ".implode(",", $mod)." WHERE `usuario` = '{$_POST['usuario']}' AND id = '{$_POST['id']}'");


/*
	if($_POST['dato']['m'])
	 {
	  foreach($_POST['dato']['m'] AS $attri => $attra)
	   {
	    foreach($attra AS $attrk => $attrv)
	     {
	      if(empty($attrv)) $mysqli->query("DELETE FROM `usuarios_valores` WHERE `id` = '${attrk}'");
	      else $mysqli->query("UPDATE `usuarios_valores` SET `${attri}` = '${attrv}' WHERE `id` = '${attrk}'");
		 }
	   }
	 }

	if($_POST['dato']['n'])
	 {
	  foreach($_POST['dato']['n'] AS $attri => $attra)
	   {
		foreach($attra AS $attrv)
		 {
		  if(empty($attrv)) continue;
		  $mysqli->query("INSERT INTO `usuarios_valores` (`atributo_id`, `usuario_id`, `{$atributos[$attri]['tipo']}`) VALUES ('${attri}', '".$_POST['id']."', '${attrv}')");
		 }
	   }
	 }
*/

	$datos_modif = array();
	if(isset($_POST['nombre'])) $datos_modif[] = "nombre = '{$_POST['nombre']}'";
	if(isset($_POST['apellido'])) $datos_modif[] = "apellido = '{$_POST['apellido']}'";
	if(isset($_POST['pais'])) $datos_modif[] = "pais = '{$_POST['pais']}'";
	if(isset($_POST['departamento'])) $datos_modif[] = "estado = '{$_POST['departamento']}'";
	if(isset($_POST['ciudad'])) $datos_modif[] = "ciudad = '{$_POST['ciudad']}'";
	if(isset($_POST['direccion'])) $datos_modif[] = "direccion = '{$_POST['direccion']}'";
	if(isset($_POST['telefono'])) $datos_modif[] = "telefono = '{$_POST['telefono']}'";
	if(isset($_POST['celular'])) $datos_modif[] = "celular = '{$_POST['celular']}'";
	if(count($datos_modif)) $mysqli->query("UPDATE `usuarios_datos` SET ".implode(",", $datos_modif)." WHERE id = {$_POST['id']}");
   }
 }

//echo "UPDATE `usuarios_datos` SET ".implode(",", $datos_modif)." WHERE id = {$_POST['id']}<pre>\n";
//print_r($_POST);
//echo "\n\n\n</pre>";
//echo "<a href=\"/usuarios?id=${id}\">/usuarios?id=${id}</a>";
//exit;

header("Location: /usuarios?id=".$id);
//print_r($mensaje_error);
exit;

$id = $_POST['id'] ? $_POST['id'] : "false";
$modif = 0;
$mensaje = "false";

if($_POST['id'])
 {
  $mysqli->query("DELETE FROM usuarios_permisos WHERE usuario_id = '".$_POST['id']."'");
  if($_POST['permisos_t']) $mysqli->query("INSERT INTO `usuarios_permisos` (`usuario_id`, `cliente_id`) VALUES ('".$_POST['id']."', '".$_SESSION['cliente']."')");
  else
   {
	if($_POST['list2'])
	 {
	  foreach($_POST['list2'] AS $p) $mysqli->query("INSERT INTO `usuarios_permisos` (`usuario_id`, `obra_id`) VALUES ('".$_POST['id']."', '${p}')");
	 }
   }
 }

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


/*
if($_POST['permisos'] == "1" && $_POST['confirmar2'] == "Guardar" && $_POST['id'])
 {
  $admin_permisos = array();
  $permisos = $mysqli->query("SELECT ap.seccion_id, permiso, link FROM `admin_permisos` ap JOIN `admin_secciones` ads ON ap.seccion_id = ads.id WHERE admin_id = '".$_POST['id']."'");
  if($fila_perm = $permisos->fetch_row())
   {
	do
	 {
	  $admin_permisos[$fila_perm[0]] = array($fila_perm[1], $fila_perm[2]);
	 }while($fila_perm = $permisos->fetch_row());
	$permisos->close();
   }

  foreach($_POST['seccion'] as $indice => $valor)
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
	    if(!$mysqli->query("INSERT INTO `admin_permisos` (`admin_id`, `seccion_id`, `permiso`) VALUES ('".$_POST['id']."', '$indice', '$valor')")) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
	    else { if($mysqli->affected_rows) $modif++; }
	   }
	 }
   }
 }
*/
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $seccion; ?></title>
</head>
<?php

echo "<body onload=\"parent.cambiosGuardados(".$id.", 1);\">";
/*
echo "<body>";
*/
echo "<pre>";
print_r($_POST);
echo "</pre>";


?>

</body>
</html>