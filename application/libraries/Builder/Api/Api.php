<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Api;

class Api {
    private $_builder;
    private $_path;
    private $_a_endpoint = [];

    public function __construct($builder, $a_endpoint) {
        $this->set_builder($builder);
        $this->set_endpoints($a_endpoint);
    }

    public function set_builder($builder) {
        $this->_builder = $builder;
    }

    public function get_endpoint($method, $endpoint) {
        if(!isset($this->_a_endpoint[$endpoint][$method])) return NULL;
        return $this->_a_endpoint[$endpoint][$method];
    }

    public function set_endpoints($a_endpoint) {
        $this->_a_endpoint = $a_endpoint;
    }

    public function validate($method, $endpoint) {
        if(!isset($this->_a_endpoint[$endpoint][$method])) return new \Builder\Input\Response(\E::HTTP_NOT_FOUND);

        $validator = new Validator($this->_a_endpoint[$endpoint][$method]);

        return $validator->run();
    }

    public function router($endpoint) {
        if(!isset($this->_a_endpoint[$endpoint])) return FALSE;

        if(count($this->_a_endpoint[$endpoint]) == 1){
            $_endpoint = end($this->_a_endpoint[$endpoint]);
            return [$endpoint, $_endpoint->get_module()];
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if(!isset($this->_a_endpoint[$endpoint][$method])) return FALSE;

        $_endpoint = $this->_a_endpoint[$endpoint][$method];

        return [$endpoint.'_'.strtolower($method), $_endpoint->get_module()];
    }

}
