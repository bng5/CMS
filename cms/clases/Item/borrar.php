<?php

class Item_borrar {

    function __construct($seccion) {
        global $seccion_id;
        $this->seccion = $seccion;
        $this->seccion_id = $seccion_id;
        $this->modificadas = 0;
    }

    function Item($id, $borrar = false) {
        global $mysqli;
        /* Borrar ícono */
        //@unlink("../img/{$this->seccion}/iconos/{$id}.jpg");
        /* Borrar archivos xml
          //foreach(glob("../item/{$id}.xml.*") as $xmls)
          //	@unlink($xmls);
         */

        /* Eliminar índice */
        $mysqli->query("DELETE FROM `pub__{$this->seccion_id}` WHERE `id` = '{$id}'");
        /*
          foreach(glob("../menuXml/{$this->seccion}.xml.*") as $indice)
          {
          if($doc = @DOMDocument::load($indice))
          {
          $root = $doc->firstChild;
          $doc->validateOnParse = true;
          if($nodoseccion = $doc->getElementById($id)) $root->removeChild($nodoseccion);
          $doc->save($indice);
          }
          }
         */
        if ($borrar == true) {
            $mysqli->query("DELETE FROM `items` WHERE `id` = '{$id}'");
            $mysqli->query("DELETE FROM items_a_categorias WHERE `item_id` = '{$id}'");
            $mysqli->query("DELETE FROM items_valores WHERE `item_id` = '{$id}'");
            $mysqli->query("DELETE FROM campos_opciones_sel WHERE `item_id` = '{$id}'");
        }
        else
            $mysqli->query("UPDATE `items` SET `estado_id` = '0' WHERE `id` = '{$id}'");
        if ($mysqli->affected_rows)
            $this->modificadas++;
    }

}
