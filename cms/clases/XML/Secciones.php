<?php

class XML_Secciones {
	
	function __construct(&$doc) {
		$this->doc = $doc;
	}
	
	function nodo($seccion) {
		$nodo = $this->doc->createElement("seccion");
		//$this->items_ref[$superior][$id] = $this->doc->createElement("seccion");
		//$this->items_ref[$id] = & $items_ref[$superior][$id];//['secciones'];

		//$nodo = $this->doc->createElement("seccion");//$this->items_ref[$superior]->appendChild();
		//$this->items_ref[$id] = & $this->items_ref[$superior][$id];
		//$this->items_ref[$superior][$id] = $nodo[$superior]->appendChild($this->doc->createElement("seccion"));
		$nodo->setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:id', $seccion->url);
		//$nodo->setAttribute("xml:id", $seccion->identificador);
		$nodo->setAttribute("nombre", $seccion->titulo);
		$nodo->setAttribute("tipo", "");
		$nodo->setAttribute("icono", "");
		$nodo->setAttribute("info", $seccion->info);
		$nodo->setAttribute("items", $seccion->items);
		$nodo->setAttribute("categorias", $seccion->categorias);
		return $nodo;
	}
}
