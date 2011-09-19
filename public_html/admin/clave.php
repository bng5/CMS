<?php

require('../../inc/configuracion.php');
require('../../inc/ad_sesiones_s.php');

if(empty($_REQUEST['usuario']) || empty($_REQUEST['email']) || empty($_REQUEST['aut']))
 {
  header('Location: ./');
 }
else
 {
  if(!$result = $mysqli->query("SELECT `admin_id` FROM `admin` WHERE `usuario` = '".$_REQUEST['usuario']."' AND `admin_mail` = '".$_REQUEST['email']."' AND `admin_aut` = '".$_REQUEST['aut']."' LIMIT 1")) echo __LINE__." - ".$mysqli->error;
  else
   {
	if($row = $result->fetch_row())
	 {
	  $admin_id = $row['admin_id'];
	  if(empty($_POST['clave']) xor empty($_POST['clave2']))
	   { $mensaje_de_error = "Debe ingresar la nueva contrase&ntilde;a 2 veces."; }
	  elseif(!empty($_POST['clave']) && !empty($_POST['clave2']))
	   {
	    if(strlen($_POST["clave"]) >= 6)
	     {
		  if($_POST['clave'] == $_POST['clave2'])
		 {
		  mysql_query("UPDATE `admin` SET `clave` = PASSWORD('".$_POST['clave']."'), `admin_aut` = NULL WHERE `admin_id` = '$admin_id' LIMIT 1", $mysql);
		  if(mysql_affected_rows())
		   { header("Location: ./login?usuario=".$_POST['usuario']."&id=nclave"); }
		  else
		   { $mensaje_de_error = "Su contrase&ntilde;a no ha cambiado. Int&eacute;ntelo nuevamente."; }
		 }
		else
		 { $mensaje_de_error = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden."; }
       }
      else
       { $mensaje_de_error = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres."; }
     }
$result->close();
$mysqli->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recuperar contrase&ntilde;a - <?php echo SITIO_TITULO; ?></title>
<link rel="stylesheet" type="text/css" href="./css/login.css" />
<script type="text/javascript">

var claveVisible = false;
function mostrarContrasenya()
 {
  mostrarclave = document.getElementById('mostrarclave');
  clave = document.getElementById('clave');
  clave2 = document.getElementById('clave2');
  if(claveVisible == true)
   {
    claveVisible = false;
    mostrarclave.innerHTML = 'Mostrar contrase&ntilde;a';
    clave.setAttribute('type', 'password');
    clave2.setAttribute('type', 'password');
   }
  else
   {
    claveVisible = true;
    mostrarclave.innerHTML = 'Ocultar contrase&ntilde;a';
    clave.setAttribute('type', 'text');
    clave2.setAttribute('type', 'text');
   }
 }

</script>
</head>
<body>
<table style="width:100%;height:100%;">
 <tr>
  <td align="center">
	<table id="superior">
	 <tbody>
	 <tr>
	  <td height="172" id="bienvenida">
	   <div id="logo"></div>
	   <div id="descripcion"><h3>Cambiar contrase&ntilde;a</h3></div>
	  </td></tr>
	 <tr>
	  <td id="tdlogin">
		<form name="recuperarClave" action="<?php echo php_self()."\"  method=\"post\">
		 <input type=\"hidden\" name=\"usuario\" value=\"".$_REQUEST['usuario']."\" />
		 <input type=\"hidden\" name=\"email\" value=\"".$_REQUEST['email']."\" />
		 <input type=\"hidden\" name=\"aut\" value=\"".$_REQUEST['aut']; ?>" />
		<table id="tablalogin">
		<?php

  if(!empty($mensaje_de_error))
   {
    echo "
		 <tr>
		  <td colspan=\"2\" id=\"login_error\">".$mensaje_de_error."</td></tr>";
   }

?>
		 <tr class="campos">
		  <td class="campos1"><label for="clave">Contrase&ntilde;a</label></td>
		  <td class="campos2"><input type="password" name="clave" id="clave" onblur="this.className='campo'" onfocus="this.className='campo_on'" tabindex="1" class="campo" /></td></tr>
		 <tr class="campos">
		  <td class="campos1"><label for="clave2">Repita su contrase&ntilde;a</label></td>
		  <td class="campos2"><input type="password" name="clave2" id="clave2" onblur="this.className='campo'" onfocus="this.className='campo_on'" tabindex="2" class="campo" /></td></tr>
		 <tr>
		  <td colspan="2" style="padding-left:17px;padding-right:14px;">
		  	<table style="width:100%;">
		  	 <tr>
		  	  <td><script type="text/javascript"> document.write('<a href="javascript:mostrarContrasenya();" id="mostrarclave">Mostrar contrase&ntilde;a<\/a>'); </script></td>
			  <td align="right"><input type="submit" value="Recuperar" class="boton" /></td></tr>
			 </table>
		  </td></tr>
		</table>
		</form>
	 </tbody>
	</table>
  </td></tr>
</table>
</body>
</html>
<?php

   }
 }

?>