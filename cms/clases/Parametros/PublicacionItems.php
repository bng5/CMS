<?php

class Parametros_PublicacionItems {

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
	public $pagina, $rpp, $categoria, $buscar, $recursivo, $orden, $ordenAsc, $ordenAleat;
	private $_campos = array();
	
	public function __construct($seccion, $idioma) {
		$this->seccion = $seccion;
		$this->idioma = $idioma;
		
		$db = DB::instancia();//BaseDatos::Conectar();
		/** TODO
		 * cambiar el *die* por algo más amigable
		 */
		//SELECT * FROM `pub__{$this->seccion}` LIMIT 1
		if(!$resultado = $db->query("SHOW COLUMNS FROM pub__{$this->seccion->id}"))
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
		$this->categoria = null;//int , array
		$this->buscar = null;//string
		$this->recursivo = false;
		$this->orden = 'orden';
		$this->ordenAsc = true;
		$this->ordenAleat = false;
	}

	/*public function setBusqueda($campo, $criterio) {
		if(array_key_exists($campo, $criterio)) {
			$this->
		}
		return $this;
	}*/

}
