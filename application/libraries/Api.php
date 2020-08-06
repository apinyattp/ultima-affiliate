<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Builder/Autoloader/autoload.php';

use Builder\Builder;

class Api {
    private $_ci;
    private $_config = [];
    private $_builder;

    public function __construct($config=[]) {
        $this->_ci = & get_instance();
        $this->_config = $config;
        $this->_init_builder();
    }

    private function _init_builder() {
        $this->_builder = new Builder($this->_config);
        $this->read();
    }

    public function read() {
        $this->_builder->read();
    }

    public function validate($endpoint) {
        return $this->_builder->validate($endpoint);
    }

    public function document($file) {
        return $this->_builder->document($file);
    }

}
