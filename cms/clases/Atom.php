<?php

class Atom {

    private $_atom;
    public function  __construct(SimpleXMLElement $xml) {
        $this->_atom = $xml;
        $this->_atom->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
    }
    public function __get($attr) {
        return $this->_atom->channel->{$attr};
    }

}
