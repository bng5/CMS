<?php

class EPSModelo_UsuariosPlanilla
 {
  function __construct($id, $usuario)
   {
	$this->id = $id;
	$this->usuario = $usuario;
   }

  function setTitulo($valor)
   {
    $db = DB::instancia();
    $update = $db->prepare("UPDATE `eps__planillas` SET modificado = ".time().", titulo = ? WHERE id = ".$this->id." LIMIT 1");
    if($valor)
      $update->bindValue(1, $valor, DB::PARAM_STR);
    else
      $update->bindValue(1, null, DB::PARAM_NULL);
    $update->execute();
   }

  function prepararConsultas()
   {
	$DB = DB::Conectar();
	$this->insert = $DB->prepare("INSERT INTO `eps__planillas_items` (`planilla_id`, `orden`, `tipo`, `marca`, `modelo`, `insumo`, `rendimiento`, `precio_reman`, `precio_nuevo`) VALUES (".$this->id.", :orden, :tipo, :marca, :modelo, :insumo, :rendimiento, :precio_reman, :precio_nuevo)");
	$this->update = $DB->prepare("UPDATE `eps__planillas_items` SET `orden` = :orden, `tipo` = :tipo, `marca` = :marca, `modelo` = :modelo, `insumo` = :insumo, `rendimiento` = :rendimiento, `precio_reman` = :precio_reman, `precio_nuevo` = :precio_nuevo WHERE id = :id LIMIT 1");
	$this->delete = $DB->prepare("DELETE FROM `eps__planillas_items` WHERE `id` = :id AND `planilla_id` = ".$this->id." LIMIT 1");
	$this->orden = 1;
   }

  function agregar(EPS_PlanillaItem $item)
   {
	if(empty($item->id))
	 {
	  $stmt = $this->insert;
	 }
	else
	 {
	  $stmt = $this->update;
	  $stmt->bindValue(':id', $item->id, PDO::PARAM_INT);
	 }
	$stmt->bindValue(':orden', $this->orden, PDO::PARAM_INT);
	$stmt->bindValue(':tipo', $item->tipo, PDO::PARAM_INT);
	$stmt->bindValue(':marca', $item->marca, PDO::PARAM_STR);
	$stmt->bindValue(':modelo', $item->modelo, PDO::PARAM_STR);
	$stmt->bindValue(':insumo', $item->insumo, PDO::PARAM_STR);
	$stmt->bindValue(':rendimiento', $item->rendimiento, PDO::PARAM_INT);
	$stmt->bindValue(':precio_reman', $item->precio_reman, PDO::PARAM_STR);
	$stmt->bindValue(':precio_nuevo', $item->precio_nuevo, PDO::PARAM_STR);
	$stmt->execute();

	$this->orden++;
   }

  function borrar($id)
   {
	$this->delete->bindValue(':id', $id, PDO::PARAM_INT);
	$this->delete->execute();
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