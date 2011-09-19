<?php
/*echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <meta name="Author" content="Pablo Bangueses" />
 <link rel="stylesheet" type="text/css" href="/css/v3/login.css" />
 <script type="text/javascript" src="/js/ia.js"></script>
 <script type="text/javascript" src="/js/login.js"></script>
</head>
<body>
 <div id="contenedor">
  <div id="cabezal">
   <h1 id="sitio_titulo"><?php echo SITIO_TITULO; ?></h1>
   <img src="/admin_logo" alt=""/>
  </div>
  <div id="documento">
   <p id="aviso_cont"><b id="aviso"><?php echo $sucesos[$respuesta] ? $sucesos[$respuesta] : '' ?>&nbsp;</b></p><!--  class="cargando" -->
   <fieldset>
    <legend>Acceder al sistema</legend>
    <form name="login" action="/login" method="post" onsubmit="return loginAcceso(this);">
     <input type="hidden" name="accion" value="acceder" />
     <input type="hidden" name="ref" value="<?php echo urlencode($ref); ?>" />
     <ul id="camposlogin">
      <li><label for="usuario" class="izq">Usuario</label> <input type="text" name="usuario" id="usuario" value="etdp" maxlength="22" /></li>
      <li><label for="clave" class="izq">Contrase&ntilde;a</label> <input type="password" name="clave" id="clave" /></li>
      <li style="display:none;"><span class="izq"><input type="checkbox" name="recordarme" id="recordarme" value="1" disabled="disabled" /></span> <label for="recordarme">Recordarme entre sesiones</label></li>
      <li><span id="envio"><input type="submit" value="Ingresar" class="boton" /></span></li>
     </ul>
     <p id="recuperarclave"><a href="./recuperarclave">&iquest;Olvid&oacute; su contrase&ntilde;a?</a></p>
    </form>
   </fieldset>
  </div>
  <address id="etdp"><a href="http://eltorodepicasso.es" target="_blank">el toro de picasso <img src="./img/etdp" width="24" height="18" alt="" /></a></address>
 </div>
</body>
</html>