<?php

//require_once('DB/MysqliStmt.php');

class DB {// extends mysqli {//PDO {

    private static $_instance;
    private $_input_params;

    private function __construct() {

        if(class_exists('PDO') && extension_loaded('pdo_mysql')) {
            $class = 'PDO';
        } else {
            require_once(DOCUMENT_ROOT.'/lib/phppdo/phppdo.php');
            $class = 'PHPPDO';
        }

        $dbconn = new $class('mysql:dbname=' . dbname . ';host='. host , user, pass);
        $dbconn->exec("SET CHARACTER SET utf8");

        return $dbconn;

//        parent::__construct('mysql:dbname=gss;host=10.0.0.240', 'pablo', 'pablok4');
        //parent::__construct('10.0.0.240', 'pablo', 'pablok4', 'gss');
        // mysqli::set_charset está disponible a partir de PHP 5.0.5 (5.0.4 en la 243)
        //$this->set_charset("utf8");
        //$this->query("SET CHARACTER SET utf8");
    }

    /**
     * Singleton
     *
     * @return Gbg_DB
     */
    public static function instance() {
        if (!isset(self::$_instance)) {
            //self::$_instance = new self();
            if(class_exists('PDO') && extension_loaded('pdo_mysql')) {
                $class = 'PDO';
            }
            else {
                require_once(DOCUMENT_ROOT.'/lib/phppdo/phppdo.php');
                $class = 'PHPPDO';
            }

            $dbconn = new $class('mysql:dbname=' . dbname . ';host='. host , user, pass);
            $dbconn->exec("SET CHARACTER SET utf8");

            self::$_instance = $dbconn;
            }
        return self::$_instance;
    }


    public function prepare($query) {
        $this->_input_params = array();
        $query = preg_replace_callback('/:\w+/', array(__CLASS__, '_prepare_params'), $query);
        $stmt = parent::prepare($query);
        if ($stmt)
            $stmt = new Gss_DB_MysqliStmt($stmt, $this->_input_params);
        unset($this->_input_params);
        return $stmt;
    }

    /**
     * @todo Sólo devuelve '?'
     *
     * @return string '?'
     */
    private function _prepare_params($match) {
        /*
         *
          Array
          (
          [0] => Array
          (
          [0] => :active
          )

          )
         */
        $this->_input_params[$match[0]] = null;
        return '?';
    }

    public function __call($name, $arguments) {
        var_dump($name, $arguments);
    }

}
