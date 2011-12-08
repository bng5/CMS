<?php

/**
 * Description of Login
 *
 * @author pablo
 * @todo Renombrar a Controller_Admin_Home
 */
class Controller_Admin_Users extends Controller_Admin {
    
    public function index() {
        parent::index();
        $this->view = new View_Admin_Document();
        $this->view->title = 'Pero';
        $this->view->lang = 'es-uy';
        $this->view->show();
    }





}
