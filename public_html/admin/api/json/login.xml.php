<?php

require_once('inc/iniciar.php');

$mensajes = array(
'exito' => array(
	'acceder' => "Acceso aceptado",
	'salir' => "Su sesión ha sido cerrada satisfactoriamente",
	),
'errores' => array(
	1 => "Campo requerido", // El campo no puede estar vacio
    2 => "Tipo no válido", // Se esperaba otro tipo de dato
    3 => "Valor incorrecto", // Se esperaba otro valor
    4 => "El campo no alcanza la cantidad de caracteres mínima",
    5 => "El campo excede la cantidad de caracteres máxima",
    //6 => "Campo ignorado", // No se esperaba recibir este campo
   ),
'mensajes' => array(
	1 => "Petición aceptada",
	2 => "Error interno del servidor",
	3 => "La petición no pudo ser interpretada",
	4 => "Sin cambios",
	5 => "Debe completar los campos obligatorios",
	6 => "Existen datos con tipos incorrectos",
	7 => "Existen datos incorrectos",
	8 => "No es posible completar su solicitud",
   )
);

//if($_POST['usuario'] && $_POST['clave'])
// {
$login = new Login($_POST['cuenta'], $_POST['usuario'], $_POST['clave']);

$http_status = $login->respuesta->exito ? 200 : 401;
$suceso = $login->respuesta;
$http_accept = explode(",", $_SERVER['HTTP_ACCEPT']);
$http_accept = trim($http_accept[0]);

if($http_accept == 'application/json')
 {
  if($suceso->exito == false && $suceso->errores)
   {
    foreach($suceso->errores AS $k => $v)
      $suceso->errores->$k = array('cod' => $v, 'desc' => $mensajes['errores'][$v]);
   }
  if($suceso->mensajes)
   {
    //$mensajes_k = $suceso->mensajes;
    //unset($suceso->mensajes);
    foreach($suceso->mensajes AS $k => $v)
     {
      if($v == 1)
        $suceso->mensajes[$k] = array('cod' => 1, 'desc' => $mensajes['exito'][$_POST['cuenta']]);
	  else
	    $suceso->mensajes[$k] = array('cod' => $v, 'desc' => $mensajes['mensajes'][$v]);
     }
   }
  header("Content-Type: application/json; charset=UTF-8", true, $http_status);
  echo json_encode($suceso);
 }
elseif($http_accept == 'application/xml')
 {


  header("Content-Type: application/xml; charset=UTF-8", true, $http_status);
  $doc = new DOMDocument('1.0', 'utf-8');
  $doc->formatOutput = true;
  $root = $doc->createElement('acceso');
  $root = $doc->appendChild($root);
  //$root->setAttribute('xmlns', 'http://'.DOMINIO.'/login');
  //$schemaLocation = $doc->createAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation');
  //$schemaLocation->value = 'http://'.DOMINIO.'/login /api/xsd/login';
  //$root->appendChild($schemaLocation);
/*
 * xmlns="http://www.w3schools.com"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.w3schools.com note.xsd"
 */


  $root->appendChild($doc->createElement('exito', ($suceso->exito ? 'true' : 'false')));
  if($suceso->exito == false && $suceso->errores)
   {
	$errores = $doc->createElement('errores');
	$errores = $root->appendChild($errores);
    foreach($suceso->errores AS $k => $v)
	 {
      //$suceso->errores->$k = array('cod' => $v, 'desc' => $mensajes['errores'][$v]);
	  $error = $errores->appendChild($doc->createElement($k));
	  $error->appendChild($doc->createElement('cod', $v));
	  $error->appendChild($doc->createElement('desc', $mensajes['errores'][$v]));
	  //$error->setAttribute('cod', $v);
	 }
   }
  elseif($suceso->exito == true)
   {
	$mensajes[1] = $root->appendChild($doc->createElement('mensajes'));
	$mensajes[1]->appendChild($doc->createElement('cod', 1));
	$mensajes[1]->appendChild($doc->createElement('desc', $mensajes['exito'][$_POST['cuenta']]));
   }
  if($suceso->mensajes)
   {
    foreach($suceso->mensajes AS $v)
     {
      if($mensajes[$v])
        continue;
	  $mensajes[$v] = $root->appendChild($doc->createElement('mensajes'));
	  $mensajes[$v]->appendChild($doc->createElement('cod', $v));
	  $mensajes[$v]->appendChild($doc->createElement('desc', $mensajes['mensajes'][$v]));
	  //$mensajes[$v]->setAttribute('cod', $v);
     }
   }
  if($suceso->respuesta)
   {
	$respuesta = $root->appendChild($doc->createElement('respuesta'));
    foreach($suceso->respuesta AS $k => $v)
     {
	  $respuesta->appendChild($doc->createElement($k, $v));
	  //$mensajes[$v]->setAttribute('cod', $v);
     }
   }
  echo $doc->saveXML();
 // $sitio = $doc->createElement('sitio');
//$sitio->appendChild($doc->createTextNode(SITIO_TITULO));
//$sitio = $respuesta->appendChild($sitio);
 }
//array_walk($login->respuesta->errores, 'idAdesc');
//$salida_msjs = array();
//array_walk($login->respuesta->mensajes, 'msjAdesc', &$salida_msjs);

  // var_dump($respuesta);
/* }
else
 {

 }
*/


?>

