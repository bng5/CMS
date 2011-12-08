<?php

/**
 * Description of Formstring
 *
 * @author pablo
 */
class VistaAdmin_FormCampo13 extends VistaAdmin_Form {

    public $id, $nombre, $indice = 0, $sugerido, $unico, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log, $formato;
    private $item, $label, $v, $pref, $campo_nombre_pref, $niveles; //, $x = array("id" => 0)
    private $tipo = 'string';

    public function __construct($item_id = false) {

        $this->log = '';
        $this->mysqli = $mysqli;
        $this->campo_id_pref = $this->campo_nombre_pref = "hook[facebook]";
        $this->item = $item_id;
        $this->niveles = array(0);
        $this->niveles_cierres = array();
        $this->superior_niv = 0;
    }

    function __destruct() {
        $pop = end($this->niveles);
        while ($pop != 0) {
            array_pop($this->niveles);
            echo $this->niveles_cierres[$pop];
            unset($this->niveles_cierres[$pop]);
            $pop = end($this->niveles);
        }
    }

    public function mostrar() {
        $this->log .= "\n\nid: {$this->id}\nsuperior: {$this->superior}\n";
        //if($this->superior_niv != $this->superior)
        // {
        if (in_array($this->superior, $this->niveles)) {
            $retorno = '';
            $ii = count($this->niveles);
            $pop = end($this->niveles);
            while ($pop != $this->superior) {
                array_pop($this->niveles);
                $retorno .= $this->niveles_cierres[$pop];
                unset($this->niveles_cierres[$pop]);
                $ii--;
                $pop = end($this->niveles);
            }
        }
        // else
        //}
        //array_push($this->niveles, $this->superior);
        array_push($this->niveles, $this->id);
        $this->log .= var_export($this->niveles, true) . "\n";
        $this->log .= var_export($this->valores, true) . "\n";
        //$this->superior_niv = $this->superior;
        $this->superior_niv = $this->id;

        //$campo_tipo = "campo".$this->tipo;
        $this->indice++;

        $nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
        //echo $this->label(0, $this->campo_id_pref . $this->indice) . "<td><input type=\"checkbox\" name=\"share[facebook]\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['string'])."\" /> Publicar en Facebook</td>"; // tabindex=\"2\"
        echo "<td colspan=\"2\"><input type=\"checkbox\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"1\" /> <label for=\"{$this->campo_id_pref}{$this->indice}\">Publicar en Facebook</label>";
        if(count($this->valores)) {
            echo '<ul>';
            foreach($this->valores AS $fbPostId) {
                $fbPost = explode("_", $fbPostId['string']);
                echo '<li><a href="http://www.facebook.com/permalink.php?story_fbid='.$fbPost[1].'&amp;id='.$fbPost[0].'" target="_blank">'.$fbPostId['date'].'</a></li>';
            }
            echo '</ul>';
        }
        echo "
        </td>"; // tabindex=\"2\"


        //$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
        //$retorno.$this->$campo_tipo();
    }



}
