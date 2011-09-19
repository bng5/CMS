<?php
/*echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
// TODO Mostrar idioma y dir correspondientes
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo SITIO_TITULO; ?></title>
 <meta name="Author" content="Pablo Bangueses" />
 <!-- link rel="stylesheet" type="text/css" href="/css/v3/login.css" / -->
 <!-- script type="text/javascript" src="/js/ia.js"></script -->
 <!-- script type="text/javascript" src="/js/login.js"></script -->
</head>
<body>
<?php
//var_dump($this);
?>
    <h1><?php
echo $this->response->success;
?></h1>
 <div id="contenedor">
  <div id="cabezal">
   <h1 id="sitio_titulo"><?php echo SITIO_TITULO; ?></h1>
   <img src="/admin_logo" alt=""/>
  </div>
  <div id="documento">
       <?php

        if(isset($this->response) && $this->response->messages) {
            echo '
   <div id="aviso_cont" class="notice warning">
     <ul>';
            foreach($this->response->messages AS $msg) {
                echo "
       <li>{$msg->description}</li>";
            }
            echo '
     </ul>
   </div>';
   //<b id="aviso"><?php /*echo $this->respuesta->os[$respuesta] ? $sucesos[$respuesta] : ''*/ ? >&nbsp;</b></div><!--  class="cargando" -->
        }
        elseif($this->message) {
            echo "<div class=\"notice note\">{$this->message}</div>";
        }

       ?>
   <fieldset>
    <legend>Acceder al sistema</legend>
    <form name="login" action="" method="post"><!-- onsubmit="return loginAcceso(this);" -->
     <input type="hidden" name="action" value="acceder" />
     <input type="hidden" name="ref" value="<?php echo urlencode($referer); ?>" />
     <ul id="camposlogin">
      <li><label for="username" class="izq">Usuario</label> <input type="text" name="username" id="username" value="<?php echo $this->username ?>" maxlength="22" /><?php if($this->response->errors['username']) { echo ' error';} ?></li>
      <li><label for="clave" class="izq">Contrase&ntilde;a</label> <input type="password" name="clave" id="clave" /><?php if($this->response->errors['clave']) { echo ' error';} ?></li>
      <li><span class="izq"><input type="checkbox" name="recordarme" id="recordarme" value="1"<?php echo ($this->rememberme ? ' checked="checked"' : ''); ?> /></span> <label for="recordarme">Recordarme entre sesiones</label></li>
      <li><span id="envio"><input type="submit" value="Ingresar" class="boton" /></span></li>
     </ul>
     <p id="recuperarclave"><a href="./recuperarclave">&iquest;Olvid&oacute; su contrase&ntilde;a?</a></p>
    </form>
   </fieldset>
  </div>
 </div>
</body>
</html>