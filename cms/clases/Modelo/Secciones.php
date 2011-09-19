<?php

class Modelo_Secciones
 {
  static $DB;
  function Listado($leng = 1, $params = array())//'superior_id' => 0
   {
	if(!$leng_id = intval($leng))
	 {
	  $leng = "(SELECT id FROM lenguajes WHERE codigo = '".$leng."')";
	 }
	if(count($params))
	 {
	  $bsq = array();
	  foreach($params AS $k => $v)
	    $bsq[] = "`${k}` = ${v}";
	  $bsq_sql = 'WHERE '.implode(" AND ", $bsq);
	 }
	return Listado::InstanciaSQL("SELECT s.*, sn.titulo FROM `secciones` s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = ".$leng." ".$bsq_sql." ORDER BY orden");
   }

  function getPorId($id)
   {
	if(!$id = intval($id))
	  throw new Exception("No se indicó un Id válido para cargar la sección.");
	self::$DB = DB::Conectar();
	$consulta = self::$DB->prepare("SELECT s.id, s.identificador, s.superior_id, s.orden, s.link_cms, s.link_cms_params, s.tipo, s.sistema, s.info, s.items, s.categorias, s.categorias_prof, s.salida_sitio, s.menu, s.rev, s.propietario, s.grupo, s.permiso_grupo FROM secciones s WHERE id = ".$id." LIMIT 1");
	return self::stm_a_Seccion($consulta);
   }

  // Alias
  function getSeccionPorIdentificador($identificador)
   {
	return self::getPorIdentificador($identificador);
   }
  function getPorIdentificador($identificador)
   {
	if(!is_string($identificador) && !empty($identificador))
	  throw new Exception("No se indicó un Identificador válido para cargar la sección.");
	self::$DB = DB::Conectar();
	$consulta = self::$DB->prepare("SELECT s.id, s.identificador, s.superior_id, s.orden, s.link_cms, s.link_cms_params, s.tipo, s.sistema, s.info, s.items, s.categorias, s.categorias_prof, s.salida_sitio, s.menu, s.rev, s.propietario, s.grupo, s.permiso_grupo FROM secciones s WHERE identificador = ? LIMIT 1");
	$consulta->bindValue(1, $identificador, PDO::PARAM_STR);
	return self::stm_a_Seccion($consulta);
   }

  private static function stm_a_Seccion($consulta)
   {
	$consulta->execute();
	/*$consulta->bindColumn('id', $id, DB::PARAM_INT);
	$consulta->bindColumn('identificador', $identificador);
	$consulta->bindColumn('superior_id', $superior_id, DB::PARAM_INT);
	$consulta->bindColumn('orden', $orden, DB::PARAM_INT);
	$consulta->bindColumn('link_cms', $link_cms);
	$consulta->bindColumn('link_cms_params', $link_cms_params);
	$consulta->bindColumn('tipo', $tipo);
	$consulta->bindColumn('sistema', $sistema, DB::PARAM_BOOL);
	$consulta->bindColumn('info', $info, DB::PARAM_BOOL);
	$consulta->bindColumn('items', $items, DB::PARAM_BOOL);
	$consulta->bindColumn('categorias', $categorias, DB::PARAM_BOOL);
	$consulta->bindColumn('categorias_prof', $categorias_prof);
	$consulta->bindColumn('salida_sitio', $salida_sitio, DB::PARAM_INT);
	$consulta->bindColumn('menu', $menu, DB::PARAM_BOOL);
	$consulta->bindColumn('rev', $rev, DB::PARAM_INT);
	$consulta->bindColumn('propietario', $propietario, DB::PARAM_INT);
	$consulta->bindColumn('grupo', $grupo, DB::PARAM_INT);
	$consulta->bindColumn('permiso_grupo', $permiso_grupo, DB::PARAM_INT);
	$categorias_prof = $categorias_prof ? (int) $categorias_prof : null;*/
	if($seccion = $consulta->fetchObject('Seccion'))
	 {
	  //$seccion = new Seccion($identificador, $id, $superior_id, $orden, $link_cms, $link_cms_params, $tipo, $sistema, $info, $items, $categorias, $categorias_prof, $salida_sitio, $menu, $rev, $propietario, $grupo, $permiso_grupo);
	  $consulta->closeCursor();
	  $consulta = self::$DB->query("SELECT leng_id, titulo FROM secciones_nombres WHERE id = ".$seccion->getId());
	  $consulta->setFetchMode(DB::FETCH_NUM);
	  if($nombres = $consulta->fetch())
	   {
		do
		 {
		  $seccion->setNombre($nombres[1], $nombres[0]);
		 }while($nombres = $consulta->fetch());
	   }
	  return $seccion;
	 }
	else
	  return false;
   }

  function guardarSeccion(Seccion $seccion)
   {
	$DB = DB::Conectar();
	if($id = $seccion->getId())
	 {
	  $consulta = $DB->prepare("UPDATE `secciones` SET id = :id, identificador = :identificador, superior_id = :superior_id, orden = :orden, link_cms = :link_cms, link_cms_params = :link_cms_params, tipo = :tipo, sistema = :sistema, info = :info, items = :items, categorias = :categorias, categorias_prof = :categorias_prof, salida_sitio = :salida_sitio, menu = :menu, rev = :rev, propietario = :propietario, grupo = :grupo, permiso_grupo = :permiso_grupo, rev = rev+1 WHERE id = :id AND rev = :rev LIMIT 1");
	  $consulta->bindValue(':id', $id, PDO::PARAM_INT);
	 }
	else
	 {
	  $consulta = $DB->prepare("INSERT INTO `secciones` (`id`, `identificador`, `superior_id`, `orden`, `link_cms`, `link_cms_params`, `tipo`, `sistema`, `info`, `items`, `categorias`, `categorias_prof`, `salida_sitio`, `menu`, `rev`, `propietario`, `grupo`, `permiso_grupo`) VALUES (:id, :identificador, :superior_id, :orden, :link_cms, :link_cms_params, :tipo, :sistema, :info, :items, :categorias, :categorias_prof, :salida_sitio, :menu, :rev, :propietario, :grupo, :permiso_grupo)");
	  $consulta->bindValue(':id', null, PDO::PARAM_NULL);
	 }
	$consulta->bindValue(':identificador', $seccion->getIdentificador());
	$consulta->bindValue(':superior_id', $seccion->getSuperior());
	$consulta->bindValue(':orden', $seccion->getOrden());
	$consulta->bindValue(':link_cms', $seccion->getLink_cms());
	$consulta->bindValue(':link_cms_params', $seccion->getLink_cms_params());
	$consulta->bindValue(':tipo', $seccion->getTipo());
	$consulta->bindValue(':sistema', $seccion->getSistema());
	$consulta->bindValue(':info', $seccion->getInfo());
	$consulta->bindValue(':items', $seccion->getItems());
	$consulta->bindValue(':categorias', $seccion->getCategorias());
	$consulta->bindValue(':categorias_prof', $seccion->getCategorias_prof());
	$consulta->bindValue(':salida_sitio', $seccion->getPublicacion());
	$consulta->bindValue(':menu', $seccion->getMenu());
	$consulta->bindValue(':rev', $seccion->getRevision());
	$consulta->bindValue(':propietario', $seccion->getPropietario());
	$consulta->bindValue(':grupo', $seccion->getGrupo());
	$consulta->bindValue(':permiso_grupo', $seccion->getPermiso_grupo());
	$consulta->execute();
	if(count($consulta->errorInfo()) == 3)
	 {
	  $error_info = $consulta->errorInfo();
	  throw new Exception($error_info[2], $error_info[1]);
	 }
	if($consulta->rowCount())
	 {
	  return $DB->lastInsertId() ? (int) $DB->lastInsertId() : true;
	 }
	return false;
   }

  function eliminarSeccionPorId($id)
   {
	if(!$id)
	  return false;
	// TODO
	// Borrar permisos
	// Borrar Items
	// Borrar Atributos
	//$mysqli->query("DELETE FROM `usuarios_permisos` WHERE area_id = 2 AND item_id = '".$modificari."'");// or die (__LINE__." - ".mysql_error());
    self::$mysqli->exec("DELETE FROM `secciones` WHERE id = ".$this->id." LIMIT 1");
    self::$mysqli->exec("DELETE FROM `secciones_nombres` WHERE `id` = ".$this->id);
   }
//  function EliminarPorIdentificador($identificador)
//   {
//	if(!$this->id)
//	  return false;
//	// TODO
//	// Borrar permisos
//	// Borrar Items
//	// Borrar Atributos
//	//$mysqli->query("DELETE FROM `usuarios_permisos` WHERE area_id = 2 AND item_id = '".$modificari."'");// or die (__LINE__." - ".mysql_error());
//    self::$mysqli->query("DELETE FROM `secciones` WHERE id = ".$this->id." LIMIT 1");
//    self::$mysqli->query("DELETE FROM `secciones_nombres` WHERE `id` = ".$this->id);
//   }
 }

?>