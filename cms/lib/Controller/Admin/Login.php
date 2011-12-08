<?php

/**
 * Description of Login
 *
 * @author pablo
 */
class Controller_Admin_Login {//extends Controller_Admin {

    public $response;
//    public $action;
//
//    public function __construct() {
//        $this->action = 'index';
//    }


    /**
     * 
     */
    public function index() {


        // Salir de este controlador si ya se está identificado
        if(empty($_REQUEST['action']) || $_REQUEST['action'] == 'acceder') {
            $this->cms = CMS::getInstance();
            if(Session::getCurrentSession()->user['id']) {
                $redir = array(
                    'controller' => 'home',
                );
                $this->cms->uri->redirect($redir, 303);
            }
        }
        if(method_exists($this, $_REQUEST['action'])) {
            $this->{$_REQUEST['action']}();
        }

        $this->response = ($this->response && $this->response->success == false) ? $this->response->getResponse() : null;
        $referer = urldecode($_REQUEST['ref']);
        $this->rememberme = isset($_POST['recordarme']);
        include(APPLICATION_PATH.'/bng5/templates/admin/login.php');
    }

    /**
     * @todo
     */
    public function acceder() {
        $this->response = new StdResponse();

		if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->response->setException(StdResponse::METODO_NO_ACEPTADO, "solo POST");
            exit('Sólo POST');
        }

        if(empty($_POST['username']))
            $this->response->addFieldError('username', StdResponse::FIELD_REQUIRED_ERROR);
        if(empty($_POST['clave']))
            $this->response->addFieldError('clave', StdResponse::FIELD_REQUIRED_ERROR);

        $model = new DAO_Users;
        $user = new DTO_User;
//                header("Location: /login?);//Location: ".urldecode($_POST["ref"]), TRUE, 303);
//				exit;

        try {
            if($user = $model->getByUsername($_POST['username'])) {
                $this->username = $_POST['username'];
                if($user->validatePass($_POST['clave'])) {
                    Session::start($user, $_POST['recordarme']);
                }
                else
                    $this->response->setException(StdResponse::CAMPOS_VALORES_INCORRECTOS);
            }
            else
                $this->response->setException(StdResponse::CAMPOS_VALORES_INCORRECTOS);
        }
        catch(Exception $e) {
            $this->response->addFieldError($e->getMessage(), $e->getCode());
        }

        if($this->response->success) {
            CMS::getInstance()->uri->redirect(array(
                'controller' => 'login',
                'action' => 'compcookie',
                'params' => array(
                    'ref' => $_POST['ref'],
                ),
            ));
        }
//        else
//            $this->index();
    }

    /**
     * @todo
     */
    public function salir() {

        if(Session::getCurrentSession()->destroy()) {
            $this->message = 'Su sesión ha sido cerrada satisfactoriamente.';
        }
    }

    public function compcookie() {
        if(!$_COOKIE[Session::$sessionName])
            die("<h1>Error</h1><p>Su navegador no parece tener cookies habilitadas.</p>");
        header("Location: ".($_GET['ref'] ? urldecode($_GET['ref']) : '/'));
        exit("");
        //CMS::getInstance()->uri->redirect();

    }

}
