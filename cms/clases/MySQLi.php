<?php

class MySQLi extends PDO {

    private $_valores = array();
    
    public function __construct($host, $user, $pass, $db) {
        parent::__construct('mysql:dbname='.$db.';host='.$host, $user, $pass);
    }

    public function set_charset($charset) {
        $this->exec("SET CHARACTER SET {$charset}");
    }
    
    public function __get($attr) {
        switch ($attr) {
            case 'insert_id':
                return $this->lastInsertId;
                break;
            case 'affected_rows':
                return $this->_valores['affected_rows'];
                break;
            case 'error':
                $errorInfo = $this->errorInfo();
                return $errorInfo[2];
                break;
            case 'errno':
                $errorInfo = $this->errorInfo();
                return $errorInfo[1];
                break;
        }
        throw new Exception("Implementar esta propiedad: ".$attr, 0);
    }

    public function query($sql) {
        if(stripos($sql, 'select') === 0) {
            if($stmt = parent::query($sql)) {
                return new MySQLi_Result($stmt);
            }
            return false;
        }
        else {
            $this->_valores['affected_rows'] = $this->exec($sql);
        }
    }

    public function real_escape_string($string) {
        $this->quote($string);
    }
}
