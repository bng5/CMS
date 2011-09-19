<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <meta name="Author" content="Pablo Bangueses" />
 <link rel="stylesheet" type="text/css" href="/css/v2/login.css" />
 <script type="text/javascript" src="/js/ia.js"></script>
 <script type="text/javascript" src="/js/login.js"></script>
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
		<div id="descripcion">Sistema de actualizaci&oacute;n de:
<h2 id="sitio_titulo"><?php echo SITIO_TITULO; ?></h2>
Creado por:<br />
<span id="etdp"><a href="http://eltorodepicasso.es" target="_blank">el toro de picasso</a></span> <a href="http://eltorodepicasso.es" target="_blank"><img src="./img/etdp" width="24" height="18" alt="" /></a>
	   </div>
	  </td></tr
	  ><tr
	   ><td id="tdlogin"
	    >
	    <form name="login" action="/login" method="post" onsubmit="return loginAcceso(this);">
		 <input type="hidden" name="cuenta" value="acceder" />
		 <input type="hidden" name="ref" value="<?php echo urlencode($ref); ?>" />
		<table id="tablalogin"
		 ><tbody
		<?php

if(count($suceso->mensajes))
 {
  $mensaje_cod = current($suceso->mensajes);
  echo "
		  ><tr>
		  <td colspan=\"2\" id=\"login_error\"".($mensaje_cod == 1 ? ' class="cargando"' : '').">{$sucesos[$mensaje_cod]}</td></tr";
 }

?>
		  ><tr class="campos"
		   ><td class="campos1"><label for="usuario">Usuario</label></td
		   ><td class="campos2"><input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" maxlength="22" /></td
		  ></tr
		  ><tr class="campos"
		   ><td class="campos1"><label for="clave">Contrase&ntilde;a</label></td
		   ><td class="campos2"><input type="password" name="clave" id="clave" /></td
		  ></tr
		  ><tr
		   ><td colspan="2" style="padding-left:17px;padding-right:14px;">
		  	<table style="width:100%;"
		  	 ><tbody
		  	  ><tr
		  	   ><td><a href="./recuperarclave">&iquest;Olvid&oacute; su contrase&ntilde;a?</a></td
		  	   ><td align="right"><input type="submit" value="Ingresar" class="boton" /></td
		  	  ></tr
		  	 ></tbody
			></table>
		   </td
		  ></tr
		 ></tbody
		></table>
		</form>
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