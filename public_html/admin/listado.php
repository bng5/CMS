<?php

include('inc/iniciar.php');
header('Content-Type: text/plain');


/*

getIterator() PDO

Listado->total
Listado->resultados
Listado->rpp
Listado->pagina
Listado->paginas


*/




class Algos {

	public static function Listado($bsq = null, $pagina = 1, $rpp = Listado::RPP, $orden = 'orden') {
		$db = DB::instancia();
		$consulta_total = $db->query("SELECT COUNT(*) FROM secciones")->fetchColumn();

		$desde = ($pagina - 1) * $rpp;
		$consulta = $db->query("SELECT * FROM secciones ORDER BY ".$orden." LIMIT ".$desde.", ".$rpp);
		$consulta->setFetchMode(DB::FETCH_CLASS, 'Seccion');//__CLASS__);//setFetchMode(DB::FETCH_CLASS, 'MediosTelevision');//DB::FETCH_OBJ
		$l = new Listado($consulta_total, $consulta, $pagina, $rpp);
		//$l->setIterator($consulta);
		return $l;
	}
}

$pagina = isset($_GET['pagina']) && intval($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$rpp = isset($_GET['rpp']) && intval($_GET['rpp']) ? intval($_GET['rpp']) : 25;
$l = Algos::Listado(null, $pagina, $rpp);
echo "total: ".$l->total."\n";
echo "rpp: ".$l->rpp."\n";
echo "pagina: ".$l->pagina."\n";
echo "paginas: ".$l->paginas."\n";


$it = $l->getIterator();
var_dump($it);
foreach($it AS $k => $v) {
	echo "\n".$v->getId()." ".$v->getIdentificador()."\n";
	//var_dump($v);
}


?>
