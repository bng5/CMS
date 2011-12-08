<?php

/**
 * Description of Document
 *
 * @author pablo
 */
class View_Admin_Html extends View_Admin {

    private $html;
    public function __construct($html) {
        $this->html = $html;
    }

    public function show() {
        echo $this->html;
    }


}
