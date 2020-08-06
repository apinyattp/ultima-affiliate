<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Api;

class Validator {
    private $_endpoint;

    public function __construct($endpoint) {
        $this->set_endpoint($endpoint);
    }

    public function set_endpoint($endpoint) {
        $this->_endpoint = $endpoint;
    }

    public function run() {
        $a_input = $this->_endpoint->get_input();
        foreach ($a_input as $var_name => $validate) {
            $input = new \Builder\Input\Input($var_name, $validate);

            if(($result = $input->validate()) !== TRUE) return $result;
        }
        return TRUE;
    }

}
