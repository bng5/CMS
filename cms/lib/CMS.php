<?php

//define('CMS_PATH', dirname(__FILE__));
// TODO Hacer dinámico
//define('APPLICATION_PATH', dirname(dirname(dirname($_SERVER["SCRIPT_FILENAME"]))).'/bng5');

//require(APPLICATION_PATH.'/config.php');
mb_internal_encoding("UTF-8");
error_reporting(E_ALL ^ E_NOTICE);

/**
 * Description of CMS
 *
 * @package Bng5_CMS
 * @author pablo
 */
class CMS {

    private static $_instance;
    private $_env;

    public $uri;
    public $config;
    public $lang;

    private function  __construct() {
        spl_autoload_register(array(__CLASS__, 'loadClass'));
        $this->uri = new Uri;
        //$config = include(APPLICATION_PATH.'/config.php');
        //$this->config = new Config($config);
        $this->lang->id = 1;
        $this->lang->code = 'es';
        self::$_instance = $this;
        //$this->session
    }

	/**
	 * Singleton
	 *
	 * @return CMS
	 */
	public static function admin($config = null) {
		if (!isset(self::$_instance)) {
			//$className = __CLASS__;
			//self::$_instance =
            new self;//$className
            self::$_instance->setConfig($config);
            self::$_instance->_env = 'admin';
		}
		return self::$_instance;
	}

    public static function site($config = null) {
		if (!isset(self::$_instance)) {
			//$className = __CLASS__;
			//self::$_instance =
            new self;//$className
            self::$_instance->setConfig($config);
            if(isset($config['URI']['request_type']))
                self::$_instance->uri->request_type = $config['URI']['request_type'];
		}
		return self::$_instance;
	}

    public static function getInstance() {
        return self::$_instance;
    }
    
    public static function loadClass($class) {
        $class = str_replace('_', '/', $class);
        require_once(CMS_PATH.'/'.$class.'.php');
    }

    public function setConfig($config) {
        $this->config = new Config($config);
    }

    public function run() {
        $this->uri->resolve();
        $controller = 'Controller_'.($this->_env == 'admin' ? 'Admin_' : '').($this->uri->controller ? ucfirst($this->uri->controller) : 'Home');
        $this->controller = new $controller;
        $action = $this->uri->action ? $this->uri->action : 'index';
        $this->controller->{$action}();

        
        
        //$this->uri-> = var_dump($_SERVER['PATH_INFO']);
        //$this->controller
    }
}


