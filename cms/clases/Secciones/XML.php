<?php

class Secciones_XML {

    function __construct(&$doc, &$root) {
        $this->doc = $doc;
        $this->root = $root;
    }

    function agregar($seccion, $id, $superior) {
        $nodo = $superior->appendChild($this->doc->createElement("seccion"));
        //$this->items_ref[$superior][$id] = $this->doc->createElement("seccion");
        //$this->items_ref[$id] = & $items_ref[$superior][$id];//['secciones'];
        //$nodo = $this->doc->createElement("seccion");//$this->items_ref[$superior]->appendChild();
        //$this->items_ref[$id] = & $this->items_ref[$superior][$id];
        //$this->items_ref[$superior][$id] = $nodo[$superior]->appendChild($this->doc->createElement("seccion"));
        $nodo->setAttribute("xml:id", $seccion->identificador);
        $nodo->setAttribute("nombre", $seccion->titulo);
        $nodo->setAttribute("tipo", "");
        $nodo->setAttribute("icono", "");
        $nodo->setAttribute("info", $seccion->info);
        $nodo->setAttribute("items", $seccion->items);
        $nodo->setAttribute("categorias", $seccion->categorias);
        return $nodo;
    }

}
