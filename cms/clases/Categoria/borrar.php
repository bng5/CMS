<?php

class Categoria_borrar {

    function __construct($seccion) {
        $this->seccion = $seccion;
        $this->modificadas = 0;
    }

    function Categoria($id, $borrar = false) {
        global $mysqli;
        /* Eliminar índice */
        $mysqli->query("DELETE FROM `pubcats__{$this->seccion}` WHERE `id` = '{$id}'");
        if ($borrar == true) {
            $mysqli->query("DELETE FROM `items_categorias` WHERE `id` = '{$id}'");
            $mysqli->query("DELETE FROM `items_categorias_nombres` WHERE `id` = '{$id}'");
            $mysqli->query("DELETE FROM `categorias_valores` WHERE `categoria_id` = '{$id}'");
            $mysqli->query("DELETE FROM `items_a_categorias` WHERE `categoria_id` = '{$id}'");
        }
        else
            $mysqli->query("UPDATE `items_categorias` SET `estado_id` = '0' WHERE `id` = '{$id}'");
        if ($mysqli->affected_rows)
            $this->modificadas++;
    }

}
