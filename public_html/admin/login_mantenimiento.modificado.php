<?php

require_once('inc/iniciar.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <link rel="stylesheet" type="text/css" href="./css/login.css" />
 <script type="text/javascript" src="js/ia.js"></script>
 <script type="text/javascript" src="js/login.js"></script>
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
<span id="etdp">el toro de picasso</span> <img src="./img/etdp" width="24" height="18" alt="" />
	   </div>
	  </td></tr
	  ><tr
	   ><td id="tdlogin"
	    >
	    <form name="login" action="/login" method="post">
		 <input type="hidden" name="cuenta" value="acceder" />
		 <input type="hidden" name="ref" value="<?php echo urlencode($ref); ?>" />
		<div id="mantenimiento"><p>Estamos realizando tareas de mantenimiento.<br /> En breve podrá acceder al sistema.<br />Disculpe las molestias.</p></div>
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