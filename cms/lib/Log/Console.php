<?php

/**
 * Description of Console
 *
 * @author pablo
 */
class Log_Console extends Log {

    private $_indent = 0;
    private $_fp;

    private function _print($msj) {
        fwrite($this->_fp, str_repeat('  ', $this->_indent).$msj.PHP_EOL);
    }

    public function log($msj) {
        $this->_print($msj);
    }
    
    public function error($msj) {
        $this->_print("\033[31m".$msj."\033[0m");
    }

    public function group($msj) {
        $this->_print("\033[1m".$msj."\033[0m");
        $this->_indent++;
    }

    public function groupEnd($msj = null) {
        if($msj)
            $this->_print($msj);
        if($this->_indent > 0)
            $this->_indent--;
    }
}
