<?php

/**
 * Description of Console
 *
 * @author pablo
 * @example ../../examples/Log_Plain.php description
 */
class Log_Plain extends Log {

    private function _print($msj) {
        fwrite($this->_fp, str_repeat('  ', $this->_indent).$msj.PHP_EOL);
    }

    public function log($msj) {
        $this->_print($msj);
    }
    
    public function error($msj) {
        $this->_print($msj);
    }

    public function group($msj) {
        $this->_print($msj);
        $this->_indent++;
    }

    public function groupEnd($msj = null) {
        if($msj)
            $this->_print($msj);
        $this->_print('');
        if($this->_indent > 0)
            $this->_indent--;
    }
}
