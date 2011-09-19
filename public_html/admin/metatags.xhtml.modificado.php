<?php

//header("Content-type: text/html; charset=utf-8");
$seccion_id = 7;
require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$titulo = "Información para buscadores";
$seccion = "meta";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
<?php

if($_POST['etiqueta']) {
	$meta = array();
	$meta['etiqueta'] = $_POST['etiqueta'];
	$meta['descripcion'] = $_POST['descripcion'];

	function limpia_valores(&$valor) {
		$valor = trim($valor);
		return ($valor ? $valor : false);
	}

	$meta_salida = "";
	foreach($meta['etiqueta'] AS $kw_k => $kw_v) {
		//array_walk($kw_v, 'limpia_valores');
		$meta['etiqueta'][$kw_k] = array_filter($kw_v, "limpia_valores");
		//if(count($meta['etiqueta'][$kw_k]))
		//	$meta_salida .= "<meta name=\"Keywords\" lang=\"${kw_k}\" content=\"".stripslashes(implode(" ", $meta['etiqueta'][$kw_k]))."\" />\n";
	}

	foreach($_POST['descripcion'] AS $desc_k => $desc_v) {
		$meta['descripcion'][$desc_k] = stripslashes(trim(str_replace(array("\r\n", "  "), " ", $desc_v)));
		//if($desc_v)
		//	$meta_salida .= stripslashes("<meta name=\"Description\" lang=\"${desc_k}\" content=\"".htmlspecialchars($meta['descripcion'][$desc_k])."\" />\n");
	}
	
	file_put_contents(RUTA_CARPETA.'public_html/inc_xhtml/meta.php', "<?php\n\$meta = ".var_export($meta, true).";\n?>");//\n${meta_salida}

}
else {
	@include(RUTA_CARPETA.'public_html/inc_xhtml/meta.php');
}

include('inc/iaencab.php');

?>
<form action="/metatags" method="post">
<ul class="lista_idiomas2" style="height:auto;margin-bottom:0;">
<?php
	$lengs = array();
	$mysqli = BaseDatos::Conectar();
	$cons_lengs = $mysqli->query("SELECT codigo FROM lenguajes WHERE estado > 0 AND estado < 5 ORDER BY leng_poromision DESC");
	if($fila_lengs = $cons_lengs->fetch_row()) {
		$leng_poromision = $fila_lengs[0];
		do {
			$lengs[] = $fila_lengs[0];
		}while($fila_lengs = $cons_lengs->fetch_row());
		$cons_lengs->close();
	}
	foreach($lengs AS $leng_id => $leng_cod)
		echo "<li><a onclick=\"mostrarMeta('${leng_cod}')\" id=\"etiqueta_idioma_${leng_cod}\" class=\"etiqueta_idioma".($leng_id ? '' : ' seleccionado')."\"><tt>(${leng_cod})</tt></a></li>";

?>
</ul>

<?php

foreach($lengs AS $leng_id => $leng_cod) {


  echo "
 <table id=\"meta-${leng_cod}\" class=\"tabla\"".($leng_id ? ' style="display:none;"' : '').">
  <tbody>
   <tr>
    <td><label for=\"etiquetas_${leng_cod}\">Etiquetas:</label></td><td class=\"etiquetas\">";
  if(count($meta['etiqueta'][$leng_cod])) foreach($meta['etiqueta'][$leng_cod] AS $et) echo "<span>".htmlspecialchars($et)."<input type=\"hidden\" name=\"etiqueta[${leng_cod}][]\" value=\"".htmlspecialchars($et)."\"/><sup onclick=\"borrarEtiqueta(this)\">X</sup></span> ";
  echo "<input type=\"text\" name=\"etiqueta[${leng_cod}][]\" id=\"etiquetas_${leng_cod}\" onkeypress=\"return mestadoPress(event)\" maxlength=\"250\" /></td></tr>
   <tr>
    <td><label for=\"descripcion_${leng_cod}\">Descripción</label></td><td><textarea name=\"descripcion[${leng_cod}]\" id=\"descripcion_${leng_cod}\" cols=\"80\" rows=\"4\" onkeyup=\"textCounter(this);\">".htmlspecialchars($meta['descripcion'][$leng_cod])."</textarea></td></tr>
  </tbody>
 </table>";

 }

?>
<!-- /div -->
<input type="submit" value="Guardar" />
</form>

<script type="text/javascript">
// <![CDATA[
var metaActivo = '<?php echo $leng_poromision ?>';

<?php

foreach($lengs AS $leng)
 {
  echo "etiquetas['${leng}'] = {};\n";
  if($meta['etiqueta'][$leng])
   {
	foreach($meta['etiqueta'][$leng] AS $cad_etiqueta)
	  echo "etiquetas['${leng}']['${cad_etiqueta}'] = ".strlen($cad_etiqueta).";\n";
   }
 }

?>

// ]]>
</script>

<?php

include('inc/iapie.php');

?>