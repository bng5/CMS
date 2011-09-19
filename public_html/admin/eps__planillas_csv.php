<?php

include('inc/iniciar.php');
require('inc/ad_sesiones.php');

$usuario = $_GET['usuario'];

if($planilla = EPSModelo_UsuariosUltPlanilla::Listado($usuario))
 {
  $planillaItems = $planilla->getIterator();
  $usuarioObj = Modelo_Usuarios::getPorId($usuario);
  $fecha = $planilla->fecha_modificado ? $planilla->fecha_modificado : $planilla->fecha_agregado;
  $charsets = array(1 => 'UTF-8', 2 => 'ISO-8859-15', 3 => 'Windows-1252');
  $charset = $charsets[$_GET['charset']] ? (int) $_GET['charset'] : 1;
  //header("Content-Type: text/plain; charset=".$charsets[$charset]);
  header("Content-Type: text/csv; charset=".$charsets[$charset]);
  header("Content-Disposition: attachment; filename=EPSEurope_".$usuarioObj->usuario."_".date('Y-m-d', $fecha).".csv");


  if($_GET['recordar_pref'])
   {
    $db = DB::instancia();
    $prefs = $db->query("SELECT COUNT(*) FROM eps__usuarios_pref_planillas WHERE usuario_id = ".$_GET['usuario']." LIMIT 1");
    if($prefs->fetchColumn() == 1)
      $guardar_prefs = $db->prepare("UPDATE eps__usuarios_pref_planillas SET separador = :separador, charset = :charset WHERE usuario_id = :usuario LIMIT 1");
    else
      $guardar_prefs = $db->prepare("INSERT INTO eps__usuarios_pref_planillas (usuario_id, separador, charset) VALUES (:usuario, :separador, :charset)");
    $guardar_prefs->bindValue(':usuario', $_GET['usuario'], PDO::PARAM_INT);
    $guardar_prefs->bindValue(':separador', $_GET['separador'], PDO::PARAM_INT);
    $guardar_prefs->bindValue(':charset', $_GET['charset'], PDO::PARAM_INT);
    $guardar_prefs->execute();
   }

  function aIso(&$v, $k)
   {
    global $charsets, $charset;
    //$v = '__'.utf8_decode($v);
    $v = iconv("UTF-8", $charsets[$charset], $v);
   }

  function fila($arr)
   {
    global $separador, $charset;
    if($charset != 1)
      array_walk($arr, 'aIso');
    return '"'.implode('"'.$separador.'"', $arr).'"
';
   }

  function escaparComillas($txt)
   {
    return str_replace("\"", "\"\"", $txt);
   }

  $delimiter = array(1 => ",", 2 => ";", 3 => "\t", 4 => " ");
  $separador = $delimiter[$_GET['separador']] ? $delimiter[$_GET['separador']] : current($delimiter);
  if($planilla->titulo)
   {
    echo fila(array($planilla->titulo, "", "", "", "", ""));
   }

  $tipo = 0;
  $tipos = array(1 => array("Tinta", "ml"), 2 => array("Tóner", "Págs."));
  foreach($planillaItems AS $item)
   {
	if($item->tipo != $tipo)
	 {
	  $tipo = $item->tipo;//($insumo_tolower == 'tinta') ? 1 : 2;
	  echo fila(array("", "", "", "", "", ""));
      echo fila(array("Marca", "Tipus", $tipos[$tipo][0], "Rendimiento (".$tipos[$tipo][1].")", "Re Manufacturado", "Original"));
	 }
	$fila = array(escaparComillas($item->marca), escaparComillas($item->modelo), escaparComillas($item->insumo), $item->getRendimiento(), '€ '.$item->getPrecioReman(), '€ '.$item->getPrecioNuevo());
	echo fila($fila);
   }
 }
else
 {
  echo '
<div class="error"><p>No fue posible abrir correctamente el documento.</p></div>';
 }

?>