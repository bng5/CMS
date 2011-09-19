<?php

$carpetas = explode("/", trim($_REQUEST['carpeta'], "/"));
$seccion = $carpetas[0];
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$path_arr = explode("/", trim($_SERVER['PATH_INFO'], " /#"));
//{$this->id}/{$this->indice}

/*
function tam_archivo($size)
 {
  $unidades = array("bytes", "Kb", "Mb");
  for ($i = 0; $size > 1024; $i++)
   { $size /= 1024; }
  return round($size, 2)." ".$unidades[$i];
 }

function get_extension($archivo)
 {
  $array2 = explode(".", $archivo);
  $retval = strtolower(array_pop($array2));
  return $retval;
 }
*/
$dir_inicial = RUTA_CARPETA."img/";
//$dir = $_REQUEST['dir'];
//if(strlen($dir)  substr($_REQUEST['dir']))
/*
$elem_vacios = array_keys($carpetas, "");
foreach($elem_vacios as $indice)
 { unset($carpetas[$indice]); }
*/
//print_r($carpetas);
$dir = "/".implode("/", $carpetas)."/";
$t = 0;

/*echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITIO_TITULO; ?></title>

<script type="text/javascript">
// <![CDATA[
var browser_es_ie = document.all ? true : false;
var pesoUnidades = new Array("bytes", "Kb", "Mb", "Gb", "Tb");
var enviadoImagenId;
var enviadoImg;

function tam_archivo(peso)
 {
  for(var i = 0; peso > 1024; i++)
   { peso /= 1024; }
  return Math.round(peso)+" "+pesoUnidades[i];
  //return peso+" "+pesoUnidades[i];
 }

function comp_img_a_attr()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  if(browser_es_ie)
	   {
		img_arr = new Array(enviadoImg, <?php echo $path_arr[0].", ".$path_arr[1]; ?>);
		window.returnValue = img_arr;
		window.close();
	   }
	  else
	   {
		window.opener.imgCargada(false, enviadoImagenId, enviadoImg, false, <?php echo $path_arr[1].", ".$path_arr[0] ?>);
		alert('La imagen ha sido asignada.');
		window.close();
		//alert('se va a cerrar');
		//window.opener.focus();
		//return false;
	   }

     }
    else
	 {
	  alert('HTTP Status: '+req.status);
	 }
   }
 }

function insImagen(imagenId, img)
 {
  window.stop();
  enviadoImagenId = imagenId;
  enviadoImg = img;
  imgAguarde = document.getElementById('img_aguarde');
  if(imgAguarde.firstChild == null) imgAguarde.appendChild(document.createTextNode('Aguarde un momento'));
  imgAguarde.style.display = 'block';
  loadXMLDoc('/modificar_archivo_img?img='+imagenId+'&archivo='+img+'&attr=<?php echo $path_arr[0] ?>', comp_img_a_attr, null);
 }

function previsualizarImg(archivoId, archivo, ancho, alto, peso)
 {
  prevImg.src='/icono/3/'+archivo;
  prevArchivo.replaceData(prevArchivoL, prevArchivoA, archivo);
  prevAncho.replaceData(prevAnchoL, prevAnchoA, ancho+' px.');
  prevAlto.replaceData(prevAltoL, prevAltoA, alto+' px.');
  prevPeso.replaceData(prevPesoL, prevPesoA, tam_archivo(peso));

  prevArchivoA = (prevArchivo.length - prevArchivoL);
  prevAnchoA = (prevAncho.length - prevAnchoL);
  prevAltoA = (prevAlto.length - prevAltoL);
  prevPesoA = (prevPeso.length - prevPesoL);

  document.forms[0]['seleccionadaId'].value = archivoId;
  document.forms[0]['seleccionada'].value = archivo;
 }

// ]]>
</script>
<style type="text/css">

#img_listado {
	float:left;
	width:400px;
	height:378px;
}

#img_listado div {
	width:400px;
	height:320px;
	overflow:auto;
}

#prev_detalles {
	float:left;
	background-color:#efefef;
}

#div_img {
	width:260px;
	height:260px;
	text-align:center;
	vertical-align:top;
	padding-top:4px;

}
#div_img_info {
	width:260px;
	height:100px;
	overflow:auto;
}

#img_formulario {
	clear:both;
	padding:4px;
	text-align:center;
}
#img_aguarde {
	position:absolute;
	border:1px solid #cccccc;
	background-color:#efefef;
	font-weight:700;
	padding:0.5em 1em;
	top:160px;
	left:220px;
}

</style>
<?php

include('dialogencab.php');

?>
<div id="img_aguarde" style="display:none;"></div>
<fieldset id="img_listado">
 <legend>Im&aacute;genes disponibles</legend>
<?php

$mysqli = BaseDatos::Conectar();

$cons_total = $mysqli->query("SELECT count(id) FROM `imagenes_orig`");
if($total = current($cons_total->fetch_row()))
 {
  $a = 25;
  $paginas = ceil($total / $a);
  $pagina = is_numeric($_GET["pagina"]) ? floor($_GET["pagina"]): 1;
  if($pagina > $paginas) $pagina = $paginas;
  $desde = ($pagina - 1) * $a;


//$documentos = @scandir($dir_inicial.$dir);
//if($documentos)
$consulta = $mysqli->query("SELECT io.id, io.archivo, io.ancho, io.alto, io.peso, iaa.id IS NULL AS sin_uso FROM `imagenes_orig` io LEFT JOIN imagenes_a_atributos iaa ON io.id = iaa.imagen_id GROUP BY io.id ORDER BY 1 LIMIT ${desde}, ${a}");
if($fila = $consulta->fetch_assoc())
 {
  $extensiones = array(sql => "sql");
  echo "
   <div>
	<table class=\"tabla\" style=\"width:100%;\">
	 <colgroup>
	  <col style=\"width:20px;text-align:center;\"></col>
	  <col width=\"40\"></col>
	  <col></col>
	 </colgroup>
	<tbody>
";
  // <col width=\"20\"></col>
  do
  //foreach($documentos as $e => $archivo)
   {
    //$ruta_archivo = $dir_inicial.$dir.$archivo;
    //if(filetype($ruta_archivo) != "file") continue;
    echo "
	 <tr>
	  <td>";
	if($fila['sin_uso']) echo "<input type=\"checkbox\" name=\"a[]\" value=\"{$fila['archivo']}\" />";
	echo "</td>
	  <td><img src=\"/icono/2/{$fila['archivo']}\" onclick=\"previsualizarImg({$fila['id']}, '{$fila['archivo']}', {$fila['ancho']}, {$fila['alto']}, {$fila['peso']});\" ondblclick=\"insImagen({$fila['id']}, '{$fila['archivo']}');\" style=\"cursor:pointer;\" alt=\"\" /></td>
	  <td><a onclick=\"previsualizarImg({$fila['id']}, '{$fila['archivo']}', {$fila['ancho']}, {$fila['alto']}, {$fila['peso']});\" ondblclick=\"insImagen({$fila['id']}, '{$fila['archivo']}');\" href=\"#\">{$fila['archivo']}</a></td>
	 </tr>";
    // <td><img src=\"./img/b_drop\" alt=\"Borrar\" /></td>
   }while($fila = $consulta->fetch_assoc());
  echo "
	 </tbody>
	</table>";
 }
else
 { echo "No existe la carpeta ".$dir; }

  echo "
   </div>
   <p>";
  if($pagina > 1) echo "<a href=\"/examinar{$_SERVER['PATH_INFO']}?pagina=".($pagina - 1)."\">Anterior</a> ";
  for($p = 1; $p <= $paginas; $p++)
	echo ($p == $pagina) ? "<b>${p}</b> " : "<a href=\"/examinar{$_SERVER['PATH_INFO']}?pagina=${p}\">${p}</a> ";
  if($pagina < $paginas) echo "<a href=\"/examinar{$_SERVER['PATH_INFO']}?pagina=".($pagina + 1)."\">Siguiente</a> ";
  echo "</p>";
 }
//clearstatcache();

?>

   </fieldset>
<!-- style="width:260px;height:260px;text-align:center;vertical-align:top;padding-top:4px;" -->
<fieldset id="prev_detalles"><legend>Detalles</legend><div id="div_img"><img src="img/trans" alt="" /></div><div id="div_img_info"><ul><li>Archivo: </li><li>Ancho: </li><li>Alto: </li><li>Peso: </li></ul></div></fieldset>

<form name="imagen" action="#" method="post">
 <div id="img_formulario">
  <input type="hidden" name="seleccionadaId" />
  <input type="hidden" name="seleccionada" />
  <input type="button" value="Cancelar" onclick="window.close();" />&nbsp;<input type="button" value="Aceptar" onclick="if(this.form.seleccionada.value.length > 1) { insImagen(this.form.seleccionadaId.value, this.form.seleccionada.value); }" />
 </div>
</form>

<script type="text/javascript">
// <![CDATA[
var prevEl = document.getElementById('prev_detalles');
var prevImg = document.getElementById('div_img').firstChild;
var prevLista = prevEl.childNodes[2].firstChild;
var prevArchivo = prevLista.childNodes[0].firstChild;
var prevArchivoL = prevArchivo.length;
var prevArchivoA = 0;
var prevAncho = prevLista.childNodes[1].firstChild;
var prevAnchoL = prevAncho.length;
var prevAnchoA = 0;
var prevAlto = prevLista.childNodes[2].firstChild;
var prevAltoL = prevAlto.length;
var prevAltoA = 0;
var prevPeso = prevLista.childNodes[3].firstChild;
var prevPesoL = prevPeso.length;
var prevPesoA = 0;
// ]]>
</script>
</body>
</html>