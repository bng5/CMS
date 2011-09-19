<?php

require('inc/iniciar.php');
$seccion_id = 5;
require('inc/ad_sesiones.php');
$titulo = "Estadísticas";
$seccion = "estadisticas";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo." - ".SITIO_TITULO; ?></title>
 <style type="text/css">
.aws_contenedor table {
	margin:0 auto;
}
.aws_datos td {
	text-align:center;
	padding:2px 3px;
	font-size:12px;
}
.aws_datos thead td {
	height:40px;
	width:90px;
}
.aws_datos td.aws_barras_h {
	text-align:left;
	width:300px;
}

.aws_datos td.aws_barras_h img {
	padding:0;
	mergin:0;
	display:block;
}

.aws_datos td.aws_barras_h br {
	display:none;
}

.aws_datos td.aws {
	text-align:left;
}
</style>

<?php

include('inc/iaencab.php');

//$path = trim($_SERVER['PATH_INFO'], "/");
//$log = array('access' => 'accesos', 'error' => 'errores');
//if($log[$path])
// {
  //$us = explode("/", rtrim(RUTA_CARPETA, "/"));
  //$lineas = file("/var/log/apache2/".end($us)."/${path}.log");
$textos['es'] = array();
$textos['es']['dias'] = array("Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb");
$textos['es']['meses'] = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic");
$textos['es']['BROWSER'] = "Navegadores";
$textos['es']['DOMAIN'] = "Países";
$textos['es']['TIME'] = "Visitas por Horas";
$textos['es']['ROBOT'] = "Visitas de Robots/Spiders";
$textos['es']['OS'] = "Sistemas Operativos";
$textos['es']['SEREFERRALS'] = "Enlaces al sitio";
$textos['es']['SIDER'] = "Páginas/URLs";
$textos['es']['SEARCHWORDS'] = "Buscadores frases / Buscadores palabras clave";
$textos['es']['DAY'] = "Días";
$textos['es']['otros'] = "Otros";
$textos['es']['desconocido'] = "Desconocido";
$textos['es']['no_contab'] = "No contabilizados";


$array = array();
if(preg_match('/(\d{2})(\d{4})$/', $_GET['periodo'], $coincidencias))
 {
  $fecha = $coincidencias[0];
  $mes = (int)$coincidencias[1];
  $anyo = $coincidencias[2];
 }
else
 {
  $mes = date(m);
  $anyo = date(Y);
  $fecha = $mes.$anyo;
  $mes += 0;
 }
$time = mktime(0,0,0, $mes, 1, $anyo);

$cont_id = $_GET['cont'];
$carpeta_aws = RUTA_CARPETA.'tmp/awstats/';
$archivos = glob($carpeta_aws.'awstats*.'.DOMINIO.'.txt');
$anyos = array();
$preg_dom = str_replace('.', '\.', DOMINIO);
foreach($archivos AS $archivo)
 {
  if(preg_match_all('/awstats(\d{2})(\d{4})\.'.$preg_dom.'\.txt$/', $archivo, $match))
   {
	$anyos[$match[2][0]][($match[1][0] + 0)] = $match[1][0];
   }
 }
asort($anyos);
echo "<form action=\"/estadisticas\" method=\"get\"><input type=\"hidden\" name=\"cont\" value=\"${cont_id}\" /><select name=\"periodo\">";
foreach($anyos AS $anyo_k => $meses)
 {
  asort($anyos[$anyo_k]);
  foreach($anyos[$anyo_k] AS $mes_k => $mes_v)
   {
    echo "<option value=\"{$mes_v}{$anyo_k}\"".(($fecha == $mes_v.$anyo_k) ? ' selected="selected"' : '').">{$textos['es']['meses'][$mes_k]} {$anyo_k}</option>";
   }
 }
echo "</select><input type=\"submit\" value=\"Aceptar\" /></form>";




  echo "
<ul>
 <!-- li>Monthly history</li -->
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=DAY&amp;periodo=${fecha}\">{$textos['es']['DAY']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=TIME&amp;periodo=${fecha}\">{$textos['es']['TIME']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=DOMAIN&amp;periodo=${fecha}\">{$textos['es']['DOMAIN']}</a></li>
 <!-- li>Servidores</li -->
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=ROBOT&amp;periodo=${fecha}\">{$textos['es']['ROBOT']}</a></li>
 <!-- li>Duración de las visitas</li -->
 <!-- li>Tipo de fichero</li -->
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=SIDER&amp;periodo=${fecha}\">{$textos['es']['SIDER']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=OS&amp;periodo=${fecha}\">{$textos['es']['OS']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=BROWSER&amp;periodo=${fecha}\">{$textos['es']['BROWSER']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=SEREFERRALS&amp;periodo=${fecha}\">{$textos['es']['SEREFERRALS']}</a></li>
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=SEARCHWORDS&amp;periodo=${fecha}\">{$textos['es']['SEARCHWORDS']}</a></li>
 <!-- li>Miscellaneous</li -->
 <!-- li>Códigos de error HTTP</li -->
 <li><a href=\"{$_SERVER['PHP_SELF']}?cont=accesos\">Últimos 100 accesos</a></li>
</ul>
";
if($_GET['cont'] == "accesos")
 {
  $lineas = file(RUTA_CARPETA."access-logs/".DOMINIO);

  $lineas = array_slice($lineas, -100, 100);
  $lineas = array_reverse($lineas);

  echo "<h4>Últimos 100 accesos en orden inverso</h4>
<pre>";
  foreach($lineas as $linea_num => $linea)
   {
   	echo htmlspecialchars($linea);
   }
  echo "
</pre>";
  include('inc/iapie.php');
  exit;
 }
// }
//else
// {
//  echo "<ul><li><a href=\"/estadisticas/access\">Accesos</a></li><li><a href=\"/estadisticas/error\">Errores</a></li></ul>";
// }



$largo = (strlen(DOMINIO) + 18);
$a = glob($carpeta_aws."awstats*".DOMINIO.".txt");
$ad = array();
$md = array();
foreach($a AS $v)
 {
  $archivo = basename($v);
  if(strlen($archivo) == $largo)
   {
   	$ad[substr($archivo, 9, 4)][substr($archivo, 7, 2)+0] = $archivo;
   	$md[substr($archivo, 7, 2)+0] = true;
   }
 }
ksort($ad);
ksort($md);




function byte_format($number)//, $decimals = 2)
 {
  // kilo, mega, giga, tera, peta, exa, zetta, yotta
  $prefix_arr = array('','k','M','G','T','P','E','Z','Y');
  $i = 0;
  if($number == 0) $result = 0;
  else
   {
	$value = round($number, $decimals);
	while($value > 1024) { $value /= 1024; $i++; }
	$result = number_format($value, 2, '.', '');
   }
  $result .= ' '.$prefix_arr[$i].'B'; if (!$i) $result .= 'ytes';
  return $result;
 }

function fecha_formato($fecha)
 {
  global $textos;
  return substr($fecha, 6, 2).' '.$textos['es']['meses'][(substr($fecha, 4, 2)+0)].' '.substr($fecha, 0, 4).', '.substr($fecha, 8, 2).':'.substr($fecha, 10, 2);
  //$mk_fecha = @mktime(0, 0, 0, mb_substr($fecha, 5, 2), mb_substr($fecha, 8, 2), mb_substr($fecha, 0, 4));
 }



$contenidos = array(
	'GENERAL' => 6,
	'TIME' => 6,
	'VISITOR' => 1,
	'DAY' => 4,
	'DOMAIN' => 3,
	'LOGIN' => 1,
	'ROBOT' => 4,
	'WORMS' => 1,
	'EMAILSENDER' => 1,
	'EMAILRECEIVER' => 1,
	'SESSION' => 1,
	'SIDER' => 4,
	'FILETYPES' => 1,
	'OS' => 1,
	'BROWSER' => 1,
	'SCREENSIZE' => 1,
	'UNKNOWNREFERER' => 1,
	'UNKNOWNREFERERBROWSER' => 1,
	'ORIGIN' => 1,
	'SEREFERRALS' => 2,
	'PAGEREFS' => 2,
	'SEARCHWORDS' => 1,
	'KEYWORDS' => 1,
	'MISC' => 1,
	'ERRORS' => 1,
	'CLUSTER' => 1,
	'SIDER_414' => 1);

$contenidos_p = array(
	'GENERAL' => array('ORIGIN'),
	'BROWSER' => array('UNKNOWNREFERERBROWSER'),
	'OS' => array('UNKNOWNREFERER'),
	'SEREFERRALS' => array('PAGEREFS'),
	'SEARCHWORDS' => array('KEYWORDS')
	);


if(!$cont_id || !$contenidos[$cont_id]) $cont_id = "DAY";//GENERAL";





$gestor = fopen($carpeta_aws."awstats${fecha}.".DOMINIO.".txt", "r");
if($gestor)
 {

// índice
  //$pos_cont_id = "POS_".$cont_id;
  do
   {
    //$bufer = explode(" ", trim(fgets($gestor)));
    $bufer = trim(fgets($gestor));
    if(strpos($bufer, "POS_") === 0)
     {
      $bufer = explode(" ", $bufer);
	  $pos[substr($bufer[0], 4)] = $bufer[1];
	 }
   }while($bufer != "END_MAP");//strpos($bufer, $pos_cont_id) === false &&

  echo "<h2>{$textos['es'][$cont_id]}</h2>";

/*
BEGIN_MAP 27
POS_GENERAL 1864
POS_TIME 2545
POS_VISITOR 13175
POS_DAY 30943
POS_DOMAIN 3433
POS_LOGIN 3929
POS_ROBOT 4084
POS_WORMS 4657
POS_EMAILSENDER 4788
POS_EMAILRECEIVER 4931
POS_SESSION 31604
POS_SIDER 31817
POS_FILETYPES 5066
POS_OS 5375
POS_BROWSER 5533
POS_SCREENSIZE 5968
POS_UNKNOWNREFERER 6042
POS_UNKNOWNREFERERBROWSER 6841
POS_ORIGIN 7325
POS_SEREFERRALS 7474
POS_PAGEREFS 7689
POS_SEARCHWORDS 9872
POS_KEYWORDS 11501
POS_MISC 2208
POS_ERRORS 12400
POS_CLUSTER 3785
POS_SIDER_404 12499
END_MAP
*/

  $array = array();
  $cargar = array($cont_id);
  if($contenidos_p[$cont_id]) $cargar = array_merge($cargar, $contenidos_p[$cont_id]);
  $j = 0;
  foreach($cargar AS $cargar_v)
   {
	fseek($gestor, $pos[$cargar_v]);
	$a = explode(" ", fgets($gestor));
	for($i = 0; $i < $a[1]; $i++)
	 {
	  $bufer = explode(" ", trim(fgets($gestor)));
	  $k = array_shift($bufer);
	  if($contenidos[$_GET['cont']] == 1) $bufer = array_shift($bufer);
	  $array[$j][$k] = $bufer;
	 }
	$j++;
   }
  fclose ($gestor);
 }
else
 echo "<h1>No hay archivo</h1>";


?>



<!-- form action="<?php echo $_SERVER['PHP_SELF'] ?>" style="padding: 0px 0px 0px 0px; margin-top: 0" target="_parent">
<table class="aws_border" border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td>
<table class="aws_data" border="0" cellpadding="1" cellspacing="0" width="100%">
<tr valign="middle"><td class="aws" valign="middle" width="150"><b>Última actualización:</b>&nbsp;</td><td class="aws" valign="middle"><span style="font-size: 12px;">21 Oct 2008 - 12:33</span></td><td align="right" rowspan="2"><a href="http://awstats.sourceforge.net" target="awstatshome"><img src="./img/aws/other/awstats_logo1.png" border="0" alt='Awstats Web Site' title='Awstats Web Site' /></a>
<br /><a href="awstats.pl?month=10&amp;year=2008&amp;config=eltorodepicasso.es&amp;output=main&amp;framename=index&amp;lang=en" target="_parent"><img src="./img/aws/flags/en.png" height="14" border="0" alt='English' title='English' /></a>&nbsp;
<a href="awstats.pl?month=10&amp;year=2008&amp;config=eltorodepicasso.es&amp;output=main&amp;framename=index&amp;lang=fr" target="_parent"><img src="./img/aws/flags/fr.png" height="14" border="0" alt='French' title='French' /></a>&nbsp;
<a href="awstats.pl?month=10&amp;year=2008&amp;config=eltorodepicasso.es&amp;output=main&amp;framename=index&amp;lang=de" target="_parent"><img src="./img/aws/flags/de.png" height="14" border="0" alt='German' title='German' /></a>&nbsp;
<a href="awstats.pl?month=10&amp;year=2008&amp;config=eltorodepicasso.es&amp;output=main&amp;framename=index&amp;lang=it" target="_parent"><img src="./img/aws/flags/it.png" height="14" border="0" alt='Italian' title='Italian' /></a>&nbsp;
<a href="awstats.pl?month=10&amp;year=2008&amp;config=eltorodepicasso.es&amp;output=main&amp;framename=index&amp;lang=nl" target="_parent"><img src="./img/aws/flags/nl.png" height="14" border="0" alt='Dutch' title='Dutch' /></a>&nbsp;
</td></tr>
<tr><td class="aws" valign="middle"><b>Período reportado:</b></td><td class="aws" valign="middle">
<select class="aws_formfield" name="m">
 <option value="01">Ene</option>
 <option value="02">Feb</option>
 <option value="03">Mar</option>
 <option value="04">Abr</option>
 <option value="05">May</option>
 <option value="06">Jun</option>
 <option value="07">Jul</option>
 <option value="08">Ago</option>
 <option value="09">Sep</option>
 <option selected="true" value="10">Oct</option>
 <option value="11">Nov</option>
 <option value="12">Dic</option>
 <option value="all">- Año -</option>
</select>
<select class="aws_formfield" name="year">
<option value="2007">2007</option>
<option selected="true" value="2008">2008</option>
</select>
<input type="hidden" name="output" value="main" />
<input type="hidden" name="config" value="eltorodepicasso.es" />
<input type="hidden" name="lang" value="es" />
<input type="hidden" name="framename" value="index" />
<input type="submit" value=" Aceptar " class="aws_button" /></td></tr>
</table>
</td></tr></table>
</form -->

<?php
if($cont_id == "GENERAL")
 {


echo "<pre>

";

echo "\n\n\n";





print_r($array);
echo "</pre>";

$primer_visita = mktime(0,0,0, $mes, 1, $anyo);
$ultima_visita = mktime(0,0,0, $mes, 1, $anyo);

$paginas = 0;
foreach($array[1] AS $k => $v)
 {
  $paginas += $v[0];
  $hits += $v[1];
 }

?>

<table class="aws_border" border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td class="aws_title" width="70%">Sumario </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2">
<table class="aws_data" border="0" cellpadding="2" cellspacing="0" width="100%">
<tr bgcolor="#ECECEC"><td class="aws"><b>Período reportado</b></td><td class="aws" colspan="5">Mes <?php echo $textos['es']['meses'][$mes]." ".$anyo; ?></td></tr>
<tr bgcolor="#ECECEC"><td class="aws"><b>Primera visita</b></td><td class="aws" colspan="5"><?php echo fecha_formato($array[0]['FirstTime'][0]) ?></td></tr>
<tr bgcolor="#ECECEC"><td class="aws"><b>Última visita</b></td><td class="aws" colspan="5"><?php echo fecha_formato($array[0]['LastTime'][0]) ?></td>
</tr>
<tr><td bgcolor="#CCCCDD">&nbsp;</td><td width="17%" bgcolor="#FF9933">Visitantes distintos</td><td width="17%" bgcolor="#F3F300">Número de visitas</td><td width="17%" bgcolor="#4477DD">Páginas</td><td width="17%" bgcolor="#66F0FF">Hits</td><td width="17%" bgcolor="#339944">Bytes</td></tr>
<tr><td class="aws">Traffic viewed&nbsp;*</td><td><b><?php echo $array[0]['TotalUnique'][0]."</b><br />&nbsp;</td><td><b>{$array[0]['TotalVisits'][0]}</b><br />(".round($array[0]['TotalVisits'][0] / $array[0]['TotalUnique'][0], 2)."&nbsp;Visitas/Visitante)</td><td><b>${paginas}</b><br />(".round($paginas / $array[0]['TotalVisits'][0], 2)."&nbsp;Páginas/Visita)</td><td><b>${hits}</b><br />(".round($hits / $array[0]['TotalVisits'][0], 2)."&nbsp;Hits/Visita)</td><td><b>xxx MB</b><br />(xxx&nbsp;KB"; ?>/Visita)</td></tr>
<tr><td class="aws">Traffic not viewed&nbsp;*</td><td colspan="2">&nbsp;<br />&nbsp;</td>
<td><b>xxxx</b></td><td><b>xxxx</b></td><td><b>xxx MB</b></td></tr>
</table></td></tr></table><span style="font: 11px verdana, arial, helvetica;">* Not viewed traffic includes traffic generated by robots, worms, or replies with special HTTP status codes.</span><br />
<br />

<?php
 }

elseif($cont_id == "TIME")
 {
?>



<!-- table class="aws_border">
  <tr><td class="aws_title" width="70%"><?php echo $textos['es']['TIME'] ?> </td><td class="aws_blank">&nbsp;</td></tr>
  <tr><td colspan="2">
	<table class="aws_data">
	  <tr><td align="center" -->
<?php

$zh_serv = (date("O") / 100);
$zh = (!isset($_GET['zh'])) ? $zh_serv : $_GET['zh'];
$dls = $_GET['dls'];
$desplazamiento = ($zh_serv - date("I") - $zh - $dls);
$sel_zh[$zh] = ' selected="selected"';
$sel_dls[$dls] = ' checked="checked"';
echo "
<p>La hora actual del servidor es ".fecha_formato(date("YmdHi"))." hs. ".date("P").(date("I") ? ' en hora de ahorro de luz diurna' : '')."</p>
<form action=\"/estadisticas\" method=\"get\">
 <input type=\"hidden\" name=\"cont\" value=\"${cont_id}\" />
 <input type=\"hidden\" name=\"periodo\" value=\"${fecha}\" />
Desplazar a <select name=\"zh\">
 <option value=\"-12\"{$sel_zh[-12]}>-12:00</option>
 <option value=\"-11\"{$sel_zh[-11]}>-11:00</option>
 <option value=\"-10\"{$sel_zh[-10]}>-10:00</option>
 <option value=\"-9\"{$sel_zh[-9]}>&nbsp;-9:00</option>
 <option value=\"-8\"{$sel_zh[-8]}>&nbsp;-8:00</option>
 <option value=\"-7\"{$sel_zh[-7]}>&nbsp;-7:00</option>
 <option value=\"-6\"{$sel_zh[-6]}>&nbsp;-6:00</option>
 <option value=\"-5\"{$sel_zh[-5]}>&nbsp;-5:00</option>
 <option value=\"-4\"{$sel_zh[-4]}>&nbsp;-4:00</option>
 <option value=\"-3\"{$sel_zh[-3]}>&nbsp;-3:00</option>
 <option value=\"-2\"{$sel_zh[-2]}>&nbsp;-2:00</option>
 <option value=\"-1\"{$sel_zh[-1]}>&nbsp;-1:00</option>
 <option value=\"0\"{$sel_zh[0]}>&nbsp;UTC</option>
 <option value=\"1\"{$sel_zh[1]}>&nbsp;+1:00</option>
 <option value=\"2\"{$sel_zh[2]}>&nbsp;+2:00</option>
 <option value=\"3\"{$sel_zh[3]}>&nbsp;+3:00</option>
 <option value=\"4\"{$sel_zh[4]}>&nbsp;+4:00</option>
 <option value=\"5\"{$sel_zh[5]}>&nbsp;+5:00</option>
 <option value=\"6\"{$sel_zh[6]}>&nbsp;+6:00</option>
 <option value=\"7\"{$sel_zh[7]}>&nbsp;+7:00</option>
 <option value=\"8\"{$sel_zh[8]}>&nbsp;+8:00</option>
 <option value=\"9\"{$sel_zh[9]}>&nbsp;+9:00</option>
 <option value=\"10\"{$sel_zh[10]}>+10:00</option>
 <option value=\"11\"{$sel_zh[11]}>+11:00</option>
 <option value=\"12\"{$sel_zh[12]}>+12:00</option>
</select> <input type=\"checkbox\" name=\"dls\" id=\"dls\" value=\"1\"{$sel_dls[1]} /> <label for=\"dls\">Ahorro de Luz Diurna</label> <input type=\"submit\" value=\"Aceptar\" />
</form>
<br />";
if($desplazamiento)
 {
  $array2 = $array[0];
  unset($array[0]);
  for($i = 0; $i < 24; $i++)
   {
	$nclave = ($i - $desplazamiento);
	if($nclave < 0) $nclave += 24;
	elseif(($nclave - 24) >= 0) $nclave -= 24;
	$array[0][$nclave] = $array2[$i];
   }
 }

?>
<div class="aws_contenedor">
		<table class="aws_datos">
		  <tr valign="bottom">
<?php
  //$totales = array(0, 0, 0);
  $paginas = array();
  $hits = array();
  $bytes = array();
  foreach($array[0] AS $v)
   {
	$paginas[] = $v[0];
	$hits[] = $v[1];
	$bytes[] = $v[2];
   }
  $max[0] = (100 / max($paginas));
  $max[1] = (181 / max($hits));
  $max[2] = (181 / max($bytes));
  for($i = 0; $i < 24; $i++)
   {
	/*$porc[0] = ($array[0][$i][0] * $totales[0]);
   	$porc[1] = ($array[0][$i][1] * $totales[1]);
   	$porc[2] = ($array[0][$i][2] * $totales[2]);
   	*/
   	$byte_f = byte_format($array[0][$i][2]);
	echo "<td><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"".ceil($array[0][$i][0] * $max[0])."\" width=\"6\" alt=\"Páginas: {$array[0][$i][0]}\" title=\"Páginas: {$array[0][$i][0]}\" /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"".ceil($array[0][$i][1] * $max[1])."\" width=\"6\" alt=\"Hits: {$array[0][$i][1]}\" title=\"Hits: {$array[0][$i][1]}\" /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"".ceil($array[0][$i][2] * $max[2])."\" width=\"6\" alt=\"Bytes: {$byte_f}\" title=\"Bytes: {$byte_f}\" /></td>";
   }
?>
</tr>
<tr><td width="19">0</td><td width="19">1</td><td width="19">2</td><td width="19">3</td><td width="19">4</td><td width="19">5</td><td width="19">6</td><td width="19">7</td><td width="19">8</td><td width="19">9</td><td width="19">10</td><td width="19">11</td><td width="19">12</td><td width="19">13</td><td width="19">14</td><td width="19">15</td><td width="19">16</td><td width="19">17</td><td width="19">18</td><td width="19">19</td><td width="19">20</td><td width="19">21</td><td width="19">22</td><td width="19">23</td></tr>
<tr>
<td><img src="./img/aws/clock/hr1.png" width="10" alt="0:00 - 1:00 am" /></td><td><img src="./img/aws/clock/hr2.png" width="10" alt="1:00 - 2:00 am" /></td><td><img src="./img/aws/clock/hr3.png" width="10" alt="2:00 - 3:00 am" /></td><td><img src="./img/aws/clock/hr4.png" width="10" alt="3:00 - 4:00 am" /></td><td><img src="./img/aws/clock/hr5.png" width="10" alt="4:00 - 5:00 am" /></td><td><img src="./img/aws/clock/hr6.png" width="10" alt="5:00 - 6:00 am" /></td><td><img src="./img/aws/clock/hr7.png" width="10" alt="6:00 - 7:00 am" /></td><td><img src="./img/aws/clock/hr8.png" width="10" alt="7:00 - 8:00 am" /></td><td><img src="./img/aws/clock/hr9.png" width="10" alt="8:00 - 9:00 am" /></td><td><img src="./img/aws/clock/hr10.png" width="10" alt="9:00 - 10:00 am" /></td><td><img src="./img/aws/clock/hr11.png" width="10" alt="10:00 - 11:00 am" /></td><td><img src="./img/aws/clock/hr12.png" width="10" alt="11:00 - 12:00 am" /></td><td><img src="./img/aws/clock/hr1.png" width="10" alt="0:00 - 1:00 pm" /></td><td><img src="./img/aws/clock/hr2.png" width="10" alt="1:00 - 2:00 pm" /></td><td><img src="./img/aws/clock/hr3.png" width="10" alt="2:00 - 3:00 pm" /></td><td><img src="./img/aws/clock/hr4.png" width="10" alt="3:00 - 4:00 pm" /></td><td><img src="./img/aws/clock/hr5.png" width="10" alt="4:00 - 5:00 pm" /></td><td><img src="./img/aws/clock/hr6.png" width="10" alt="5:00 - 6:00 pm" /></td><td><img src="./img/aws/clock/hr7.png" width="10" alt="6:00 - 7:00 pm" /></td><td><img src="./img/aws/clock/hr8.png" width="10" alt="7:00 - 8:00 pm" /></td><td><img src="./img/aws/clock/hr9.png" width="10" alt="8:00 - 9:00 pm" /></td><td><img src="./img/aws/clock/hr10.png" width="10" alt="9:00 - 10:00 pm" /></td><td><img src="./img/aws/clock/hr11.png" width="10" alt="10:00 - 11:00 pm" /></td><td><img src="./img/aws/clock/hr12.png" width="10" alt="11:00 - 12:00 pm" /></td>
</tr>
</table>
<br />
<table width="650"><tr>
<td align="center">

<?php

$j = 12;
$i = 0;
for($h = 0; $h < 2; $h++)
 {
  echo "
<table class=\"aws_datos\">
 <thead>
  <tr><td bgcolor=\"#ECECEC\">Visitas por Horas</td><td bgcolor=\"#4477DD\">Páginas</td><td bgcolor=\"#66F0FF\">Hits</td><td bgcolor=\"#339944\">Bytes</td></tr>
 </thead>
 <tbody>";
  for($i = $i; $i < $j; $i++)
	echo "<tr><td>${i}</td><td>{$array[0][$i][0]}</td><td>{$array[0][$i][1]}</td><td>{$array[0][$i][2]}</td></tr>";
  echo "
 </tbody>
</table>
</td>";
  if($h == 0) echo "<td width=\"10\">&nbsp;</td><td align=\"center\">";
  $j = 24;
 }

?>
  </tr>
</table>

<!-- /td></tr>
</table></td></tr></table -->
</div>
<br />

<?php
 }
elseif($cont_id == "DOMAIN")
 {
  include('leng/paises.es');

?>
<a name="domains">&nbsp;</a><br />

<!-- table class="aws_border">
  <tr><td class="aws_title" width="70%"><?php echo $textos['es'][$cont_id] ?> </td><td class="aws_blank">&nbsp;</td></tr>
  <tr><td colspan="2" -->
	<table class="aws_datos">
	 <thead>
	  <tr bgcolor="#ECECEC"><td width="32">&nbsp;</td><td colspan="2"><?php echo $textos['es'][$cont_id] ?></td><td bgcolor="#4477DD" width="80">Páginas</td><td bgcolor="#66F0FF" width="80">Hits</td><td bgcolor="#339944" width="80">Bytes</td><td>&nbsp;</td></tr>
	 </thead>
	 <tbody>
<?php
  //$totales = array(0, 0, 0);
  $paginas = array();
  $hits = array();
  $bytes = array();
  foreach($array[0] AS $v)
   {
	$paginas[] = $v[0];
	$hits[] = $v[1];
	$bytes[] = $v[2];
   }
  $max[0] = (151 / max($paginas));
  $max[1] = (261 / max($hits));
  $max[2] = (261 / max($bytes));

  foreach($array[0] AS $k => $v)
   {
   	$bytes = byte_format($v[2]);
   	/*
   	$porc[0] = ($v[0] * $max[0]);
   	$porc[1] = ($v[1] * $max[1]);
   	$porc[2] = ($v[2] * $max[2]);
	*/
	echo "
	  <tr><td><img src=\"./img/aws/flags/${k}.png\" height=\"14\" alt=\"${k}\" title=\"${k}\" /></td><td class=\"aws\">{$textos['paises'][$k]}</td><td>${k}</td><td>${v[0]}</td><td>${v[1]}</td><td>${bytes}</td><td class=\"aws_barras_h\"><img src=\"./img/aws/other/hp.png\" width=\"".ceil($v[0] * $max[0])."\" height=\"5\" alt=\"Páginas: {$v[0]}\" title=\"Páginas: {$v[0]}\" /><br />
<img src=\"./img/aws/other/hh.png\" width=\"".ceil($v[1] * $max[1])."\" height=\"5\" alt=\"Hits: {$v[1]}\" title=\"Hits: {$v[1]}\" /><br />
<img src=\"./img/aws/other/hk.png\" width=\"".ceil($v[2] * $max[2])."\" height=\"5\" alt=\"Bytes: ${bytes}\" title=\"Bytes: ${bytes}\" /></td></tr>";
   }

?>
<!-- /table></td></tr -->
</tbody>
</table><br />

<?php
 }

elseif($cont_id == "BROWSER")
 {
  $navegadores_n = array();
  $navegadores_n['msie'] = array("MS Internet Explorer");
  $navegadores_n['firefox'] = array("Firefox");
  $navegadores_n['netscape'] = array("Netscape");
  $navegadores_n['safari'] = array("Safari");
  $navegadores_n['konqueror'] = array("Konqueror");
  $navegadores_n['opera'] = array("Opera");
  $navegadores_n['mozilla'] = array("Mozilla");
  $navegadores_n['Unknown'] = array("Desconocido", 'unknown');
  $navegadores_n['w3m'] = array("W3m", 'notavailable');
  $navegadores_n['nokia'] = array("Nokia Browser (PDA/Phone browser)", 'pdaphone');
  $navegadores_n['dillo'] = array("Dillo");
  $navegadores_n['libwww'] = array("LibWWW", 'notavailable');
  $navegadores_n['k\\-meleon'] = array("K-Meleon", 'kmeleon');
  $navegadores_n['nsplayer'] = array("NetShow Player (media player)");
  $navegadores_n['real'] = array("Real player o compatible (media player)");

  arsort($array[0]);
  $total_hits = (100 / array_sum($array[0]));
  $navegador_grp = array();

  foreach($array[0] AS $k => $v)
   {
	if(strpos($k, "firefox") === 0) { $navegador_grp["firefox"][$k] = $v; unset($array[0][$k]); }
	elseif(strpos($k, "msie") === 0) { $navegador_grp["msie"][$k] = $v; unset($array[0][$k]); }
	elseif(strpos($k, "netscape") === 0) { $navegador_grp["netscape"][$k] = $v; unset($array[0][$k]); }
   }
?>


<table class="aws_border">
<tr><td class="aws_title" width="70%"><?php echo $textos['es']['BROWSER'] ?> </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2">
<table class="aws_data">
  <tr bgcolor="#ECECEC"><th colspan="2">Versiones</th><th bgcolor="#66F0FF" width="80">Hits</th><th bgcolor="#66F0FF" width="80">Porcentaje</th><th>&nbsp;</th></tr>
<?php

foreach($navegador_grp AS $grp => $vers)
 {
  $hits = array_sum($vers);
  $grp_len = strlen($grp);
  echo "<tr bgcolor=\"#F6F6F6\"><td class=\"aws\" colspan=\"2\"><b>{$navegadores_n[$grp][0]}</b></td><td><b>${hits}</b></td><td><b>".round(($hits * $total_hits), 1)." %</b></td><td>&nbsp;</td></tr>";
  foreach($vers AS $vers_k => $vers_v)
   {
   	$porc = round(($vers_v * $total_hits), 1);
	echo "<tr><td><img src=\"./img/aws/browser/${grp}.png\" alt=\"\" title=\"\" /></td><td class=\"aws\">{$navegadores_n[$grp][0]} ".substr($vers_k, $grp_len)."</td><td>${vers_v}</td><td>${porc} %</td><td class=\"aws\"><img src=\"./img/aws/other/hh.png\" width=\"".round($porc * 5.70)."\" height=\"5\" /><br /></td></tr>";
   }
 }
if(count($array[0]))
 {
  $hits = array_sum($array[0]);
  echo "<tr bgcolor=\"#F6F6F6\"><td class=\"aws\" colspan=\"2\"><b>{$textos['es']['otros']}</b></td><td><b>${hits}</b></td><td><b>".round(($hits * $total_hits), 1)." %</b></td><td>&nbsp;</td></tr>";
  foreach($array[0] AS $vers_k => $vers_v)
   {
	$porc = round(($vers_v * $total_hits), 1);
	echo "<tr><td><img src=\"./img/aws/browser/".($navegadores_n[$vers_k][1] ? $navegadores_n[$vers_k][1] : $vers_k).".png\" alt=\"\" title=\"\" /></td><td class=\"aws\">".($navegadores_n[$vers_k] ? $navegadores_n[$vers_k][0] : $vers_k)."</td><td>${vers_v}</td><td>${porc} %</td><td class=\"aws\"><img src=\"./img/aws/other/hh.png\" width=\"".round($porc * 5.70)."\" height=\"5\" /><br /></td></tr>";
   }
 }
if(count($array[1]))
 {
  echo "<tr bgcolor=\"#F6F6F6\"><td class=\"aws\" colspan=\"5\"><b>{$textos['es']['desconocido']}</b></td></tr>";
  foreach($array[1] AS $vers_k => $vers_v)
   {
	$porc = round(($vers_v * $total_hits), 1);
	echo "<tr><td> </td><td class=\"aws\" colspan=\"4\">${vers_k}</td></tr>";
   }
 }


?>
</table></td></tr>
</table><br />


<?php
 }

elseif($cont_id == "ROBOT")
 {
if($array[0])//if array
 {
  $robots = array();
  $robots['slurp'] = array("Yahoo Slurp", "http://help.yahoo.com/help/us/ysearch/slurp/");
  $robots['googlebot'] = array("Googlebot", "http://www.google.com/bot.html");
  $robots['msnbot'] = array("MSNBot", "http://search.msn.com/msnbot.htm");
  $robots['robot'] = array("Robot desconocido (identificado por 'robot')");
  $robots['spider'] = array("Robot desconocido (identificado por 'spider')");
  $robots['unknown'] = array("Robot desconocido (identificado por consultar en 'robots.txt')");
  $robots['msnbot\-media'] = array("MSNBot-media", "http://search.msn.com/msnbot.htm");
  $robots['ia_archiver'] = array("Alexa (IA Archiver)", "http://www.alexa.com/");
  $robots['yahoo!\sslurp\schina'] = array("Yahoo! Slurp China", "http://misc.yahoo.com.cn/help.html");
  $robots['crawl'] = array("Robot desconocido (identificado por 'crawl')");
  $robots['jeeves'] = array("Ask", "http://sp.ask.com/docs/about/tech_crawling.html");
  $robots['\wbot[\/\-]'] = array("Robot desconocido (identificado por 'bot/' o 'bot-')");

?>

	<table class="aws_datos">
	  <tr bgcolor="#ECECEC"><th><?php echo count($array[0]) ?> Visitas de Robots</th><th bgcolor="#66F0FF" width="80">Hits</th><th bgcolor="#339944" width="80">Bytes</th><th width="120">Última visita</th></tr>
<?php
  foreach($array[0] AS $k => $v)
   {
	echo "<tr><td class=\"aws\">".($robots[$k] ? ($robots[$k][1] ? "<a href=\"{$robots[$k][1]}\" title=\"Página del Bot [nueva ventana]\" target=\"_blank\">{$robots[$k][0]}</a>" : $robots[$k][0]) : $k)."</td><td>".($v[0] - $v[3]).($v[3] ? '+'.$v[3] : '')."</td><td>".byte_format($v[1])."</td><td>".fecha_formato($v[2])."</td></tr>";
   }

?>
	</table>
<br />





<?php
   }//if array
 }
elseif($cont_id == 'OS')
 {

  $oss_n = array();
  $oss_n['win'] = array("Windows");
  $oss_n['linux'] = array("Linux");
  $oss_n['mac'] = array("Macintosh");
  $oss_n['winxp'] = array("Windows XP", "http://www.microsoft.com/windowsxp/");
  $oss_n['winlong'] = array("Windows Vista", "http://www.microsoft.com/windows/");
  $oss_n['win2003'] = array("Windows 2003", "http://www.microsoft.com/windowsserver2003/");
  $oss_n['win2000'] = array("Windows 2000", "http://www.microsoft.com/windows2000/");
  $oss_n['win98'] = array("Windows 98", "http://www.microsoft.com/windows98/");
  $oss_n['win95'] = array("Windows 95", "http://www.microsoft.com/windows95/");
  $oss_n['winnt'] = array("Windows NT", "http://www.microsoft.com/ntworkstation/");
  $oss_n['winme'] = array("Windows ME", "http://www.microsoft.com/windowsme/");
  $oss_n['wince'] = array("Windows CE", "http://www.microsoft.com/windowsmobile/");
  $oss_n['win16'] = array("Windows 3.xx", "http://www.microsoft.com/");

  $oss_n['macosx'] = array("Mac OS X", "http://www.apple.com/macosx/");
  $oss_n['macintosh'] = array("Mac OS", "http://www.apple.com/");

  $oss_n['linuxubuntu'] = array("Ubuntu", "http://ubuntu.com");
  $oss_n['linuxfedora'] = array("Fedora", "http://fedora.redhat.com/");
  $oss_n['symbian'] = array("Symbian OS", "http://www.symbian.com/");
  $oss_n['linuxsuse'] = array("Suse", "http://www.novell.com/linux/suse/");
  $oss_n['sunos'] = array("Sun Solaris", "http://www.sun.com/software/solaris/");
  $oss_n['linuxdebian'] = array("Debian", "http://www.debian.org/");
  $oss_n['unix'] = array("Sistema Unix desconocido");

  $oss_n['psp'] = array("Sony PlayStation Portable", "http://www.playstation.jp/psp/");

  $oss_n['Unknown'] = array($textos['es']['desconocido']);
  $oss_n['otros'] = array($textos['es']['otros']);

  arsort($array[0]);
  $total_hits = (100 / array_sum($array[0]));
  $max_hits = (261 / max($array[0]));
  $oss_grp = array();

  foreach($array[0] AS $k => $v)
   {
	if(strpos($k, "win") === 0) { $oss_grp["win"][$k] = $v; }
	elseif(strpos($k, "linux") === 0) { $oss_grp["linux"][$k] = $v; }
	elseif(strpos($k, "mac") === 0) { $oss_grp["mac"][$k] = $v; }
	else { $oss_grp["otros"][$k] = $v; }
   }

?>

<div id="aws_contenedor">
	<table class="aws_data">
	  <tr bgcolor="#ECECEC"><th colspan="2">Versiones</th><th bgcolor="#66F0FF" width="80">Hits</th><th bgcolor="#66F0FF" width="80">Porcentaje</th><th>&nbsp;</th></tr>
<?php
foreach($oss_grp AS $grp => $vers)
 {
  $hits = array_sum($vers);
  echo "<tr bgcolor=\"#F6F6F6\"><td class=\"aws\" colspan=\"2\"><b>{$oss_n[$grp][0]}</b></td><td><b>${hits}</b></td><td><b>".number_format(($hits * $total_hits), 1)." %</b></td><td>&nbsp;</td></tr>";
  foreach($vers AS $vers_k => $vers_v)
   {
   	$porc = round(($vers_v * $total_hits), 1);
	echo "<tr><td><img src=\"./img/aws/os/${vers_k}.png\" alt=\"\" title=\"\" /></td><td class=\"aws\">".($oss_n[$vers_k] ? ($oss_n[$vers_k][1] ? "<a href=\"{$oss_n[$vers_k][1]}\" target=\"_blank\">{$oss_n[$vers_k][0]}</a>" : $oss_n[$vers_k][0]) : $vers_k)."</td><td>${vers_v}</td><td>${porc} %</td><td class=\"aws\"><img src=\"./img/aws/other/hh.png\" width=\"".ceil($vers_v * $max_hits)."\" height=\"5\" /><br /></td></tr>";
   }
 }

?>
	</table>
<br />

<?php
  if($total = count($array[1]))
   {
?>
<table class="aws_border">
  <tr><td class="aws_title">Sistema Operativo desconocido (campo de referencia) </td><td class="aws_blank">&nbsp;</td></tr>
  <tr><td colspan="2">
	<table class="aws_data">
<?php
	echo "<tr bgcolor=\"#ECECEC\"><th>User agent (${total})</th><th>Última visita</th></tr>";
	foreach($array[1] AS $k => $v)
	  echo "<tr><td class=\"aws\">${k}</td><td>".fecha_formato($v)."</td></tr>";
?>
	</table>
   </td></tr></table><br />

<?php
   }
  echo "</div>";
 }
elseif($cont_id == "SEREFERRALS")
 {

if($array[0])//if array
 {
  $motores = array();
  $motores['google'] = array("Google", "http://www.google.com/");
  $motores['live'] = array("Windows Live", "http://www.live.com/");
  $motores['google_cache'] = array("Google (cache)", "http://www.google.com/help/features.html#cached");
  $motores['google_image'] = array("Google (Imágenes)", "http://images.google.com/");
  $motores['msn'] = array("MSN Search", "http://search.msn.com/");
  $motores['yahoo'] = array("Yahoo!", "http://www.yahoo.com/");
  $motores['seznam'] = array("Seznam");
  $motores['aolfr'] = array("AOL (fr)");
  $motores['dogpile'] = array("Dogpile", "http://www.dogpile.com/");
  $motores['altavista'] = array("AltaVista", "http://www.altavista.com/");
  $motores['search'] = array("Buscador desconocido");


  foreach($array[0] AS $llave => $fila)
   {
	$paginas[$llave] = $fila[0];
	$hits[$llave] = $fila[1];
   }
  $totales = array((100 / array_sum($paginas)), (100 / array_sum($hits)));
  array_multisort($paginas, SORT_DESC, $hits, SORT_DESC, $array[0]);

?>

<table class="aws_border">
<tr><td class="aws_title" width="70%">Enlaces desde algún motor de búsqueda </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2">
<table class="aws_data">
<tr bgcolor="#ECECEC"><th><?php echo count($array[0]) ?> Enlaces desde buscadores diferentes</th><th bgcolor="#4477DD" width="80">Páginas</th><th bgcolor="#4477DD" width="80">Porcentaje</th><th bgcolor="#66F0FF" width="80">Hits</th><th bgcolor="#66F0FF" width="80">Porcentaje</th></tr>
<?php
  foreach($array[0] AS $k => $v)
   {
	$porc[0] = number_format(($v[0] * $totales[0]), 1);
	$porc[1] = number_format(($v[1] * $totales[1]), 1);
	echo "<tr><td class=\"aws\">".($motores[$k] ? ($motores[$k][1] ? "<a href=\"{$motores[$k][1]}\" target=\"_blank\">{$motores[$k][0]}</a>" : $motores[$k][0]) : $k)."</td><td>{$v[0]}</td><td>{$porc[0]} %</td><td>{$v[1]}</td><td>{$porc[1]} %</td></tr>";
   }
?>
</table></td></tr></table><br />


<table class="aws_border">
<tr><td class="aws_title" width="70%">Enlaces desde páginas externas (exeptuando motores de búsqueda) </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2">
<table class="aws_data">
<tr bgcolor="#ECECEC"><th>Total: <?php echo count($array[1]) ?> páginas diferentes</th><th bgcolor="#4477DD" width="80">Páginas</th><th bgcolor="#4477DD" width="80">Porcentaje</th><th bgcolor="#66F0FF" width="80">Hits</th><th bgcolor="#66F0FF" width="80">Porcentaje</th></tr>
<?php

  $paginas = array();
  $hits = array();
  foreach($array[1] AS $llave => $fila)
   {
	$paginas[$llave] = $fila[0];
	$hits[$llave] = $fila[1];
   }
  $totales = array((100 / array_sum($paginas)), (100 / array_sum($hits)));
  array_multisort($paginas, SORT_DESC, $hits, SORT_DESC, $array[1]);

  foreach($array[1] AS $k => $v)
   {
	$porc[0] = number_format(($v[0] * $totales[0]), 1);
	$porc[1] = number_format(($v[1] * $totales[1]), 1);
	echo "<tr><td class=\"aws\"><a href=\"".htmlspecialchars($k)."\" target=\"_blank\">".htmlspecialchars($k)."</a></td><td>".($v[0] ? "{$v[0]}</td><td>{$porc[0]} %" : " </td><td> ")."</td><td>{$v[1]}</td><td>{$porc[1]} %</td></tr>\n";
   }
?>
</table></td></tr></table><br />


<?php
   }// if array
 }
elseif($cont_id == 'SIDER')
 {
?>

<!-- table class="aws_border">
<tr><td class="aws_title" width="70%">Páginas/URLs </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2" -->
<div id="aws_contenedor">
	<table class="aws_datos">
	 <thead>
	  <tr bgcolor="#ECECEC"><td><?php echo count($array[0]); ?> páginas diferentes</td><td bgcolor="#4477DD">Accesos</td><td bgcolor="#339944">Tamaño medio</td><td bgcolor="#CEC2E8">Página de entrada</td><td bgcolor="#C1B2E2">Salida</td><td>&nbsp;</td></tr>
	 </thead>
	 <tbody>
<?php

  $accesos = array();
  $tamanyo = array();
  $entrada = array();
  $salida = array();
  foreach($array[0] AS $llave => $fila)
   {
	$accesos[$llave] = $fila[0];
	$tamanyo[$llave] = $fila[1];
	$entrada[$llave] = $fila[2];
	$salida[$llave] = $fila[3];
   }
  $totales = array((950 / array_sum($accesos)), (3.6 / array_sum($tamanyo)), (950 / array_sum($entrada)), (950 / array_sum($salida)));
  array_multisort($accesos, SORT_DESC, $array[0]);

  $max[0] = (261 / max($accesos));
  $max[1] = (261 / max($tamanyo));
  $max[2] = (261 / max($accesos));
  $max[3] = (261 / max($accesos));

	foreach($array[0] AS $k => $v)
	 {
	 	$tam = ($v[1] / $v[0]);
	 	$porc[0] = round(($v[0] * $max[0]));
	 	$porc[1] = round(($v[1] * $max[1]));
	 	$porc[2] = round(($v[2] * $max[2]));
	 	$porc[3] = round(($v[3] * $max[3]));
	  echo "<tr><td class=\"aws\"><a href=\"http://".DOMINIO."{$k}\" target=\"url\">{$k}</a></td><td>{$v[0]}</td><td>".byte_format($tam)."</td><td>{$v[2]}</td><td>{$v[3]}</td><td class=\"aws_barras_h\"><img src=\"./img/aws/other/hp.png\" width=\"".ceil($v[0] * $max[0])."\" height=\"4\" /><br /><img src=\"./img/aws/other/hk.png\" width=\"".ceil($v[1] * $max[1])."\" title=\"(({$v[1]} / {$v[0]}) * ((261 / ".max($tamanyo).") / {$v[0]}))\" height=\"4\" /><br /><img src=\"./img/aws/other/he.png\" width=\"".ceil($v[2] * $max[2])."\" height=\"4\" /><br /><img src=\"./img/aws/other/hx.png\" width=\"".ceil($v[3] * $max[3])."\" height=\"4\" /></td></tr>";
	 }
?>
	 </tbody>
	</table>
</div><br />

<?php
 }
elseif($cont_id == 'SEARCHWORDS')
 {
  echo '<div id="aws_contenedor">';
  if($array[0])
   {
?>

<h4>Buscadores frases</h4>

<table class="aws_datos">
<tr bgcolor="#ECECEC"><th><?php echo count($array[0]) ?> diferentes palabras clave</th><th bgcolor="#8888DD" width="80">Búsquedas</th><th bgcolor="#8888DD" width="80">Porcentaje</th></tr>
<?php

	arsort($array[0]);
	$total = (100 / array_sum($array[0]));
	foreach($array[0] AS $k => $v)
	 {
	  echo "<tr><td class=\"aws\">".htmlspecialchars(urldecode($k))."</td><td>${v}</td><td>".number_format(($v * $total), 1)." %</td></tr>";
	 }
?>
</table>
<br />

<?php

 }
if($array[1])
 {
?>

<h4>Buscadores palabras clave</h4>
<table class="aws_datos">
 <thead>
  <tr bgcolor="#ECECEC"><th><?php echo count($array[1]) ?> Palabra clave</th><th bgcolor="#8888DD" width="80">Búsquedas</th><th bgcolor="#8888DD" width="80">Porcentaje</th></tr>
 </thead>
 <tbody>
<?php

	arsort($array[1]);
	$total = (100 / array_sum($array[1]));
	foreach($array[1] AS $k => $v)
	 {
	  echo "<tr><td class=\"aws\">".htmlspecialchars(urldecode($k))."</td><td>${v}</td><td>".number_format(($v * $total), 1)." %</td></tr>";
	 }
?>
 </tbody>
</table>

<?php
   }
  echo '</div>';

 }
elseif($cont_id == 'DAY')
 {

?>

<!-- table class="aws_border" border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr><td class="aws_title" width="70%">Días del mes </td><td class="aws_blank">&nbsp;</td></tr>
  <tr><td colspan="2" -->
  <h4>Días del mes</h4>
	<!-- table class="aws_data" border="1" cellpadding="2" cellspacing="0" width="100%">
	  <tr><td align="center" -->
<div class="aws_contenedor">
		<table>
		  <tr valign="bottom">
<?php

$ultima_fecha_mes = date(t, $time);
  $paginas = array();
  $hits = array();
  $transf = array();
  $visitas = array();
  foreach($array[0] AS $llave => $fila)
   {
	$paginas[$llave] = $fila[0];
	$hits[$llave] = $fila[1];
	$transf[$llave] = $fila[2];
	$visitas[$llave] = $fila[3];
   }

  $max[0] = (88 / max($paginas));
  $max[1] = (181 / max($hits));
  $max[2] = (181 / max($transf));
  $max[3] = (181 / max($visitas));
  $totales = array(array_sum($paginas), array_sum($hits), array_sum($transf), array_sum($visitas));


for($fecha = 1, $dia_semana = date(w, $time); $fecha <= $ultima_fecha_mes; $fecha++, $dia_semana++)
 {
  if($v = $array[0][date("Ymd", mktime(0,0,0, $mes, $fecha, $anyo))])
   {
	//$porc[0] = round(($v[0] * 100 / $totales[0]), 2);
	//$porc[1] = round(($v[1] * 100 / $totales[1]), 2);
	//$porc[2] = round(($v[2] * 100 / $totales[2]), 2);
	//$porc[3] = round(($v[3] * 100 / $totales[3]), 2);
	echo "<td><img align=\"bottom\" src=\"./img/aws/other/vv.png\" height=\"".ceil($v[3] * $max[3])."\" width=\"4\" alt=\"Número de visitas: {$v[3]}\" title=\"Número de visitas: {$v[3]}\" /><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"".ceil($v[0] * $max[0])."\" width=\"4\" alt='Páginas: {$v[0]}' title='Páginas: {$v[0]}' /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"".ceil($v[1] * $max[1])."\" width=\"4\" alt='Hits: {$v[1]}' title='Hits: {$v[1]}' /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"".ceil($v[2] * $max[2])."\" width=\"4\" alt='Bytes: {$v[2]}' title='Bytes: {$v[2]}' /></td>";
   }
  else
   {
    $v = array(0, 0, 0, 0);
    echo "<td><img align=\"bottom\" src=\"./img/aws/other/vv.png\" height=\"1\" width=\"4\" alt=\"\" /><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"1\" width=\"4\" alt=\"\" /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"1\" width=\"4\" alt=\"\" /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"1\" width=\"4\" alt=\"\" /></td>";
   }
 }

$tot_fechas = count($array[0]);
$media[0] = round(($totales[0] / $tot_fechas), 2);
$media[1] = round(($totales[1] / $tot_fechas), 2);
$media[2] = ($totales[2] / $tot_fechas);
$media[3] = round(($totales[3] / $tot_fechas), 2);
$byte = byte_format($media[2]);
echo "<td>&nbsp;</td><td><img align=\"bottom\" src=\"./img/aws/other/vv.png\" height=\"".ceil(($media[3] * $max[3]))."\" width=\"4\" alt='Número de visitas: {$media[3]}' title='Número de visitas: {$media[3]}' /><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"".ceil(($media[0] * $max[0]))."\" width=\"4\" alt='Páginas: {$media[0]}' title='Páginas: {$media[0]}' /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"".ceil(($media[1] * $max[1]))."\" width=\"4\" alt='Hits: {$media[1]}' title='Hits: {$media[1]}' /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"".ceil(($media[2] * $max[2]))."\" width=\"4\" alt='Bytes: {$byte}' title='Bytes: {$byte}' /></td>";
?>
</tr>
<tr valign="middle">
<?php



//$v = reset($array[0]);
for($fecha = 1, $dia_semana = date(w, $time); $fecha <= $ultima_fecha_mes; $fecha++, $dia_semana++)
 {
  if(($dia_semana % 7) == 0) $dia_semana = 0;
  echo "<td".(($dia_semana == 0 || $dia_semana == 6) ? ' bgcolor="#EAEAEA"' : '').">${fecha} <br /><span style=\"font-size: 8px;\">{$textos['es']['meses'][$mes]}</span></td>";
  //$v = next($array[0]);
 }
?>
<td>&nbsp;</td><td valign="middle">Media</td>
</tr>
</table>
<br />
<table class="aws_datos">
 <thead>
  <tr><td bgcolor="#ECECEC">Día</td><td bgcolor="#F3F300">Número de visitas</td><td bgcolor="#4477DD">Páginas</td><td bgcolor="#66F0FF">Hits</td><td bgcolor="#339944">Bytes</td></tr>
 </thead>
<tbody>
<?php




//$dias_semana = array();
$paginas_semana = array(0, 0, 0, 0, 0, 0, 0);
$semana_t = array(0, 0, 0, 0, 0, 0, 0);
$hits_semana = array(0, 0, 0, 0, 0, 0, 0);
$bytes_semana = array(0, 0, 0, 0, 0, 0, 0);

//$v = reset($array[0]);
for($fecha = 1, $dia_semana = date(w, $time); $fecha <= $ultima_fecha_mes; $fecha++, $dia_semana++)
 {
  if($dia_semana == 7) $dia_semana = 0;
  echo "<tr".(($dia_semana == 0 || $dia_semana == 6) ? ' bgcolor="#EAEAEA"' : '')."><td>${fecha} {$textos['es']['meses'][$mes]} ${anyo}</td><td>";
  if($v = $array[0][date("Ymd", mktime(0,0,0, $mes, $fecha, $anyo))])
   {
	$paginas_semana[$dia_semana] += $v[0];
	$semana_t[$dia_semana]++;
	$hits_semana[$dia_semana] += $v[1];
	$bytes_semana[$dia_semana] += $v[2];
	echo "{$v[3]}</td><td>{$v[0]}</td><td>{$v[1]}</td><td>".byte_format($v[2]);
   }
  else
	echo "0</td><td>0</td><td>0</td><td>0";
  echo "</td></tr>";
  //$v = next($array[0]);
 }



echo "
<tr bgcolor=\"#ECECEC\"><td>Media</td><td>{$media[3]}</td><td>{$media[0]}</td><td>{$media[1]}</td><td>${byte}</td></tr>
<tr bgcolor=\"#ECECEC\"><td>Total</td><td>{$totales[3]}</td><td>{$totales[0]}</td><td>{$totales[1]}</td><td>".byte_format($totales[2])."</td></tr>";


?>
		 </tbody>
		</table>
</div>
<br />
<!-- /td></tr>
	</table -->
   <!-- /td></tr>
</table -->
<br />


<h4>Días de la semana</h4>
<!-- table class="aws_border" border="0" cellpadding="2" cellspacing="0">
<tr><td class="aws_title" width="70%">Días de la semana </td><td class="aws_blank">&nbsp;</td></tr>
<tr><td colspan="2">
<table class="aws_data" border="1" cellpadding="2" cellspacing="0">
<tr><td align="center" -->
<div class="aws_contenedor">
<table>
<tr valign="bottom">

<?php


for($d = 0; $d < 7; $d++)
 {
  if($semana_t[$d])
   {
    $prom_pags[$d] = round(($paginas_semana[$d] / $semana_t[$d]), 2);
    $prom_hits[$d] = round(($hits_semana[$d] / $semana_t[$d]), 2);
    $prom_bytes[$d] = ($bytes_semana[$d] / $semana_t[$d]);
   }
 }
	$max[0] = (92 / max($prom_pags));
	$max[1] = (181 / max($prom_hits));
	$max[2] = (181 / max($prom_bytes));

$bytes = array();
for($d = 0; $d < 7; $d++)
 {
  if($semana_t[$d])
   {
    $bytes[$d] = byte_format($prom_bytes[$d]);
	echo "<td valign=\"bottom\"><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"".ceil(($prom_pags[$d] * $max[0]))."\" width=\"6\" alt='Páginas: {$prom_pags[$d]}' title='Páginas: {$prom_pags[$d]}' /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"".ceil(($prom_hits[$d] * $max[1]))."\" width=\"6\" alt='Hits: {$prom_hits[$d]}' title='Hits: {$prom_hits[$d]}' /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"".ceil(($prom_bytes[$d] * $max[2]))."\" width=\"6\" alt='Bytes: {$bytes[$d]}' title='Bytes: {$bytes[$d]}' /></td>";
   }
  else
    echo "<td valign=\"bottom\"><img align=\"bottom\" src=\"./img/aws/other/vp.png\" height=\"1\" width=\"6\" alt=\"\" /><img align=\"bottom\" src=\"./img/aws/other/vh.png\" height=\"1\" width=\"6\" alt=\"\" /><img align=\"bottom\" src=\"./img/aws/other/vk.png\" height=\"1\" width=\"6\" alt=\"\" /></td>";
  //echo round(($paginas_semana[$d] / $semana_t[$d]), 2)."</td><td>".round(($hits_semana[$d] / $semana_t[$d]), 2)."</td><td>".byte_format(($bytes_semana[$d] / $semana_t[$d]))."</td></tr>";
 }

?>
</tr>

<tr>
<td bgcolor="#EAEAEA">Dom</td><td>Lun</td><td>Mar</td><td>Mie</td><td>Jue</td><td>Vie</td><td bgcolor="#EAEAEA">Sab</td></tr>
</table>
<br />
<table class="aws_datos">
 <thead>
  <tr><td bgcolor="#ECECEC">Día</td><td bgcolor="#4477DD">Páginas</td><td bgcolor="#66F0FF">Hits</td><td bgcolor="#339944">Bytes</td></tr>
 </thead>
 <tbody>
<?php

for($d = 0; $d < 7; $d++)
 {
  echo "<tr ".(($d == 0 || $d == 6) ? ' bgcolor="#EAEAEA"' : '')."><td>{$textos['es']['dias'][$d]}</td><td>";
  if($semana_t[$d]) echo "{$prom_pags[$d]}</td><td>{$prom_hits[$d]}</td><td>".byte_format(($bytes_semana[$d] / $semana_t[$d]))."</td></tr>";
  else echo "0</td><td>0</td><td>0</td></tr>";
 }
?>
 </tbody>
</table>
</div>
<br />
	   <!-- /td></tr>
	</table>
   </td></tr>
</table -->
<br />

<?php
 }

include('inc/iapie.php');

?>