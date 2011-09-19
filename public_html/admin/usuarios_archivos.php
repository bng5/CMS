<?php

//header("Content-type: text/html");

$titulo = "Archivos de Usuarios";
$seccion = "usuarios";
$seccion_id = 4;

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$usuario_id = $_GET['usuario'];
$ia = $_REQUEST['ia'];

//$mysqli = BaseDatos::Conectar();

function tamArchivo($bytes)
 {
  $unidades = array('bytes', 'KiB', 'MiB', 'GiB');
  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($unidades) - 1);
  $bytes /= pow(1024, $pow);
  return round($bytes, 1) . ' ' . $unidades[$pow];
 }
$meses = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic");

if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  $carpeta = RUTA_CARPETA.'usuarios_archivos/'.$_POST['usuario'];
  if($_POST['eliminar'])
   {
    $modifs = 0;
    if(is_array($_POST['lista_item']) && count($_POST['lista_item']))
     {
      foreach($_POST['lista_item'] AS $v)
       {
        if(unlink($carpeta."/".$v))
          $modifs++;
       }
     }
   }
  elseif($_POST['sobreescribir'])
   {
    unlink($carpeta.'/'.$_POST['archivo']);
    rename($carpeta.'/'.$_POST['temp'], $carpeta.'/'.$_POST['archivo']);
   }
  elseif($_POST['renombrar'])
   {
    if(file_exists($carpeta.'/'.$_POST['n_nombre']))
     {
      $confirmacion_sobreesc = true;
      $nombre = htmlspecialchars($_POST['temp']);
      $archivo = htmlspecialchars($_POST['n_nombre']);
     }
    else
      rename($carpeta.'/'.$_POST['temp'], $carpeta.'/'.$_POST['n_nombre']);
   }
  elseif($_FILES['archivo'] && !$_FILES['archivo']['error'] && $_POST['usuario'])
   {
    $carpeta = RUTA_CARPETA.'usuarios_archivos/'.$_POST['usuario'];
    if(!is_dir($carpeta))
      mkdir($carpeta);
    $destino = $carpeta.'/'.$_FILES['archivo']['name'];
    if(file_exists($destino))
     {
      $confirmacion_sobreesc = true;
      if($pos_punto = mb_strrpos($_FILES['archivo']['name'], '.'))
        $nombre = mb_substr($_FILES['archivo']['name'], 0, $pos_punto);
      else
        $nombre = $_FILES['archivo']['name'];
      $nombre = tempnam($carpeta, $nombre);
      unlink($nombre);
      $nombre = basename($nombre);
      $nombre .= $pos_punto ? mb_substr($_FILES['archivo']['name'], $pos_punto) : '';
      $destino = $carpeta.'/'.$nombre;
      $nombre = htmlspecialchars($nombre);
      $archivo = htmlspecialchars($_FILES['archivo']['name']);
     }
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino);
//Array
//(
//    [name] => eps
//    [type] => application/octet-stream
//    [tmp_name] => /tmp/php75eECB
//    [error] => 0
//    [size] => 43900
//)
   }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
 <script type="text/javascript" src="/js/editar.js" charset="utf-8"></script>

<?php

include('inc/iaencab.php');

if($confirmacion_sobreesc)
 {
  echo '
<form action="/usuarios_archivos?usuario='.$usuario_id.'" method="post">
 <input type="hidden" name="temp" value="'.$nombre.'" />
 <input type="hidden" name="archivo" value="'.$archivo.'" />
 <input type="hidden" name="usuario" value="'.$usuario_id.'" />
<fieldset>
 <legend>Confirmación de sebreescritura</legend>
 <p>Ya existe un archivo llamado <em>'.$archivo.'</em>.</p>
 <ul>
  <li>Remplazar el archivo existente <input type="submit" name="sobreescribir" value="Reemplazar" /></li>
  <li><label for="renombrar_a">Renombrar a:</label> <input type="text" name="n_nombre" id="renombrar_a" value="'.$nombre.'" /> <input type="submit" name="renombrar" value="Renombrar" /></li>
 </ul>
</fieldset>
</form>
';
  include('inc/iapie.php');
  exit;
 }

echo '
	<div class="solapas"><ul><li><a href="/usuarios?id='.$usuario_id.'">Información de usuario</a></li><li><span>Archivos</span></li></ul></div>';

$carpeta = RUTA_CARPETA.'/usuarios_archivos/'.$usuario_id;
  try {
    $path = new DirectoryIterator(RUTA_CARPETA.'usuarios_archivos/'.$usuario_id);
    echo '
<form action="/usuarios_archivos?usuario='.$usuario_id.'" method="post">
 <input type="hidden" name="usuario" value="'.$usuario_id.'" />
<table class="tabla"><thead><tr><th style="width: 20px; text-align: center;"><input type="checkbox" name="checkTodos" onclick="checkearTodo(this.form, this, \'lista_item[]\');" /></th><th>Archivo</th><th>Tamaño</th><th>Fecha</th></tr></thead><tbody>';
    foreach($path as $file)
     {
      if($file->isDot())
        continue;
      echo '
 <tr><td style="text-align: center;"><input type="checkbox" name="lista_item[]" value="'.htmlspecialchars($file->getFilename()).'" /></td><td>'.htmlspecialchars($file->getFilename()).'</td><td>'.tamArchivo($file->getSize()).'</td><td>'.date("d-", $file->getMTime()).$meses[date("n", $file->getMTime())].date("-Y G:i", $file->getMTime()).' hs.</td></tr>';
     }
    echo '
</tbody></table>
<div id="error_check_form" class="div_error" style="display: none;">No ha seleccionado ningún archivo.</div>
<div id="listado_opciones" style="padding: 4px;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding: 0pt 5px;" /><input type="submit" name="eliminar" value="Eliminar" onclick="return confBorrado(\'lista_item[]\');" /></div>
</form>';
   }catch (Exception $e) {
     echo "<p>No existen archivos asociados a este usuario.</p>";
   }

?>

<p><label>FTP:</label> <a href="ftp://<?php echo DOMINIO ?>/usuarios_archivos/<?php echo $usuario_id ?>">ftp://<?php echo DOMINIO ?>/usuarios_archivos/<?php echo $usuario_id ?></a></p>
<form action="/usuarios_archivos?usuario=<?php echo $usuario_id ?>" enctype="multipart/form-data" method="post">
 <input type="hidden" name="usuario" value="<?php echo $usuario_id ?>" />
 <fieldset>
  <legend>Subir archivo</legend>
  <ul>
   <li><input type="file" name="archivo" /> <input type="submit" value="Subir" /></li>
  </ul>
 </fieldset>
</form>

<?php
include('inc/iapie.php');

?>