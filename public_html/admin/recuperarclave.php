<?php

require('inc/iniciar.php');
//require('inc/ad_sesiones_s.php');

/*
if(!$_POST)
 { login_error(FALSE, $autousername); }
else
 {
  if(empty($_POST["usuario"]) || empty($_POST["email"]))
   { login_error("Debe completar ambos campos.", $autousername); }

  $username = $_POST["usuario"];
  $casilla = $_POST["email"];

  $mysqli = BaseDatos::Conectar();
  if(!$result = $mysqli->query("SELECT `admin_id`, `usuario`, `admin_mail`, `admin_aut` FROM `admin` WHERE `usuario` = '{$username}' AND `admin_mail` = '{$casilla}' LIMIT 1")) die (__LINE__.': '.$mysqli->error);
  if($row = $result->fetch_assoc())
   {
    $username = $row["usuario"];
    $email = $row["admin_mail"];
    $clave = generarpass(15);
    $mysqli->query("UPDATE `admin` SET `admin_aut` = '$clave' WHERE `admin_id` = '".$row["admin_id"]."' LIMIT 1");

//    if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $row["admin_mail"]))
//     {
    //require('../../inc/class.phpmailer.php');

    $mail = new PHPMailer();
    $mail->From     = "info@eltorodepicasso.com";
    $mail->FromName = "el toro de picasso";
    $mail->Subject = "Recuperar contraseña";
    $mail->AddAddress($email);
    $mail->Mailer   = "smtp";
    //nl2br($encoded)
    $mail->Body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\">
<title>".$mail->Subject."</title>
<style type=\"text/css\">
span.atributo {font:normal 0.8em serif;text-decoration:underline;}
span.datos, ul.datos li {font:normal 1em sans-serif;}
</style>
</head>
<body>
<table align=\"center\" border=\"1\" width=\"575\">
 <tbody>
 <tr>
  <td height=\"126\" background=\"http://".$_SERVER['HTTP_HOST']."/img/cabezal_mail.jpg\">&nbsp;</td></tr>
 </tbody>
</table>
<table align=\"center\" border=\"1\" width=\"575\">
 <tbody>
 <tr>
  <td><font size=\"2\"><a href=\"http://".$_SERVER['HTTP_HOST']."/clave?usuario=".$username."&amp;email=".$email."&amp;aut=".$clave."\">".$_SERVER['HTTP_HOST']."/clave?usuario=".$username."&amp;email=".$email."&amp;aut=".$clave."</a></font></td></tr>
 <tr>
  <td bgcolor=\"#bfb196\"><br>
<span class=\"datos\">".$mail->FromName."</span><br></td></tr>
 </tbody>
</table>
</body>
</html>";
    $mail->AltBody = "http://".$_SERVER['HTTP_HOST']."/clave?usuario=".$username."&amp;email=".$email."&amp;aut=".$clave."
\n\n\n
Enviado a través de ".$_SERVER['HTTP_HOST'].".";
    if(!$mail->Send())
     { login_error("Ocurri&oacute; un error al enviar el mensaje.<br />Por favor intentelo nuevamente.", NULL); }
    else
     {
header("Location: ./recuperarenviado");
exit;
//	echo "<div>Su mensaje ha sido enviado correctamente.<br />Pronto nos pondremos en contacto con Ud.</div>";
     }
   }
  else
   { login_error("La informaci&oacute;n brindada no es correcta.", NULL); }
 }
*/


if(!$_POST)
    login_error(false, false);
if($_SESSION['usuario_id'])
    login_error(5, $_SESSION['usuario']);
elseif(!$_POST['usuario'] || !$_POST['email'])
    login_error(2, $_POST['usuario']);

/*else
 {
  if(empty($_POST["usuario"]) || empty($_POST["email"]))
   { login_error("Debe completar ambos campos.", $autousername); }
*/

$usuario = $_POST["usuario"];
$email = $_POST['email'];
$mysqli = BaseDatos::Conectar();
if(!$result = $mysqli->query("SELECT `id`, `email`, `nombre_mostrar` FROM `usuarios` WHERE `usuario` = '{$usuario}' AND email = '{$email}' LIMIT 1"))
    login_error(6, null);
if($row = $result->fetch_row()) {
    //$username = $row["usuario"];
    $id = $row[0];
    $email = $row[1];
    $nombre = $row[2];
    function generarpass($largo = 16)
	 {
	  $clave_caract = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789:.,";
	  $n_clave = "";
	  for ($i=0; $i < $largo; $i++)
	   { $n_clave .= substr($clave_caract, rand(1, strlen($clave_caract)), 1); }
	  return $n_clave;
	 }
    $clave = generarpass(15);
    $mysqli->query("UPDATE `usuarios` SET `aut` = '{$clave}' WHERE `id` = '{$id}' LIMIT 1");

/*
    if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $row["admin_mail"]))
     {
*/

    $mail = new PHPMailer();
    $mail->Host     = "localhost";
    $mail->From     = "no-responder@".DOMINIO;
	$mail->CharSet  = "utf-8";
    $mail->FromName = SITIO_TITULO;
    $mail->Subject = "Recuperar contraseña";
    $mail->AddAddress($email, $nombre);
    $mail->Mailer   = "smtp";
    $mail->Username = "no-responder@".DOMINIO;
	$mail->Password = MYSQL_CLAVE;
    //nl2br($encoded)
    $mail->Body = "
 ".SITIO_TITULO."

   Estimado {$nombre}:

Hemos recibido una petición para restablecer tu contraseña.

En caso de que el mensaje lo haya enviado otra persona o ya no te haga
falta, puedes ignorar esta notificación y seguir utilizando la
contraseña de siempre.

   Tu nombre de usuario
{$usuario}

Para restablecer tu contraseña, pulsa en este enlace:
http://admin.".DOMINIO."/cambiar_clave/{$clave}/{$usuario}

Si el enlace no te funciona, también puedes copiar el URL y pegarlo
manualmente en tu navegador.
";

    if(!$mail->Send())
	  login_error(4, NULL);
    else
	  login_error(1, NULL);
   }
  else
   { login_error(3, $_POST['usuario']); }

/*
function login_error($suceso_id, $usuario)
 {

  $doc = new DOMDocument('1.0', 'utf-8');

  $root = $doc->createElement("recuperarclave");
  $root = $doc->appendChild($root);

  $suceso = $doc->createElement("suceso");
  $suceso = $root->appendChild($suceso);
  $suceso->setAttribute("id", $suceso_id);
  $suceso->appendChild($doc->createTextNode($sucesos[$suceso_id]));

  echo $doc->saveXML();
  exit;
 }
*/

function login_error($suceso, $usuario, $enviado = 0)
 {
  global $ref, $email;
  if($suceso == 1)
   {
	header("Location: /recuperarenviado");
	exit;
   }
  $sucesos = array(
  1 => "Las indicaciones para restaurar su contraseña han sido enviadas a su casilla de correo",
  "Debe completar ambos campos",
  "Los datos ingresados no coinciden con nuestros registros",
  "Ocurrió un error al intentar enviarle un correo electrónico",//.\nPor favor intentelo nuevamente.
  "Existe una sesión abierta",
  "Ocurrió un error interno de servidor"
 );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recuperar contrase&ntilde;a - <?php echo SITIO_TITULO; ?></title>
<link rel="stylesheet" type="text/css" href="/css/login.css" />
</head>
<body>
<table id="contenedor"
 ><tbody
  ><tr
   ><td align="center"
    ><table id="superior"
     ><tbody
      ><tr
       ><td id="bienvenida">
		<div id="logo"></div>
	   <div id="descripcion"><h3>Recuperar contrase&ntilde;a</h3>
Ingrese su nombre de usuario y casilla de correo electr&oacute;nico para obtener una nueva contrase&ntilde;a.</div>
	  </td></tr>
	 <tr>
	  <td id="tdlogin">
		<form name="recuperarClave" action="<?php echo $_SERVER['REQUEST_URI']; ?>"  method="post">
		 <input type="hidden" name="ref" value="<?php echo $ref; ?>" />
		<table id="tablalogin">
		<?php

  if(!empty($suceso))
   {
    echo "
		 <tr>
		  <td colspan=\"2\" id=\"login_error\">{$sucesos[$suceso]}</td></tr>";
   }

?>
		 <tr class="campos">
		  <td class="campos1"><label for="usuario">Usuario</label></td>
		  <td class="campos2"><input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" onblur="this.className='campo'" onfocus="this.className='campo_on'" tabindex="1" class="campo" maxlength="22" /></td></tr>
		 <tr class="campos">
		  <td class="campos1"><label for="email">E-mail</label></td>
		  <td class="campos2"><input type="text" name="email" id="email" value="<?php echo $email; ?>" onblur="this.className='campo'" onfocus="this.className='campo_on'" tabindex="2" class="campo" /></td></tr>
		 <tr>
		  <td colspan="2" style="padding-left:17px;padding-right:14px;">
		  	<table style="width:100%;">
		  	 <tr>
		  	  <td>&nbsp;</td>
			  <td align="right"><input type="submit" value="Recuperar" class="boton" /></td></tr>
			 </table>
		  </td></tr>
		</table>
		</form>
	   </td></tr>
	 </tbody>
	</table>
  </td></tr>
 </tbody>
</table>
</body>
</html>
<?php

  exit;
 }

?>