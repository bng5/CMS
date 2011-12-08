<?php

/**
 * View_Admin_ItemForm
 *
 * @author pablo
 */
class View_Admin_ItemForm extends View_Admin {

    public function __construct() {

    }
//	public function  __construct(Listado $items) {
//		$this->listado = $items;
//		$this->agregarComponente($this->agregarComponente(new VistaAdmin_Paginado($this->listado->total, $this->listado->pagina, $this->listado->paginas)));
//	}

    public function show() {

        ?>
    <form name="formedicion" id="formedicion" action="editar_guardar?seccion='.$seccion_id.'&amp;cat='.$_REQUEST['cat'].'" method="post">
	 <input type="hidden" name="tipo" value="i" />
	 <input type="hidden" name="id" value="{$id}" />
	 <input type="hidden" name="ia" value="modificar" />
	 <input type="hidden" name="publicar" value="0" />
	 <input type="hidden" name="seccion" value="{$seccion_id}" />
	 <input type="hidden" name="sup" value="{$_REQUEST['sup']}" />
	 <input type="hidden" name="leng[]" value="{$leng}" />
     <table class="tabla">
        <tbody>
        <?php

        if(count($this->_children)) {
            $this->showChildren();
        }
        else {
            echo '<tr><td>La configuración de esta sección aún no ha sido finalizada.<!--No existe ningún campo. a href="/configuracion?seccion='.$seccion_id.'">Configuración de items</a --></td></tr>';
        }
        ?>
        </tbody>
     </table>
    </form>

        <?php

	}


    public function createComponent($type) {
        $component = new View_Admin_Field_Str();

        return $component;
    }


}
