<?php

/**
 * Description of Uri
 *
 * @author pablo
 */
class Uri {

    public $controller;
    public $action;
    public $request_type = 'PATH_INFO';

//    public function __construct($cms) {
//        $this->parent;
//    }

    public function resolve() {
        list($this->controller, $this->action) = explode("/", trim($_SERVER[$this->request_type], ' /'));
    }

    /**
     *
     * $data = array(
     *  'controller' => 'login',
     *  'params' => array(),
     * );
     * $uri->redirect($data);
     *
     * @see Uri::link()
     * @param array $url
     * @param int $status_header
     *
     */
    public function redirect($url, $status_header = 302) {
        $params = $url['params'] ? '?'.http_build_query($url['params']) : '';
        header("Location: ".$this->link($url), true, $status_header);
        exit(" ");
    }

    /**
     *
     * @param string $url
     * @param int $status_header
     */
    public function link($url) {
        $params = $url['params'] ? '?'.http_build_query($url['params']) : '';
        $url_str = "/index.php";
        if($url['controller']) {
            $url_str .= '/'.$url['controller'];
            if($url['action'])
                $url_str .= '/'.$url['action'];
        }
        return $url_str.$params;
    }

}
