<?php

/**
 * Description of Login
 *
 * @author pablo
 * @todo Renombrar a Controller_Admin_Home
 */
class Controller_Admin_Items extends Controller_Admin {
    
    public function index() {


        $sectionsModel = new DAO_Sections;
        $section = $sectionsModel->getById($_GET['section']);
//var_dump($_GET['section'], $section);


        $this->loadView();
        $this->view->section = $section;
        $this->view->title = 'Inicio';



        

        $listadoItems = new ItemsList;
        $listadoItems->page = 1;
        $listadoItems->section = $section;
        $listadoItems->bsq_cat = $bsq_cat;
        $listadoItems->orden_prov = $orden_prov;

        $items = new DAO_Items();
        $items->loadList($listadoItems);

//$vista->agregarComponente($vista->crearComponente('ListadoItems', $listadoItems));
$agregarItem = new View_Admin_Html('<div><a href="'.$this->cms->uri->link(array('controller' => 'editar', 'params' => array('section' => $section->id, 'cat' => ''))).'">'._("Agregar item").'</a></div>');
$this->view->appendChild($agregarItem);
$itemsList = new View_Admin_ItemsList($listadoItems);
$this->view->appendChild($itemsList);
//var_dump($this->view->createComponent('itemsList'));

        $this->view->show();
    }






}
