<?php

class MySQLi_Result {

    private $_stmt;
    
    public function __construct($stmt) {
        $this->_stmt = $stmt;
    }

    public function fetch_assoc() {
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetch_row() {
        return $this->_stmt->fetch(PDO::FETCH_NUM);
    }
}
