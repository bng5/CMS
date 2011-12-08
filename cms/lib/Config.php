<?php

/**
 * Description of Config
 *
 * @author pablo
 */
class Config {

    private $_config;
    public function __construct($config) {
        $this->_config = $config;
    }

    /**
     * Not in use
     */
    public function __set($attr, $value) {

    }

    public function __get($attr) {
        return $this->_config[$attr];
    }
}
