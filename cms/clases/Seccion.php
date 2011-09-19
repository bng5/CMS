<?php

class Seccion {
	
	private $_valores = array(
		'id' => null,
		'identificador' => '',
		'titulo' => '',
		'url' => '',
		'superior_id' => 0,
		'orden' => null,
		'link_cms' => 'listar',
		'sistema' => 0,
		'info' => 0,
		'items' => 0,
		'categorias' => 0,
		'categorias_prof' => 0,
		'salida_sitio' => 1,
		'menu' => 1,
		'rpp' => 25,
	);


  private $_nombres = array();
  private $_urls = array();
  /*
  function __construct($identificador, $id = null, $superior_id = 0, $orden = null, $link_cms = 'listar', $link_cms_params = null, $tipo = 'admin', $sistema = 0, $info = 0, $items = 0, $categorias = 0, $categorias_prof = null, $salida_sitio = 1, $menu = true, $rev = 1, $propietario = false, $grupo = false, $permiso_grupo = false)
   {
	if(!is_string($identificador) || empty($identificador))
	  throw new Exception("El Identificador no es válido.");
  	$this->identificador = $identificador;
	$this->id = $id;
	$this->superior_id = $superior_id;
	$this->orden = $orden;
	$this->link_cms = $link_cms;
	$this->link_cms_params = $link_cms_params;
	$this->tipo = $tipo;
	$this->sistema = $sistema;
	$this->info = $info;
	$this->items = $items;
	$this->categorias = $categorias;
	$this->categorias_prof = $categorias_prof;
	$this->salida_sitio = $salida_sitio;
	$this->menu = $menu;
	$this->rev = $rev;
	$this->propietario = $propietario;
	$this->grupo = $grupo;
	$this->permiso_grupo = $permiso_grupo;
   }
   */

	public function __get($attr) {
		return $this->_valores[$attr];
	}

	public function __set($attr, $valor) {
		if(array_key_exists($attr, $this->_valores)) {
			$metodo = "set_".$attr;
			$this->$metodo($valor);
		}
	}
	
	function __clone() {
		$this->id = null;
		//$this->identificador .= '_'.time();
		//$this->orden++;
	}

   public function set_id($valor) {
	   $this->_valores['id'] = (int) $valor;
   }

   public function set_identificador($valor) {
	   $this->_valores['identificador'] = $valor;
   }


   public function set_superior_id($valor) {
	   	$this->campos['superior_id'] = true;
		$this->_valores['superior_id'] = (int) $valor;
   }

   public function set_orden($valor) {
	   $this->_valores['orden'] = (int) $valor;
	   $this->campos['orden'] = true;
   }

   public function set_link_cms($valor) {
	   $this->_valores['link_cms'] = (string) $valor;
	   $this->campos['link_cms'] = true;
   }

   public function set_sistema($valor) {
	   $this->_valores['sistema'] = (int) $valor;
   }

   public function set_info($valor) {
	   $this->_valores['info'] = (int) $valor;
   }

   public function set_items($valor) {
	   $this->_valores['items'] = (int) $valor;
   }

   public function set_categorias($valor) {
	   $this->_valores['categorias'] = (int) $valor;
   }

   public function set_categorias_prof($valor) {
	   $this->_valores['categorias_prof'] = (int) $valor;
   }

   public function set_salida_sitio($valor) {
	   $this->_valores['salida_sitio'] = (int) $valor;
   }

   public function set_menu($valor) {
	   $this->_valores['menu'] = (int) $valor;
   }

   public function set_titulo($valor) {
	   $this->_valores['titulo'] = $valor;
   }
   public function set_url($valor) {
	   $this->_valores['url'] = $valor;
   }
   public function set_rpp($valor) {
	   $this->_valores['rpp'] = $valor;
   }



  function setRevision($rev)
   {
	$this->rev = $rev;
   }

  function getRevision()
   {
	return $this->rev;
   }

  function setValor($nombre, $valor)
   {
	if(array_key_exists($this->datos, $nombre))
	 {
	  $this->datos[$nombre] = $valor;
	  return true;
	 }
	return false;
   }

  function getValor($nombre = false)
   {
	if(!$nombre)
	  return $this->datos;
	else
	 {
	  if(array_key_exists($this->datos, $nombre))
	    return $this->datos[$nombre];
	 }
	return false;
   }


  function setSistema($valor)
   {
	$this->campos['sistema'] = true;
	$this->sistema = (int) $valor;
   }
  function getSistema()
   {
	return $this->sistema;
   }
   
  function setInfo($valor)
   {
	$this->campos['info'] = true;
	$this->info = (int) $valor;
   }
  function getInfo()
   {
	return $this->info;
   }
  function setItems($valor)
   {
	$this->campos['items'] = true;
	$this->items = (int) $valor;
   }
  function getItems()
   {
	return $this->items;
   }
  function setCategorias($valor)
   {
	$this->campos['categorias'] = true;
	$this->categorias = (int) $valor;
   }
  function getCategorias()
   {
	return $this->categorias;
   }
  function setCategorias_prof($valor)
   {
	$this->campos['categorias_prof'] = true;
	$this->categorias_prof = (int) $valor;
   }
  function getCategorias_prof()
   {
	return $this->categorias ? $this->categorias_prof : false;
   }
  function setPublicacion($valor)
   {
	$this->campos['salida_sitio'] = true;
	$this->salida_sitio = (int) $valor;
   }
  function getPublicacion()
   {
	return (int) $this->salida_sitio;
   }
  function setMenu($valor)
   {
	$this->campos['menu'] = true;
	$this->menu = (boolean) $valor;
   }
  function getMenu()
   {
	return (boolean) $this->menu;
   }

  function setNombre($nombre, $leng_id)
   {
    $this->nombres[$leng_id] = $nombre;
   }
  function getNombre($leng_id = false)
   {
	if(!$leng_id)
	  $leng_id = $_SESSION['leng_id'];
	return $this->nombres[$leng_id];
   }
  function setNombres($nombres)
   {
	if(!is_array($nombres))
	  return;
    $this->nombres += $nombres;
   }
  function getNombres()
   {
	  return $this->nombres;
   }

  // Alias
  function Guardar()
   {
	return Modelo_Secciones::Guardar($this);
   }
 }

?>