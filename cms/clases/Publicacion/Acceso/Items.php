<?php

/**
 *
 *
 * @author Bng5
 *
 */

class Publicacion_Acceso_Items {

	const ORDEN_ALEATORIO = 1;
	/**
	 *
	 * @var int $seccion
	 * @var int $idioma
	 * @var int $pagina
	 * @var int $rpp
	 * @var int, array $categoria
	 * @var string $buscar
	 * @var bool $recursivo
	 * @var string $orden
	 * @var bool $ordenAsc
	 * @var bool $ordenAleat;
	 */
	public $seccion, $idioma;
	
	/**
	 * Parámetros de búsqueda
	 * 
	 */
	public $pagina, $rpp, $resultados, $categoria, $buscar, $recursivo, $orden, $ordenAsc, $ordenAleat;

	private $_campos = array();
	private $_db;

	public function __construct($seccion, $idioma) {
		$this->seccion = $seccion;
		$this->idioma = $idioma;

		$this->_db = DB::instancia();
		/** TODO
		 * cambiar el *die* por algo más amigable
		 */
		//SELECT * FROM `pub__{$this->seccion}` LIMIT 1
		if(!$resultado = $this->_db->query("SHOW COLUMNS FROM pub__{$this->seccion->id}"))
			throw new Exception("No existe ninguna publicación para la sección indicada.");
		if($finfo = $resultado->fetchAll()) {
			foreach ($finfo as $val) {
				$ex = explode("__", $val['Field']);
				if($ex[1])
					$this->_campos[$ex[1]][$ex[0]] = "{$ex[0]}__{$ex[1]}";
			}
		}

		$this->_campos[1] = array('id');
		$this->_campos[2] = array('creado');
		$this->_campos[3] = array('modificado');

		$this->pagina = 1;
		$this->rpp = 25;
		$this->resultados = 25;
		$this->categoria = null;//int , array
		$this->buscar = null;//string
		$this->recursivo = false;
		$this->orden = 'orden';
		$this->ordenAsc = true;
		$this->ordenAleat = false;
	}

//	public function __set($attr, $valor) {
//		if($attr == 'rpp' || $attr == 'resultados') {
//			$this->rpp = $this->resultados = $valor;
//		}
//		$this->{$attr} = $valor;
//	}

	/*public function setBusqueda($campo, $criterio) {
		if(array_key_exists($campo, $criterio)) {
			$this->
		}
		return $this;
	}*/


	/**
	 */
	public static function publicar() {

	}

	/**
	 *
	 * @param Parametros_PublicacionItems $this
	 * @return Listado
	*/
	public function obtenerListado() {//Parametros_PublicacionItems $parametros) {
		$total = $this->_db->prepare("SELECT COUNT(*) FROM pub__".$this->seccion->id." WHERE leng_cod = ?");
		$total->execute(array($this->idioma->cod));
		$total = $total->fetchColumn();

		$orden = $this->ordenAleat ? 'RAND()' : 'ordennull ASC, i.'.$this->orden;
		$limite = ($this->rpp) ? " LIMIT ".(($this->pagina - 1) * $this->rpp).", ".$this->rpp : "";

		$consulta = $this->_db->prepare("SELECT ip.*, i.orden IS NULL AS ordennull FROM `pub__".$this->seccion->id."` ip JOIN items i ON ip.id = i.id WHERE ip.leng_cod = ? ORDER BY {$orden} ".$this->ordenDir.$limite);
		$consulta->setFetchMode(DB::FETCH_ASSOC);
		$consulta->execute(array($this->idioma->cod));

		return new Listado($total, $consulta, $this->pagina, $this->rpp);
		//return $consulta->fetchAll(DB::FETCH_ASSOC);
	}

//	public function obtener() {
//		$limite = ($this->resultados) ? " LIMIT ".(($this->pagina - 1) * $this->rpp).", ".$this->resultados : "";
//
//		$consulta = $this->_db->prepare("SELECT ip.*, i.orden IS NULL AS ordennull FROM `pub__".$this->seccion->id."` ip JOIN items i ON ip.id = i.id WHERE ip.leng_cod = ? ORDER BY ordennull ASC, i.".$this->orden." ".$this->ordenDir.$limite);
//		//$consulta->setFetchMode(DB::FETCH_ASSOC);
//		return $consulta->execute(array($this->idioma->cod))->fetchAll(DB::FETCH_ASSOC);
//
//		$consulta->execute(array($this->idioma->cod));
//		return $consulta->fetchAll(DB::FETCH_ASSOC);
//	}
}
