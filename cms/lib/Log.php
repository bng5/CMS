<?php

/**
 * Description of Log
 *
 * @author pablo
 */
class Log {
    const ERROR = 1;

    protected $_indent = 0;
    protected $_fp;

    public function __construct() {
        $this->_fp = fopen('php://stdout', 'w');
    }

}
