<?php

$seccion_id = 1;
require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$titulo = "Formatos de fechas";
$seccion = "idiomas";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

// guardar
if(!empty($_REQUEST['id']))
 {
  $mysqli = BaseDatos::Conectar();
  if(!$result = $mysqli->query("SELECT codigo, nombre_nativo FROM `lenguajes` WHERE `id` = '".$_REQUEST['id']."'")) echo "<br />".__LINE__." mySql: ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
	$id = $_REQUEST['id'];
	$leng_cod = $fila[0];
	$nn = $fila[1];
   }

  $recibe = array('%d', '%j', '%D', '%l', '%m', '%n', '%F', '%M', '%Y', '%y', '%G', '%H', '%g', '%h', '%i', '%s', '%a', '%A');
  $devuelve = array('%1$s', '%1$d', '%2$s', '%3$s', '%4$s', '%4$d', '%5$s', '%6$s', '%7$d', '%8$s', '%9$d', '%9$s', '%10$d', '%10$s', '%11$s', '%12$s',	'%13$s', '%14$s');
  if($_POST)
   {
   	if(count($_POST['meses']) == 12)
   	 {
	  $texto_g = array();
	  $texto_g['meses'] = $_POST['meses'];
	  $texto_g['dias'] = $_POST['dias'];

	  $ff[1] = str_replace($recibe, $devuelve, $_POST['formato_fecha'][0]);
	  $ff[2] = str_replace($recibe, $devuelve, $_POST['formato_fecha'][1]);
	  if(@file_put_contents(RUTA_CARPETA.'leng/textos.'.$leng_cod, "<?php\n\$texto = ".var_export($texto_g, true).";\n\$formato_fecha = ".var_export($ff, true)."\n?>"))
	    $div_mensaje = "Los datos han sido almacenados correctamente.";
	  else
	    $div_mensaje = "No fue posible guardar los cambios.";
	 }
   }
  include('inc/iaencab.php');

  echo "<div id=\"nav_bar\"><a href=\"./idiomas\">Idiomas</a> &gt; <a href=\"./idiomas?id=${id}\">${nn}</a> &gt; <span>Formato de fecha</span></div>";
  $texto = array();
  $texto['meses'] = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $texto['dias'] = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");


  @include(RUTA_CARPETA.'leng/textos.'.$leng_cod);
  $ffsalida[1] = str_replace($devuelve, $recibe, $formato_fecha[1]);
  $ffsalida[2] = str_replace($devuelve, $recibe, $formato_fecha[2]);
  echo "
  <form action=\"./idiomas_fecha?id=${id}\" method=\"post\">
   <h4>Meses</h4>
   <ol>";
  foreach($texto['meses'] AS $k => $v)
   {
	echo "<li><input type=\"text\" name=\"meses[${k}]\" value=\"${v}\" /></li>";
   }
  echo "
   </ol>
   <h4>Días</h4>
   <ul>";
  foreach($texto['dias'] AS $k => $v)
   {
	echo "<li><input type=\"text\" name=\"dias[${k}]\" value=\"${v}\" /></li>";
   }
  $ahora = new Fecha(date("Y-m-d H:i:s"));
  echo "
   </ul>
   <h4>Formatos</h4>
   <ul>
    <li><label>Corto</label> <input type=\"text\" name=\"formato_fecha[]\" value=\"{$ffsalida[1]}\" size=\"50\" /> Ej.: ".$ahora->Formatear($formato_fecha[1], $texto)."</li>
    <li><label>Largo</label> <input type=\"text\" name=\"formato_fecha[]\" value=\"{$ffsalida[2]}\" size=\"50\" /> Ej.: ".$ahora->Formatear($formato_fecha[2], $texto)."</li>
   </ul>

   <input type=\"submit\" value=\"Guardar\" />
  </form>";
 }
?>

	<table class="tabla"
	 ><tbody
	  ><tr
	   ><th colspan="3">Día</th></tr
	  ><tr
	   ><td>d</td><td>Día del mes, 2 dígitos con ceros iniciales</td><td>01 a 31</td></tr
	  ><tr
	   ><td>D</td><td>Una representación textual de un día, tres letras</td><td>Dom a Lun</td></tr
	  ><tr
	   ><td>j</td><td>Día del mes sin ceros iniciales</td><td>1 a 31</td></tr
	  ><tr
	   ><td>l ('L' minúscula)</td><td>Una representación textual completa del día de la semana</td><td>Domingo a Sábado</td></tr
	  ><tr
	   ><th colspan="3">Mes</th></tr
	  ><tr
	   ><td>F</td><td>Una representación textual completa de un mes</td><td>Enero a Diciembre</td></tr
	  ><tr
	   ><td>m</td><td>Representación numérica de un mes, con ceros iniciales</td><td>01 a 12</td></tr
	  ><tr
	   ><td>M</td><td>Una representación textual corta de un mes, tres letras</td><td>Ene a Dic</td></tr
	  ><tr
	   ><td>n</td><td>Representación numérica de un mes, sin ceros iniciales</td><td>1 a 12</td></tr
	  ><tr
	   ><th colspan="3">Año</th></tr
	  ><tr
	   ><td>Y</td><td>Una representación numérica completa de un año, 4 dígitos</td><td>1999 o 2003</td></tr
	  ><tr
	   ><td>y</td><td>Una representación de dos dígitos de un año</td><td>99 o 03</td></tr
	  ><tr
	   ><th colspan="3">Hora</th></tr
	  ><tr
	   ><td>a</td><td>Ante meridiano y Post meridiano en minúsculas</td><td>am o pm</td></tr
	  ><tr
	   ><td>A</td><td>Ante meridiano y Post meridiano en mayúsculas</td><td>AM o PM</td></tr
	  ><tr
	   ><td>g</td><td>formato de 12-horas de una hora sin ceros iniciales</td><td>1 a 12</td></tr
	  ><tr
	   ><td>G</td><td>formato de 24-horas de una hora sin ceros iniciales</td><td>0 a 23</td></tr
	  ><tr
	   ><td>h</td><td>formato de 12-horas de una hora con ceros iniciales</td><td>01 a 12</td></tr
	  ><tr
	   ><td>H</td><td>formato de 24-horas de una hora con ceros iniciales</td><td>00 a 23</td></tr
	  ><tr
	   ><td>i</td><td>Minutos con ceros iniciales</td><td>00 a 59</td></tr
	  ><tr
	   ><td>s</td><td>Segundos, con ceros iniciales</td><td>00 a 59</td></tr
 	 ></tbody
	></table>

<?php

include('inc/iapie.php');

?>