<?php

/**
 * Video YouTube
 *
 * @author pablo
 */
class VistaAdmin_FormCampo6 extends VistaAdmin_Form {

	public function mostrar() {
		echo $this->label().'<td>
			<ul>
				<li><label>Id.:</label> '.$this->valores[0]['string'].'</li>
				<li><label>Título:</label> '.$this->valores[0]['text'].'</li>
				<li><label>Duración:</label> '.$this->valores[0]['int'].' segundos</li>
				<li><label>Imagen:</label> <img src="http://i.ytimg.com/vi/'.$this->valores[0]['string'].'/2.jpg" height="90" width="120" alt="" /></li>
			</ul>
			<!-- button >Cambiar</button --></td>';
	}
}
