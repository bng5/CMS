<?php

/**
 * Item (dato externo)
 *
 * @author pablo
 */
class VistaAdmin_FormCampo12 extends VistaAdmin_Form {


	public function __construct($item_id = false) {
		$this->campo_id_pref = $this->campo_nombre_pref = "dato";
	}

	public function mostrar() {
		//$nombre_campo = $this->valores[0]['int'] ? "[m][".$this->id."][".$this->valores[0]['id']."]" : "[n][".$this->id."][]";

        $db = DB::instance();
        $ret = $this->label(1) . "<td>";

        if (!$cons_vista = $db->query($this->extra['consulta'] . " ORDER BY 2", PDO::FETCH_NUM))
            $ret .= "Existe un error en la configuración de este campo";
        else {
            //if($fila_vista = $cons_vista->fetch(PDO::FETCH_NUM)) {
                $nombre_campo = $this->valores[0]['int'] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
                $ret .= "<select name=\"{$this->campo_nombre_pref}{$nombre_campo}\"><option value=\"\"> </option>";
                //do {
                foreach($cons_vista AS $fila_vista) {
                    $ret .= "<option value=\"{$fila_vista[0]}\"";
                    if ($fila_vista[0] == $this->valores[0]['int'])
                        $ret .= " selected=\"selected\"";
                    $ret .= ">" . htmlspecialchars($fila_vista[1]) . "</option>";
                }
                //}while ($fila_vista = $cons_vista->fetch_row());
                $ret .= "</select>";
                //$cons_vista->close();
            //}
        }
        return $ret.'</td>';
	}

}
