<?php

session_start();
header("Content-type: image/png");

$fuentes = array(
	array("814YZX__.TTF", 20),
	array("BATMOS.TTF", 30),
	array("besign.ttf", 16));
$f = $fuentes[array_rand($fuentes)];
$fuente = './fonts/'.$f[0];
$caracteres = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
$carac_largo = strlen($caracteres)-1;
$enc = '';
for($i = 0; $i < 4; $i++)
  $enc .= $caracteres[rand(0, $carac_largo)];
$angulo = rand(-6, 6);

//$img_handle = imagecreatefrompng("imagecode_bg.png");
$img = imagecreate(100, 66);
//$img = imagecreatetruecolor(153, 41);
//$color = imagecolorallocate ($img_handle, 450, 450, 450);
//$enc = strtoupper(substr(md5(rand()), 2, 4));

if($_GET['ref'])
    $_SESSION['captcha'][$_GET['ref']] = $enc;

$background_color = imagecolorallocate($img, 0, 0, 0);//77, 75, 75);
$text_color = imagecolorallocate($img, 255, 255, 255);
imagettftext($img, $f[1], $angulo, 13, 41, $text_color, $fuente, $enc);
imagepng ($img);
imagedestroy($img);

?>