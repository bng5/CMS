<?php

include('inc/iniciar.php');
require('inc/ad_sesiones.php');

$dias = array('Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb');
$meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$vista = new Vista_XHTML(Modelo_Secciones::getSeccionPorIdentificador('__usuarios'));

?>

<link rel="stylesheet" href="/css/eps/planilla.css" />
<script type="text/javascript" src="/js/eps/planilla.js" charset="utf-8"></script>
<script type="text/javascript">
function abrirDescarga(el)
 {
  el.className = (el.className == 'desplegable') ? 'desplegado' : 'desplegable';
  return false;
 }
</script>


<?php
include('./vistas/iaencab.php');

$usuario = $_GET['usuario'];
/*
?>


<ul>
<?php

foreach($planilla AS $planilla)
 {
  echo '<li><a href="/eps_planillas?usuario='.$usuario.'&amp;planilla='.$planilla->id.'">'.$planilla->fecha.'</a></li>';
 }
?>
</ul>
*/

  if($_SERVER['REQUEST_METHOD'] == 'POST')
   {
    if($_FILES['planilla'] && !$_FILES['planilla']['error'])
     {
	  $copia = time();
	  $archivo = RUTA_CARPETA.'/planillasTmp/'.$copia;
	  $guardado = move_uploaded_file($_FILES['planilla']['tmp_name'], RUTA_CARPETA.'/planillasTmp/'.$copia);
	 }
	elseif($_POST['archivo'])
	 {
	  $copia = $_POST['archivo'];
	  $archivo = RUTA_CARPETA.'/planillasTmp/'.$copia;
	 }

	if($archivo && file_exists($archivo))
	 {
	  $file = new SplFileObject($archivo);
	  $delimiter = array(1 => ",", 2 => ";", 3 => "\t", 4 => " ");
	  $enclosure = array(1 => "\"", 2 => "'");
      $file->setCsvControl($delimiter[$_POST['separador']], $enclosure[$_POST['delimitador']]);
	  //$file->setCsvControl($delimiter, $enclosure);//, "\\"
	  //$orden = 1;

      while($file->valid())
       {
        $data = $file->fgetcsv();
        $data_limpio = array_filter($data);
        if(count($data_limpio) < 3)
         {
          if(count($data_limpio) == 1 && !$titulo_planilla)
            $titulo_planilla = current($data_limpio);
          continue;
         }
	    $item = new EPS_PlanillaItem;
		implode("", $data);
        list($item->marca, $item->modelo, $item->insumo, $rendimiento, $precio_reman, $precio_nuevo) = $data;
	    if(strtolower($item->marca) == 'marca')
		 {
		  if(!mb_check_encoding($item->insumo, 'UTF-8'))
		   {
		    $utf8_conv = true;
			$item->insumo = utf8_encode($item->insumo);
		   }
		  //$insumo_tolower = strtolower($item->insumo);
		  //if($insumo_tolower == 'tinta' || $insumo_tolower == 'toner' || $insumo_tolower == 'tÓner' || $insumo_tolower == 'tóner')
		  if(stristr($item->insumo, "tinta") || stristr($item->insumo, "toner") || stristr($item->insumo, "tóner") || stristr($item->insumo, "tÓner"))
		   {
		    //$tipo = ($insumo_tolower == 'tinta') ? 1 : 2;
		    $tipo = stristr($item->insumo, "tinta") ? 1 : 2;
		    continue;
		   }
	     }
	    $item->tipo = $tipo;
	    $item->marca = $item->marca ? $item->marca : $marca_prev;
	    $item->set_parseRendimiento($rendimiento);
	    $item->set_parsePrecio('precio_reman', $precio_reman);
	    $item->set_parsePrecio('precio_nuevo', $precio_nuevo);
	    $marca_prev = $item->marca;
	    $planillaItems[] = $item;
       }
	 }
	else
	 {
	  echo '
<div class="error"><p>No se encontró el archivo para su apertura.</p></div>';
	 }
   }

  if($copia)
   {
	echo '
 <div class="aviso">
  <p>Esto es una previsualización, el documento no ha sido guardado aún.</p>
  <ol>
   <li>Si puede ver el documento de forma correcta seleccione \'Guardar\' al final de la página.</li>
   <li>De lo contrario, si la previsualización no es correcta: intente modificar las <a href="#opciones_apertura" onclick="document.getElementById(\'opcionesApertura\').style.display=\'block\';return false;">Opciones de apertura</a>.</li>
  </ol>
 </div>
 <fieldset id="opcionesApertura" style="display:none;">
  <legend><a name="opciones_apertura">Opciones de apertura</a></legend>
  <form action="/eps__planillas?usuario='.$usuario.'" method="post">
   <input type="hidden" name="archivo" value="'.$copia.'" />
   <ul>
    <li><label for="separador">Separador</label> <select id="separador" name="separador"><option value="1">Coma</option><option value="2">Punto y coma</option><option value="3">Tab</option><option value="4">Espacio</option></select></li>
    <li><label for="delimitador">Delimitador</label> <select id="delimitador" name="delimitador"><option value="1">Comilla doble</option><option value="2">Comilla simple</option></select></li>
    <li><input type="submit" value="Aceptar" /></li>
   </ul>
  </form>
 </fieldset>
';
   }
  else
   {
    if($planilla = EPSModelo_UsuariosUltPlanilla::Listado($usuario))
     {
      $titulo_planilla = $planilla->titulo;
	  $planillaItems = $planilla->getIterator();
	  $usuarioObj = Modelo_Usuarios::getPorId($usuario);
      $db = DB::instancia();
      $prefs = $db->query("SELECT separador, charset FROM `eps__usuarios_pref_planillas` WHERE usuario_id = ".$usuario." LIMIT 1");
      if($us_prefs = $prefs->fetch(DB::FETCH_NUM))
       {
        $sel_dwl_sep[$us_prefs[0]] = ' selected="selected"';
        $sel_dwl_char[$us_prefs[1]] = ' selected="selected"';
       }
	  echo '<p>
<form action="/eps__planillas_csv" method="get">
 <input type="hidden" name="usuario" value="'.$usuario.'" />
 <input type="hidden" name="id" value="'.$planilla->id.'" />
<fieldset class="desplegable"><legend><a href="/eps__planillas_csv?usuario='.$usuario.'&amp;id='.$planilla->id.'" onclick="return abrirDescarga(this.parentNode.parentNode)">Descargar CSV</a></legend><ul><li><label for="dwl_separador">Separador</label> <select id="dwl_separador" name="separador"><option value="1"'.$sel_dwl_sep[1].'>Coma</option><option value="2"'.$sel_dwl_sep[2].'>Punto y coma</option><option value="3"'.$sel_dwl_sep[3].'>Tab</option></select></li><li><label for="dwl_charset">Juego de caracteres</label> <select name="charset" id="dwl_charset"><option value="1"'.$sel_dwl_char[1].'>UTF-8</option><option value="2"'.$sel_dwl_char[2].'>ISO-8859-15</option><option value="3"'.$sel_dwl_char[3].'>Windows-1252</option></select> <input type="submit" value="Descargar" /></li><li><input type="checkbox" name="recordar_pref" id="recordar_pref" checked="checked" /> <label for="recordar_pref">Recordar mis preferencias</label></li></ul></fieldset>
</form>

<label>Usuario:</label> <span><a href="/usuarios?id='.$usuario.'">'.$usuarioObj->nombre_mostrar.'</a></span><br />
<label>Agregado:</label> <span>'.$dias[date('w', $planilla->fecha_agregado)].', '.date('j', $planilla->fecha_agregado).' de '.$meses[date('n', $planilla->fecha_agregado)].' de '.date('Y, G:i', $planilla->fecha_agregado).' hs.</span>';
	  if($planilla->fecha_modificado)
	   {
	    echo '<br />
<label>Última modificación:</label> <span>'.$dias[date('w', $planilla->fecha_modificado)].', '.date('j', $planilla->fecha_modificado).' de '.$meses[date('n', $planilla->fecha_modificado)].' de '.date('Y, G:i', $planilla->fecha_modificado).' hs.</span>';
	   }
	  echo '</p>';
     }
   }



if($planillaItems)
 {

?>

<div class="planilla_botones">
 <button id="boton_agregar" onclick="iniciarAccionFila(event, AGREGAR)"><img src="/img/silk/add" alt="Agregar fila" /></button>
 <button id="boton_eliminar" onclick="iniciarAccionFila(event, ELIMINAR)"><img src="/img/silk/delete" alt="Eliminar fila" /></button>
</div>

<form action="/eps__planillas_guardar" method="post">
 <input type="hidden" name="usuario" value="<?php echo $usuario ?>" />
 <input type="hidden" name="planilla" value="<?php echo $planilla->id ?>" />
 <h2><input type="text" name="titulo" value="<?php echo $titulo_planilla ?>" /></h2>
<table class="eps_planilla" id="eps_planilla">
 <tr>
  <th>Marca</th>
  <th>Tipus</th>
  <th>Insumo</th>
  <th>Tipo</th>
  <th>Rendimiento</th>
  <th title="Precio remanufacturado">Precio rem.</th>
  <th>Precio nuevo</th>
 </tr>
<?php
  foreach($planillaItems AS $item)
   {
	echo '
 <tr>
  <td class="marca"><span class="arrastre" onmousedown="moverFila(event)">&nbsp;</span> <input type="hidden" name="id[]" value="'.$item->id.'" /><input type="text" name="marca[]" value="'.htmlspecialchars($item->marca).'" /></td>
  <td><input type="text" name="modelo[]" value="'.htmlspecialchars($item->modelo).'" /></td>
  <td><input type="text" name="insumo[]" value="'.htmlspecialchars($item->insumo).'" /></td>
  <td><select name="tipo[]" onchange="cambioTipo(event)"><option value=""> </option><option value="2" '.($item->tipo == 2 ? 'selected="selected"' : '').'>Tóner</option><option value="1" '.($item->tipo == 1 ? 'selected="selected"' : '').'>Tinta</option></select></td>
  <td><input type="text" name="rendimiento[]" value="'.$item->getRendimiento().'" class="precio"/> <span>'.$item->getRendimientoUn().'</span></td>
  <td>&nbsp;€&nbsp;<input type="text" name="precio_reman[]" value="'.$item->getPrecioReman().'" class="precio"/></td>
  <td>&nbsp;€&nbsp;<input type="text" name="precio_nuevo[]" value="'.$item->getPrecioNuevo().'" class="precio" /></td>
 </tr>';
   }
?>

</table>
<div><input type="submit" value="Guardar cambios" /></div>
</form>

<?php
 }
elseif($copia)
 {
  echo '
<div class="error"><p>No fue posible abrir correctamente el documento.</p></div>';
 }

if(!$copia)
 {
?>
<fieldset>
 <legend>Subir archivo CSV</legend>
 <form action="/eps__planillas?usuario=<?php echo $usuario ?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="usuario" value="<?php echo $usuario ?>" />
  <ul>
   <li><input type="file" name="planilla" /></li>
   <li><label for="separador">Separador</label> <select id="separador"  name="separador"><option value="1">Coma</option><option value="2">Punto y coma</option><option value="3">Tab</option><option value="4">Espacio</option></select></li>
   <li><label for="delimitador">Delimitador</label> <select id="delimitador" name="delimitador"><option value="1">Comilla doble</option><option value="2">Comilla simple</option></select></li>
   <li><input type="submit" value="Guardar" /></li>
   <!-- li><input type="submit" onclick="this.form.action='/eps__planillas_prev';return true;" value="Previsualizar" /></li -->
  </ul>
 </form>
</fieldset>
<?php
 }


if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  //$consulta = $DB->prepare("INSERT INTO eps__planillas (usuario_id, orden, tipo, marca, modelo, insumo, rendimiento, precio_reman, precio_nuevo) VALUES (:usuario_id, :orden, :tipo, :marca, :modelo, :insumo, :rendimiento, :precio_reman, :precio_nuevo)");
 }

unset($vista);

?>