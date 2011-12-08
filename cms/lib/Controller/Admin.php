<?php

/**
 * Description of Admin
 *
 * @author pablo
 */
class Controller_Admin {

    protected $cms;
    public function __construct() {
        // Reutilizar control de sesiones
        $this->cms = CMS::getInstance();
        if(empty(Session::getCurrentSession()->user['id'])) {
            $redir = array(
                'controller' => 'login',
                'params' => array('ref' => urlencode($_SERVER["REQUEST_URI"])),
            );
            $this->cms->uri->redirect($redir, 303);
        }
    }

    protected function loadView() {
        $sectionsModel = new DAO_Sections;
        $this->view = new View_Admin_Document();
        $this->view->lang = $this->cms->lang;
        $this->view->setConfig('menu', true);
        $this->view->sections = $sectionsModel->getSections(true);
        $sectionsModel->loadLangTexts($this->view->sections, $this->cms->lang);

    }

}

