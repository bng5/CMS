<?php
/*echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es" lang="es">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="Content-Language" content="es" />
 <title><?php echo SITIO_TITULO; ?></title>
 <script type="text/javascript" src="/js/ia.js"></script>
 <script type="text/javascript" src="/js/login.js"></script>

</head>
<body>
<div id="contenedor">
	<div id="logo">
		<h1><?php echo SITIO_TITULO; ?></h1>
	</div>
	<div id="documento">
		<p id="aviso_cont"><b id="aviso"><?php echo $sucesos[$respuesta] ? $sucesos[$respuesta] : '' ?>&nbsp;</b></p>
		<fieldset>
			<legend>Acceder al sistema</legend>
			<form name="login" action="/login" method="post" onsubmit="return loginAcceso(this);">
				<input type="hidden" name="accion" value="acceder" />
				<input type="hidden" name="ref" value="<?php echo urlencode($ref); ?>" />
				<ul id="camposlogin">
					<li><label for="usuario" class="izq">Usuario</label> <input type="text" name="usuario" id="usuario" value="" maxlength="22" /></li>
					<li><label for="clave" class="izq">Contrase&ntilde;a</label> <input type="password" name="clave" id="clave" /></li>
					<li style="display:none;"><span class="izq"><input type="checkbox" name="recordarme" id="recordarme" value="1" /></span> <label for="recordarme">Recordarme entre sesiones</label></li>
					<li><span id="envio"><input type="submit" value="Ingresar" class="boton" /></span></li>
				</ul>
				<p id="recuperarclave"><a href="./recuperarclave">&iquest;Olvid&oacute; su contrase&ntilde;a?</a></p>
			</form>
		</fieldset>
	</div>
</div>
</body>
</html>