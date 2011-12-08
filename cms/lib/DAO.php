<?php
/**
 * Superclase
 *
 * @author pablo
 *
 */

abstract class DAO {

	const INSERT = 0;
	const UPDATE = 1;
	const DELETE = 2;

	protected $_db;
	protected $_afectadas = array(0, 0, 0);

	public function  __construct() {
		$this->_db = DB::instance();
	}


// Heredado de CMS3(A) Modelo
	/**
	 * Recibe un obeto que extiende de DTO y lo guarda en DB según el caso (insert | update)
	 *
	 * @param DTO_* $dto
	 * @return ????
	 */
	public function save(DTO $dto) {
		switch($dto->estado()) {
			case DTO::ESTADO_NUEVO:
				$this->_insert($dto);
				break;
			case DTO::ESTADO_GUARDADO:
				$this->_update($dto);
				break;
		}
		$dto->estado(DTO::ESTADO_GUARDADO);
	}

	public function filasAfectadas($tipo = null) {
		if(!isset($tipo))
			return array_sum($this->_afectadas);
		else
			return $this->_afectadas[$tipo];
	}

	protected function _agregarAfectadas($tipo, $cantidad) {
		$this->_afectadas[$tipo] += $cantidad;
	}

	/**
	 * Ejecuta una consulta SQL dada e incrementa las filas afectadas para el tipo de gestión.
	 *
	 * @param string $sql
	 * @param int $tipo  (ALTA | BAJA | MODIFICACION)
	 */
	protected function _exec($sql, $tipo) {
		if($afectadas = $this->_db->exec($sql))
			$this->_agregarAfectadas($tipo, $afectadas);
        return $afectadas;
	}

}
