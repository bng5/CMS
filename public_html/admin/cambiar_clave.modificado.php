<?php

require('inc/iniciar.php');
$path = explode("/", trim($_SERVER['PATH_INFO'], " /"));

$mysqli = BaseDatos::Conectar();

$valid = $mysqli->query("SELECT id FROM usuarios WHERE usuario = '{$path[1]}' AND aut = '{$path[0]}' LIMIT 1");
if($fila = $valid->fetch_row())
 {


  if($_POST['clave'])
   {
	if(empty($_POST['clave1']) || empty($_POST['clave2']))
	 { $mensaje_error[] = "Ingrese la nueva contrase&ntilde;a dos veces."; }
	else
	 {
	  if(strlen($_POST['clave1']) < 6)
	   { $mensaje_error[] = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres."; }
	  else
	   {
	    if($_POST['clave1'] !== $_POST['clave2']) $mensaje_error[] = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden.";
	    else
	     {
	      $mysqli->query("UPDATE `usuarios` SET clave = SHA1('{$_POST['clave1']}'), aut = NULL WHERE `id` = '{$fila[0]}'");
	      if($mysqli->affected_rows)
	       {
			$login = new Login('acceder', $path[1], $_POST['clave1']);
			$suceso = $login->sucesoId();
			if($suceso == 3)
			 {
			  $_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
			  header("Location: /login?compcookie&ref=".$_POST['ref']);//Location: ".urldecode($_POST["ref"]), TRUE, 303);
			  exit;
			 }
			else $mensaje_error[] = "Imposible autenticar.<br />".$login->sucesoTxt();
	       }
	      else $mensaje_error[] = "Debido a un error en el servidor no fue posible cambiar su contraseña.";
		 }
	   }
	 }
   }

$aut = true;
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cambiar contrase&ntilde;a - <?php echo SITIO_TITULO; ?></title>
<link rel="stylesheet" type="text/css" href="/css/login.css" />
<style type="text/css">
td#tdlogin p, td#tdlogin form {
	margin:0 45px;
}

td#tdlogin ul {
	list-style-type:none;
}
td#tdlogin li {
	margin:4px 0;
	float:left;
	clear:left;
}
td#tdlogin li label {
	float:left;
	width:10em;
}

td#tdlogin li input {
	float:left;
}
td#login_error {
	padding-left:37px;
}

</style>
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
	   <div id="descripcion"><h3>Cambiar contrase&ntilde;a</h3></div>
	  </td></tr>
<?php

if($mensaje_error)
 {
  echo "
		 <tr>
		  <td colspan=\"2\" id=\"login_error\">".current($mensaje_error)."</td></tr>";
 }

?>
	 <tr>
	  <td id="tdlogin">
<?php

if($aut)
 {

?>


<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
 <ul>
  <li><label for="usuario">Usuario:</label> <span><?php echo $path[1] ?></span></li>
  <li><label for="clave1">Nueva contraseña:</label> <input type="password" name="clave1" id="clave1" /></li>
  <li><label for="clave2">Repita contraseña:</label> <input type="password" name="clave2" id="clave2" /></li>
  <li><input type="submit" name="clave" value="Enviar" /></li>
 </ul>
</form>

<?php

 }
else
 {
  echo "<p>La URL ingresada no es correcta o el enlace ha expirado.<br /><a href=\"/login\">Regresar</a></p>";
 }
?>
	   </td
	  ></tr
	 ></tbody
	></table>
   </td
  ></tr
 ></tbody
></table>
</body>
</html>