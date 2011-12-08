<?php

/**
 * Description of FeedsList
 *
 * @author pablo
 */
class Berocca_FeedsList extends Listado {
    
    public function __construct() {
        $this->_campos['rpp'] = 10;
        $this->_campos['pagina'] = 1;
        $this->_campos['username'] = false;

    }
}

