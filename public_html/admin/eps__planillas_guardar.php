<?php

header("Content-Type: text/plain; charset=utf-8");
include('inc/iniciar.php');
require('inc/ad_sesiones.php');

$usuario_id = $_POST['usuario'];
$id = $_POST['planilla'];

if(empty($id))
 {
  $DB = DB::Conectar();
  $DB->exec("INSERT INTO `eps__planillas` (`fecha`, `usuario_id`) VALUES (".time().", ".$usuario_id.")");
  $id = $DB->lastInsertId();
 }

$planilla = new EPSModelo_UsuariosPlanilla($id, $usuario_id);
$planilla->setTitulo($_POST['titulo']);
$planilla->prepararConsultas();

for($i = 0; $i < count($_POST['id']); $i++)
 {
  $item = new EPS_PlanillaItem;
  $item->id = $_POST['id'][$i];
  $item->marca = $_POST['marca'][$i];
  $item->modelo = $_POST['modelo'][$i];
  $item->insumo = $_POST['insumo'][$i];
  $item->tipo = $_POST['tipo'][$i];
  $item->set_parseRendimiento($_POST['rendimiento'][$i]);
  $item->set_parsePrecio('precio_reman', $_POST['precio_reman'][$i]);
  $item->set_parsePrecio('precio_nuevo', $_POST['precio_nuevo'][$i]);
  $planilla->agregar($item);
 }

if(isset($_POST['borrar']) && is_array($_POST['borrar']))
 {
  foreach($_POST['borrar'] AS $item_id)
    $planilla->borrar($item_id);
 }

header("Location: /eps__planillas?usuario=".$usuario_id);
?>
