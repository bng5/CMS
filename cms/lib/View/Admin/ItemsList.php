<?php

/**
 * Description of Document
 *
 * @author pablo
 */
class View_Admin_ItemsList extends View_Admin {

    public function __construct($dataProvider) {
        $this->listado = $dataProvider;
    }
//	public function  __construct(Listado $items) {
//		$this->listado = $items;
//		$this->agregarComponente($this->agregarComponente(new VistaAdmin_Paginado($this->listado->total, $this->listado->pagina, $this->listado->paginas)));
//	}

    public function show() {


		if($this->listado->total == 0) {
			echo("No se encontró ningún item.");
			return;
		}
		// TODO path y query debería ser accesible para todos los componentes
		$parse2 = parse_url($_SERVER["REQUEST_URI"]);
		if($parse2['query']) {
			parse_str($parse2['query'], $arr2);
			$query = http_build_query($arr2, null, '&amp;');
		}

		//var_dump($this->listado->atributos);
		echo '
	<form action="'.$this->_documento->path.$this->_documento->construirQuery().'" method="post" onsubmit="return contarCheck(\'lista_item[]\');">
		<table class="tabla" id="tablaListado" style="width:auto;">
			<thead>
				<tr class="orden">
					<td style="width:20px;text-align:center;"><input type="checkbox" name="checkTodos" onclick="checkearTodo(this.form, this, \'lista_item[]\');" /></td>';
		foreach($this->listado->atributos AS $attr) {
			echo '
					<td>'.$attr['atributo'].'</td>';
		}
		echo '
					<td>Creado</td>
					<td>Modificado</td>
					<td>Orden</td>
				</tr>
			</thead>
			<tbody>';
		foreach($this->listado AS $fila) {

			/*
			echo "<tr><td colspan=\"5\">";
			var_dump($fila);
			echo "</td></tr>";
			 *
array
  'id' => string '34' (length=2)
  'estado_id' => string '1' (length=1)
  'f_creado' => string '2010-06-21 19:48:16' (length=19)
  'f_modificado' => string '2010-06-21 19:48:16' (length=19)
  'orden' => string '1' (length=1)
  'titulo' => string 'Ventaja número 4' (length=17)
  'archivo' => string 'icono1_ventajasly2Gvs.jpg' (length=25)
  'orden_null' => string '0' (length=1)

			*/

			$f_orden++;

			if($f_orden != $fila[4]) {
				//$this->db->query(sprintf($f_consulta, $f_orden, $fila[0], $cat));
			}
			if($tiempo < $fila[6])
				$fila['estado_id'] = 2;
			echo "
		  <tr class=\"{$clase_estado[$fila[1]]}\">
		   <td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila['id']}\" onclick=\"selFila(this, '{$clase_estado[$fila['estado_id']]}');\" /></td>";
			if(!count($this->listado->atributos)) {
				echo "
		   <td><a href=\"/editar?seccion={$this->listado->seccion->id}&amp;id={$fila['id']}{$items_link}\">{$fila['id']}</a></td>";
			}
			else {
				//$n = 5;
				$linkeado = false;

				foreach($this->listado->atributos AS $attrs_lista_k => $attrs_lista_v) {
					$n = $attrs_lista_v['identificador'];
					echo "<td>";
					//var_dump($attrs_lista_k, $attrs_lista_v);
					if($attrs_lista_k == 2) {
						if($linkeado) //$items->attrs_lista['tipo_id'])// || $attrs_lista[21])
							echo("<img src=\"icono/2/{$fila[$n]}\" alt=\"\" />");
						else {
							echo("<a href=\"/editar?seccion={$this->listado->seccion->id}&amp;id={$fila['id']}{$items_link}\"><img src=\"icono/2/{$fila[$n]}\" alt=\"\" /></a>");
							$linkeado = true;
						}
					}
					else {
						$txt = $fila[$n] ? htmlspecialchars($fila[$n]) : "id: {$fila[0]}";
						if(!$linkeado) {
							echo("<a href=\"/editar?seccion={$this->listado->seccion->id}&amp;id={$fila['id']}{$items_link}\">{$txt}</a>");
							$linkeado = true;
						}
						else
							echo($txt);
					}
					//$n++;
					echo "</td>";
				}
			}
			$creado = new DateTime($fila['f_creado']);
			$modificado = new DateTime($fila['f_modificado']);
			echo "
		   <td>".$creado->format("d-m-Y G:i")." hs.</td>
		   <td>".$modificado->format("d-m-Y G:i")." hs.</td>
		   <td><input type=\"text\" value=\"{$f_orden}\" size=\"3\" /><img src=\"/img/flecha_bt\" onclick=\"document.location.href='/listar?seccion={$this->listado->seccion->id}{$items_link}&amp;pagina={$pagina}&amp;n_orden={$fila['id']},{$f_orden},'+this.previousSibling.value\" alt=\"\" /></td>
		   </tr>";
		}
		echo '
			</tbody>
		</table>
		<div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ningún item.</div>
		<div id="listado_opciones" style="padding:4px;"><img src="./img/flecha_arr_der.png" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Publicar" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar publicaci&oacute;n" />&nbsp;<input type="submit" name="mult_submit" value="Eliminar completamente" onclick="return confBorrado(\'lista_item[]\');" /></div>
		<div id="listado_result"></div>
		<div>Total: '.$this->listado->total.'</div>';
		if($this->_children && $this->_children->count()) {
			foreach($this->_children AS $child)
				echo $child->mostrar();
		}
		echo '
	</form>';
	/*<table class="tabla" id="tablaReferencia" style="width:auto;position:absolute;display:none;">
		<thead>
			<tr class="orden">
				<td>Referencia</td>
			</tr>
		</thead>
		<tbody>
			<tr class="sel_fila">
				<td>Seleccionado</td>
			</tr>
			<tr>
				<td>Publicado</td></tr>
			<tr class="inactivo">
				<td>No publicado</td>
			</tr>
			<tr class="enproceso">
				<td>Siendo editado</td>
			</tr>
			<tr class="actual">
				<td>actual</td>
			</tr>
			<tr class="nofinaliz">
				<td>nofinaliz</td>
			</tr>
			<tr class="sinverificar">
				<td>sinverificar</td>
			</tr>
			<tr class="suspendido">
				<td>suspendido</td>
			</tr>
		</tbody>
	</table>*/

	}


}
