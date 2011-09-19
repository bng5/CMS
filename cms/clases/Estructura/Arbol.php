<?php

class Estructura_Arbol {

    private $handler;

    function __construct() {
        $this->items = array();
        $this->items_ref = array();
        $this->items['items'] = array();
        //$this->items_ref[0] = & $this->items['items'];
        $this->huerfanos = array();
        $this->superiores = array();
    }

    function RegistrarHandler($obj) {
        $this->handler = $obj;
    }

    function agregar($item, $id, $superior) {
        //$item = (array) $item;
        //$this->superiores[$id] = $superior;

        if (isset($this->items_ref[$superior])) {
            //$this->items_ref[$superior][$id] = & $this->handler->agregar($item, $id, $this->items_ref[$superior]);
            $this->items_ref[$id] = & $this->handler->agregar($item, $id, $this->items_ref[$superior]);
            // $this->items_ref[$superior][$id] = $this->handler($item, $id, $superior);//$item;
            // $this->items_ref[$id] = & $this->items_ref[$superior][$id];//['items'];
        } else {
            $this->huerfanos[$superior][$id] = $item;
            $this->items_ref[$id] = & $this->huerfanos[$superior][$id]; //['items'];
        }
        if ($huerfanos[$id]) {
            $this->items_ref[$id] = $this->huerfanos[$id];
            unset($this->huerfanos[$id]);
        }
    }
}
