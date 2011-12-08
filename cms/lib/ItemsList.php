<?php

/**
 * Description of ItemsList
 *
 * @author pablo
 */
class ItemsList {

    public $total;
    public $page;
    public $pages;
    public $rpp;

    public function __construct() {
        $this->total = 0;
        $this->page = 1;
        $this->rpp = 25;
    }
    
}
