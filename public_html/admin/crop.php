<?php

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$imagen_id = $_POST['imagenId'];
	$atributo_id = $_POST['atributo'];
}
else {
	$imagen_id = $_GET['imagenId'];
	$atributo_id = $_GET['atributo'];
}

$db = DB::instancia();
$consulta = $db->prepare("SELECT io.archivo, io.ancho AS anchoorig, io.alto AS altoorig, iaa.ancho, iaa.alto, ia.extra FROM imagenes_a_atributos iaa JOIN imagenes_orig io ON iaa.imagen_id = io.id, items_atributos ia WHERE iaa.atributo_id = ia.id AND iaa.imagen_id = :imagen AND iaa.atributo_id = :atributo LIMIT 1");
$consulta->execute(array(':imagen' => $imagen_id, ':atributo' => $atributo_id));
$consulta->bindColumn('archivo', $archivo);
$consulta->bindColumn('anchoorig', $anchoorig);
$consulta->bindColumn('altoorig', $altoorig);
$consulta->bindColumn('ancho', $ancho);
$consulta->bindColumn('alto', $alto);
$consulta->bindColumn('extra', $extra);
$consulta->fetch(DB::FETCH_ASSOC);
$extra = unserialize($extra);

$ancho = $anchoorig;
$alto = $altoorig;
$porcentaje = 1;
if($anchoorig > $altoorig) {
	$disp = 'h';
	if($anchoorig > 500) {
		$div = ($ancho / 500);
		$ancho = 500;
		$alto = ceil($altoorig / $div);
		$porcentaje = (500 / $anchoorig);
	}
}
else {
	$disp = 'v';
	if($altoorig > 500) {
		$div = ($alto / 500);
		$alto = 500;
		$ancho = ceil($ancho / $div);
		$porcentaje = (500 / $altoorig);
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
 <title>Cortar imagen</title>

<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$img = Imagen::crearDesdeArchivo(RUTA_CARPETA.'img/'.$archivo);

	if($_POST['w'] < $_POST['h']) {
		$factor = ($altoorig * $porcentaje) / $_POST['h'];
		$img->escalar(0, round($extra[0]['al'] * $factor));
	}
	else {
		$factor = ($anchoorig * $porcentaje) / $_POST['w'];
		$img->escalar(round($extra[0]['an'] * $factor));
	}
	$img->crop($extra[0]['an'], $extra[0]['al'], round(0 - ($img->ancho * $_POST['x'] / $ancho)), round(0 - ($img->alto * $_POST['y'] / $alto)));
	//unlink(RUTA_CARPETA."public_html/img/0/".$atributo_id."/".$archivo);
	$img->guardar(RUTA_CARPETA."public_html/img/0/".$atributo_id, FALSE, FALSE, true);

?>

 <script type="text/javascript">
//<![CDATA[
window.opener.mostrarCrop({<?php
echo "imagenId: {$imagen_id}, imagenArchivo: '{$img->archivo}', indice: {$_POST['indice']}, atributo: {$atributo_id}";
?>});
//]]>
 </script>
</head>
<body></body>
</html>
 <?php
	exit;
}


$sel['f'] = min(($ancho / $extra[0]['an']), ($alto / $extra[0]['al']));
$sel['w'] = round($extra[0]['an'] * $sel['f']);
$sel['h'] = round($extra[0]['al'] * $sel['f']);
$sel['x'] = ($ancho / 2) - ($sel['w'] / 2);
$sel['y'] = ($alto / 2) - ($sel['h'] / 2);


?>
 <link rel="stylesheet" href="/css/jquery.Jcrop.css" type="text/css" />
 <style type="text/css">
body {
	background: #eee;
}

#contenedor {
	width:<?php echo ($extra[0]['an'] + $ancho + 15) ?>px;
	margin: 0 auto;
}

#formulario {
	margin:10px 0;
	text-align:right;
}
 </style>
 <script src="/js/jquery-1.4.2.min.js" type="text/javascript"></script>
 <script src="/js/jquery.Jcrop.min.js" type="text/javascript"></script>
<script type="text/javascript">
// <![CDATA[
if(window.opener.ventanaModal == null)
	window.opener.ventanaModal = window;

jQuery(window).load(function() {

	jQuery('#cropbox').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		//bgColor:     'black',
        //bgOpacity:   .4,
        setSelect:   [<?php echo $sel['x'].', '.$sel['y'].', '.($sel['w'] + $sel['x']).', '.($sel['h'] + $sel['y']); ?>],
        aspectRatio: <?php echo $extra[0]['an'].' / '.$extra[0]['al']; ?>,
        minSize: [<?php echo round($extra[0]['an'] * $porcentaje).', '.round($extra[0]['al'] * $porcentaje); ?>]
	});
});

function showPreview(coords) {
	if (parseInt(coords.w) > 0) {
		var rx = <?php echo $extra[0]['an']; ?> / coords.w;
		var ry = <?php echo $extra[0]['al']; ?> / coords.h;

		$('#x').val(coords.x);
		$('#y').val(coords.y);
		$('#w').val(coords.w);
		$('#h').val(coords.h);
		jQuery('#preview').css({
			width: Math.round(rx * <?php echo $ancho ?>) + 'px',
			height: Math.round(ry * <?php echo $alto ?>) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
		//$("#pre").text(coords.x+'\n'+ coords.y+'\n'+coords.w+'\n'+ coords.h);
	}
}


// ]]>
</script>
</head>
<body>
<div id="contenedor">
	<div id="imagenes" style="overflow: hidden;">
		<div style="float:left;">
			<img src="/imgorig/<?php /*/icono/0/ $atributo_id.'/'.*/ echo $archivo ?>?tam=500" id="cropbox" alt="" />
		</div>
		<div style="width:<?php echo $extra[0]['an'] ?>px;height:<?php echo $extra[0]['al'] ?>px;overflow:hidden;border:1px solid #cccccc;float:left;margin-left:10px;">
			<img src="/imgorig/<?php echo $archivo ?>?tam=500" id="preview" alt=""/>
		</div>
	</div>
	<div id="formulario">
		<form method="post" action="/crop">
			<input type="hidden" name="atributo" value="<?php echo $atributo_id ?>" />
			<input type="hidden" name="imagenId" value="<?php echo $imagen_id ?>" />
			<input type="hidden" name="indice" value="<?php echo $_GET['indice'] ?>" />
			<input type="hidden" name="x" id="x" />
			<input type="hidden" name="y" id="y" />
			<input type="hidden" name="w" id="w" />
			<input type="hidden" name="h" id="h" />
			<button type="submit">Cortar</button>
		</form>
	</div>
</div>
</body>
</html>