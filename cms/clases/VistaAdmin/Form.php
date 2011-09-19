<?php

/**
 * Description of Form
 *
 * @author pablo
 */
class VistaAdmin_Form extends VistaAdmin {

	//provisorio
	public $thead = '', $tfoot = '';

	static $_indice = 0;

	public static function crearComponente($tipo) {
		$tipo = "VistaAdmin_Form".$tipo;
		$componente = new $tipo;
		$componente->indice = self::$_indice++;
		return $componente;
	}

	public static function crearComponentePorId($tipo) {
		$tipo = "VistaAdmin_FormCampo".$tipo;
		$componente = new $tipo;
		$componente->indice = self::$_indice++;
		return $componente;
	}

	public function mostrar() {

		echo '
<table class="tabla">
	'.$this->thead.'
	'.$this->tfoot.'
	<tbody>';
		if(count($this->_children)) {
			foreach($this->_children AS $child) {
				echo '
		<tr>
			';
				//var_dump($child);//$child->mostrar();
				echo $child->mostrar();
				echo '</tr>';
			}
		}
		else {
			echo '<tr><td>La configuración de esta sección aún no ha sido finalizada.<!--No existe ningún campo. a href="/configuracion?seccion='.$seccion_id.'">Configuración de items</a --></td></tr>';
		}
		echo '
	</tbody>
</table>';

	}
	
	protected function label($tipo = 0, $for = '') {
		if($tipo == 1)
			return "<td><label>{$this->nombre}:</label></td>\n";
		else {
			//$for = empty($for) ? $this->campo_id_pref.$this->indice : $for;
			return "<td><label for=\"{$this->campo_id_pref}{$this->indice}\">{$this->nombre}:</label></td>";
		}
	}
}

?>