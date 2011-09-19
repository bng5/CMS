<?php
/*echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es" lang="es">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="Content-Language" content="es" />
 <title>Karin Topolanski</title>
 <script type="text/javascript" src="/js/ia.js"></script>
 <script type="text/javascript" src="/js/login.js"></script>
 <style type="text/css">

body {
	color:#000000;
	font-family:Tahoma, sans-serif;
	font-size:12px;
	background-color: #F5F5F5;
}

#contenedor {

}

#logo {
	margin:23px auto;
	background:url(/admin_logo) no-repeat;
	width:214px;
	height:201px;
	overflow:hidden;
}

#logo h1 a {
	display:block;
	width:214px;
	height:201px;
	text-indent:-5000px;
}

#logo h1, #logo em {
	display:none;
}

p#aviso_cont {
	text-align:center;
}

fieldset {
border:0;
margin:0 auto;
width:300px;
}

li {
list-style-type:none;
float:left;
clear:both;
}

li label {
width:11em;
text-align:right;
vertical-align:middle;
}

li input {
float:left;
vertical-align:middle;
}

p#recuperarclave {
padding-top:15px;
clear:both;
}

 </style>
</head>
<body>
<div id="contenedor">
	<div id="logo">
		<em>Since 2004</em>
		<h1><a href="home">Karin Topolanski</a> <small>Photographer - Art Director</small></h1>
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