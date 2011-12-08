<?php

/**
 * Description of Login
 *
 * @author pablo
 * @todo Renombrar a Controller_Admin_Home
 */
class Controller_Admin_Home extends Controller_Admin {
    
    public function index() {
        $cms = CMS::getInstance();

        $sectionsModel = new DAO_Sections;
        $this->view = new View_Admin_Document();
        $this->view->title = 'Inicio';
        $this->view->lang = $cms->lang;
        $this->view->setConfig('menu', true);
        $this->view->sections = $sectionsModel->getSections(true);
        $sectionsModel->loadLangTexts($this->view->sections, $cms->lang);


        $this->view->show();
    }





}
