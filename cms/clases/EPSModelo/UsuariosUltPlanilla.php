<?php

class EPSModelo_UsuariosUltPlanilla
 {
  static function Listado($usuario)
   {
	$DB = DB::Conectar();
	$consulta_total = $DB->query("SELECT COUNT(*) AS total, p.id, p.fecha, p.modificado, p.titulo FROM eps__planillas p LEFT JOIN eps__planillas_items pi ON p.id = pi.planilla_id WHERE p.usuario_id = ".$usuario."  GROUP BY pi.planilla_id ORDER BY fecha DESC LIMIT 1");
	$planilla = $consulta_total->fetch(DB::FETCH_ASSOC);
	unset($consulta_total);
	if($planilla['total'])
	 {
	  $respuesta = new EPS_Planilla;
	  $respuesta->total = $planilla['total'];
	  $respuesta->id = $planilla['id'];
	  $respuesta->fecha_agregado = $planilla['fecha'];
	  $respuesta->fecha_modificado = $planilla['modificado'];
	  $respuesta->titulo = $planilla['titulo'];

	  $respuesta->stmt = $DB->query("SELECT id, tipo, marca, modelo, insumo, rendimiento, precio_reman, precio_nuevo FROM eps__planillas_items WHERE planilla_id = ".$planilla['id']." ORDER BY orden");
	  $respuesta->stmt->setFetchMode(DB::FETCH_CLASS, 'EPS_PlanillaItem');//DB::FETCH_OBJ
     }
	else
	  $respuesta = false;
	return $respuesta;
   }
 }






/*  static function Listado($usuario)
   {
	$DB = DB::Conectar();
	$consulta_total = $DB->query("SELECT COUNT(*) FROM eps__planillas");
	$respuesta = new Listado;
	if($respuesta->total = $consulta_total->fetchColumn())
	 {
	  $respuesta->rpp = $rpp;
	  $respuesta->paginas = ceil($respuesta->total / $respuesta->rpp);
	  $respuesta->pagina = is_numeric($pagina) ? floor($pagina) : 1;
	  if($respuesta->pagina > $respuesta->paginas)
	    $respuesta->pagina = $respuesta->paginas;
	  $desde = ($respuesta->pagina - 1) * $respuesta->rpp;
	  $respuesta->listado = $DB->query("SELECT string__titulo_video AS titulo, img__img_video AS img, text__des_video AS descripcion, string__codigo AS codigo FROM pub__television  LIMIT ${desde}, ".$respuesta->rpp);
	  $respuesta->listado->setFetchMode(DB::FETCH_CLASS, 'MediosTelevision');//DB::FETCH_OBJ
     }
	return $respuesta;
   }*/
?>