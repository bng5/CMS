<?php

header("Content-Type: text/html; charset=utf-8");
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

if($secciones = Modelo_Secciones::Listado())
 {
  echo "
<table border=\"1\" cellspacing=\"0\">
 <tr>
  <th>Id</th>
  <th>Identificador</th>
  <th>Orden</th>
  <th>Superior</th>
 </tr>";
  foreach($secciones AS $k => $v)
   {
    echo "
 <tr>
  <td>".$v->id."</td>
  <td>".$v->identificador."</td>
  <td>".$v->orden."</td>
  <td>".$v->superior_id."</td>
 </tr>";
   }
  echo "
</table>";
 }

//$seccion = Modelo_Secciones::getPorId(1);
//var_export($seccion);
echo "\n\n";

try {
  $seccion_b = Modelo_Secciones::getPorIdentificador('essssgg');
 }
catch (Exception $e)
 {
  echo $e->getMessage();
 }

$seccion = clone $seccion_b;
$seccion->setSuperior(1);
//$seccion = new Seccion('qwerty2');
//
//$seccion->setNombre('Qwerty 2', 1);
//$seccion->setNombre('Qwerty 2', 2);
//
try {
	$id = Modelo_Secciones::Guardar($seccion);
 }
catch (Exception $e)
 {
  switch($e->getCode())
   {
	case 1062:
		$errtxt = sprintf("Ya existe una seccion con el identificador '%s'", $seccion->getIdentificador());
		break;
	default:
		$errtxt = "Ha ocurrido un error inesperado.";
		break;
   }
  echo $errtxt;
 }


echo "
<pre>
";
var_export($seccion);
echo "
</pre>
";

?>