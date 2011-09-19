<?php

require('../../inc/configuracion.php');
$secciones = new adminsecciones();
require('../../inc/ad_sesiones.php');

if($_REQUEST["pp"] == "clave")
 {
  if(!empty($_POST["clave_a"]) && !empty($_POST["clave_n"]) && !empty($_POST["clave_rn"]))
   {
    if(strlen($_POST["clave_n"]) >= 6)
     {
      if($_POST["clave_n"] == $_POST["clave_rn"])
       {
		$clave_cod = md5($_POST["clave_a"]);
		$clave_n_cod = md5($_POST["clave_n"]);
		if(!$result = $mysqli->query("SELECT `clave` FROM `admin` WHERE `usuario` = '$ad_username' AND `clave` = '$clave_cod' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
		else
		 {
		  if($row = $result->fetch_array())
		   {
		    if($row["clave"] == $clave_cod)
		     {
		      $mysqli_query("UPDATE `admin` SET `clave` = '$clave_n_cod' WHERE `usuario` = '$ad_username' AND `clave` = '$clave_cod' LIMIT 1");
		      $cambiada = $mysqli->affected_rows();
		      if($cambiada == 1)
		       { $mensaje = "Su contrase&ntilde;a ha sido modificada satisfactoriamente."; }
		      else
		       {
				$no_poromision1 = TRUE;
				$errorcl = "No se pudo modificar la contrase&ntilde;a. Int&eacute;ntelo nuevamente.";
			   }
		     }
		    else
		     {
		      $no_poromision1 = TRUE;
		      $errorcl = "La contrase&ntilde;a anterior no es correcta.";
		     }
		   }
		  else
		   {
		    $no_poromision1 = TRUE;
		    $errorcl = "La contrase&ntilde;a anterior no es correcta.";
		   }
         }
       }
      else
       {
		$no_poromision1 = TRUE;
		$errorcl = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden.";
       }
     }
    else
     {
      $no_poromision1 = TRUE;
      $errorcl = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres.";
     }
   }
  else
   {
    $no_poromision1 = TRUE;
    $errorcl = "Debe llenar todos los campos.";
   }
 }

elseif($_REQUEST["pp"] == "datos")
 {
  if(!empty($_POST["mail"])) // !empty($_POST["nombre"]) && !empty($_POST["apellido"]) && 
   {
    if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $_POST['mail']))
     {
      $mysqli->query("UPDATE `admin` SET `admin_mail` = '".$_POST["mail"]."' WHERE `usuario` = '$ad_username' LIMIT 1");
      // `admin_nombre` = '".$_POST["nombre"]."', `admin_apellido` = '".$_POST["apellido"]."', 
      if($mysqli->affected_rows == '1')
       { $mensaje = "Sus datos han sido modificados satisfactoriamente."; }
     }
    else
     {
      $no_poromision2 = TRUE;
      $errorcl = "Ingrese una casilla de correo v&aacute;lida.";
     }
   }
  else
   {
    $no_poromision2 = TRUE;
    $errorcl = "Debe llenar todos los campos.";
   }
 }


$titulo = "Cuenta";
$seccion = "cuenta";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('iaencab.php');


/* por omisión */
if(!$no_poromision1)
 {
  if(!$result = $mysqli->query("SELECT `usuario`, `admin_mail` FROM `admin` WHERE `usuario` = '$ad_username' LIMIT 1")) die(__LINE__." - mySql: ".$mysqli->error);
  if($row = $result->fetch_array())
   {
    if(!$nombre)
     { $nombre = $row["admin_nombre"]; }
    if(!$apellido)
     { $apellido = $row["admin_apellido"]; }
	if(!$mail)
     { $mail = $row["admin_mail"]; }

    echo "
	<form action=\"".$_SERVER['PHP_SELF']."?pp=datos&amp;sesion=".$sesion."\" method=\"post\">
	 <input type=\"hidden\" name=\"cnfrmr\" value=\"1\" />
	<table class=\"tabla\">
	 <tr>
	  <th colspan=\"2\">Administrador</th></tr>";
    if($errorcl)
     {
      echo "
	 <tr>
	  <td colspan=\"2\" height=\"35\">&nbsp;<b style=\"font-size:2;color:#800000;\">".$errorcl."</b></td></tr>";
     }
    elseif($mensaje)
     {
      echo "
	 <tr>
	  <td colspan=\"2\" height=\"35\">&nbsp;<b style=\"font-size:2;color:#000080;\">".$mensaje."</b></td></tr>";
     }
    echo "
	 <tr>
	  <td align=\"left\">Usuario</td>
	  <td><b>".$row["usuario"]."</b></td></tr>";
/*
	 <tr>
	  <td align=\"left\">Nombre</td>
	  <td><input type=\"text\" name=\"nombre\" value=\"".$nombre."\" size=\"20\" maxlength=\"30\" class=\"campo\" /></td></tr>
	 <tr>
	  <td align=\"left\">Apellido</td>
	  <td><input type=\"text\" name=\"apellido\" value=\"".$apellido."\" size=\"20\" maxlength=\"30\" class=\"campo\" /></td></tr>
*/
    echo "
	 <tr>
	  <td align=\"left\">E-mail</td>
	  <td><input type=\"text\" name=\"mail\" value=\"".$mail."\" size=\"30\" maxlength=\"60\" class=\"campo\" /></td></tr>
	 <tr>
	  <td colspan=\"2\" align=\"center\"><p><input type=\"submit\" value=\"Cambiar\" class=\"boton\" /></p></td></tr>
	</table>
	</form>
<br />";
   }
 }

if(!$no_poromision2)
 {
  echo "
	<form action=\"".php_self()."?pp=clave\" method=\"post\">
	 <input type=\"hidden\" name=\"cnfrmr\" value=\"1\" />
	<table class=\"tabla\">
	 <tr>
	  <th colspan=\"2\">Cambiar contrase&ntilde;a</th></tr>";
  if($errorcl)
   {
    echo "
	 <tr>
	  <td colspan=\"2\" height=\"35\">&nbsp;<b style=\"color:#800000;\">".$errorcl."</b></td></tr>";
   }

  echo "
	 <tr>
	  <td align=\"left\">Contrase&ntilde;a&nbsp;anterior</td>
	  <td><input type=\"password\" size=\"17\" name=\"clave_a\" id=\"clave_a\" class=\"campo\" /></td></tr>
	 <tr>
	  <td align=\"left\">Nueva&nbsp;contrase&ntilde;a</td>
	  <td><input type=\"password\" size=\"17\" name=\"clave_n\" id=\"clave_n\" class=\"campo\" /></td></tr>
	 <tr>
	  <td align=\"left\">Repita&nbsp;su&nbsp;nueva&nbsp;contrase&ntilde;a</td>
	  <td><input type=\"password\" size=\"17\" name=\"clave_rn\" id=\"clave_rn\" class=\"campo\" /></td></tr>
	 <tr>
	  <td colspan=\"2\" align=\"center\"><script type=\"text/javascript\">
/"."/<![CDATA[
document.write('<a href=\"javascript:mostrarContrasenya(\\'clave_a\\', \\'clave_n\\', \\'clave_rn\\');\" id=\"mostrarclave\">Mostrar contrase&ntilde;a<\/a>&nbsp;');
/"."/]]>
</script><input type=\"submit\" value=\"Cambiar\" class=\"boton\" /></td></tr>
	</table>
	</form>";
 }

include('./iapie.php');

?>