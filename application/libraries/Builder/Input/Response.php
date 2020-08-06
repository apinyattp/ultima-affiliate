<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Input;

class Response {
    var $error_code;
    var $data;

    public function __construct($error_code, $data=[]) {
        $this->set_error_code($error_code);
        $this->set_data($data);
    }

    public function set_error_code($error_code) {
        $this->error_code = $error_code;
    }

    public function set_data($data) {
        $this->data = $data;
    }

}
