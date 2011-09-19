<?php

$titulo = "Mi cuenta";
//$seccion = "permisos";
//$seccion_id = 3;

require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$id = $_SESSION['usuario_id'];
$ia = $_REQUEST["ia"];

$mysqli = BaseDatos::Conectar();
/*
$atributos = array();
if(!$atributos_tipos = $mysqli->query("SELECT ua.id, sugerido, unico, tipo, subtipo, nombre, identificador FROM usuarios_atributos ua LEFT JOIN usuarios_atributos_nombres uan ON ua.id = uan.id AND leng_id = '1' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
if($fila_at = $atributos_tipos->fetch_row())
 {
  do
   {
	$atributos[$fila_at[0]] = array('sugerido' => $fila_at[1], 'unico' => $fila_at[2], 'tipo' => $fila_at[3], 'subtipo' => $fila_at[4], 'nombre' => $fila_at[5], 'identificador' => $fila_at[6]);
   }while($fila_at = $atributos_tipos->fetch_row());
  $atributos_tipos->close();
 }
*/

// modificar
if($_POST['ia'] == "modificar" && $_POST['confirmar'] == "Guardar")
 {
  if($_POST['ia'] == "modificar") $cond = "AND id != '${id}'";
  //$resultado = $mysqli->query("SELECT `usuario`, `email` FROM `usuarios` WHERE (`usuario` = '".$_SESSION['usuario']."' OR `email` = '".$_POST['email']."') ${cond} LIMIT 1");
  //$fila = $resultado->fetch_row();
  //$resultado->close();

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

  if((empty($_POST['adclave']) xor empty($_POST['adclave2'])))
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

  $modif = array();
  if($casilla) $modif[] = "`email` = LCASE('${casilla}')";
  if($clave) $modif[] = "`clave` = SHA1('${clave}')";
  if(count($modif))
   {
   	$mysqli->query("UPDATE `usuarios` SET ".implode(", ", $modif)." WHERE `usuario` = '{$_SESSION['usuario']}' AND id = '${id}'");
   	$mensaje_error[] = $mysqli->affected_rows ? "Los cambios fueron realizados." : "No se produjo ningún cambio.";
   }
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
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('inc/iaencab.php');

// agregar / editar
//if($ia == "editar" || !empty($id))
// {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  if(!$mensaje_error || $_POST['ia'] == "modificar")
   {
	if(!empty($id))
	 {
	  if(!$result = $mysqli->query("SELECT `id`, `usuario`, UNIX_TIMESTAMP(`creado`) AS creado, `email` FROM `usuarios` WHERE `id` = '${id}'")) echo __LINE__." - ".$mysqli->error;
	  else
	   {
	  	if($fila = $result->fetch_assoc())
	     {
	      $filaDB = true;
	      $transaccion = "Editar";
		  echo "
	<!-- form name=\"edicion\" method=\"post\" action=\"php_self()_guardar".$sesion1."\" target=\"frguardar\" -->
	<form name=\"edicion\" method=\"post\" action=\"./micuenta\">
	 <input type=\"hidden\" name=\"id\" value=\"".$fila['id']."\" />";
		  $v_ia = "modificar";
	 	  $result->close();
		 }
	   }
	  /*	  
	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, UNIX_TIMESTAMP(`date`), text, `int` FROM usuarios_valores WHERE usuario_id = '".$_REQUEST['id']."'")) echo __LINE__." - ".$mysqli->error;
	  if($fila_valores = $cons_valores->fetch_row())
	   {
	    do
	     {
		  $valor = $fila_valores[0];
		  $valores[$valor][] = array($fila_valores[1], $fila_valores[2], $fila_valores[3], $fila_valores[4], $fila_valores[5]);
	     }while($fila_valores = $cons_valores->fetch_row());
	 	$cons_valores->close();
	   }
	  */
	 }
   }
  if(!$filaDB)
  //else //if(!$fila || $mensaje_error)
   {
	echo "
	<form name=\"edicion\" method=\"post\" action=\"".php_self().$sesion1."\">";
	$v_ia = "agregar";
   }
  echo "
	 <input type=\"hidden\" name=\"ia\" value=\"${v_ia}\" />";

?>

	<table class="tabla">
	 <thead>
	 <tr>
	  <th colspan="2"><?php echo $transaccion; ?> administrador</th></tr>
<?php

  if($mensaje_error)
   {
	echo "
	 <tr>
	  <td colspan=\"2\"><ul>";
	foreach($mensaje_error as $error)
	 { echo "<li>".$error."</li>"; }
	echo "</ul></td></tr>";
   }
  $req = $filaDB ? false : "<span>*</span>";

?>
	 </thead>
	 <tfoot>
	  <!-- tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="< ? php echo php_self()."?de=".$_REQUEST['de']."&amp;".SID; ? >">Regresar</a></div></td></tr -->
	  <tr>
	   <td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/micuenta?de=<?php echo $_REQUEST['de']."&amp;".SID; ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar" id="guardar" value="Guardar" tabindex="7" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for="usuario">Nombre de usuario</label>:</td>
	   <td><?php

  if(empty($fila['id'])) echo "<input type=\"text\" name=\"usuario\" id=\"usuario\" value=\"".$fila['usuario']."\" size=\"22\" maxlength=\"22\" tabindex=\"1\" />*";
  else
   {
	$ultimo_login = $fila['ultimo_login'] ? formato_fecha($fila[4], false) : "nunca";
    echo $fila['usuario']."<input type=\"hidden\" name=\"usuario\" id=\"usuario\" value=\"".$fila['usuario']."\" /></td></tr>
	  <tr>
	   <td>Creado</td>
	   <td>".Fecha::formato($fila['creado'], false)."</td></tr>";
	/*  <tr>
	   <td>&Uacute;ltimo login</td>
	   <td>".$ultimo_login;
	*/
   }
//</td></tr>

?>
	  <tr>
	   <td><label for="adclave">Contrase&ntilde;a</label>:</td>
	   <td><input type="password" name="adclave" id="adclave" size="22" maxlength="30" tabindex="2" /><?php echo $req; ?></td></tr>
	   <tr>
	   <td><label for="adclave2">Repita contrase&ntilde;a</label>:</td>
	   <td><input type="password" name="adclave2" id="adclave2" size="22" maxlength="30" tabindex="3" /><?php echo $req; ?></td></tr>
	  <tr>
	   <td><label for="email">E-mail</label>:</td>
	   <td><input type="text" name="email" id="email" value="<?php echo $fila['email']; ?>" size="22" maxlength="70" tabindex="6" />*</td></tr>
	 </tbody>
	</table>
	</form>
	<iframe id="frguardar" name="frguardar" style="display:none;"></iframe>

<?php

// }



include('inc/iapie.php');

?>