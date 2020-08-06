<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Reader;

class Endpoint {
    private $_method;
    private $_endpoint;
    private $_file;
    private $_module_name;
    private $_a_input = [];
    private $_a_input_desc = [];
    private $_a_header = [];
    private $_a_header_desc = [];
    private $_a_error_code = [];
    private $_results = [];

    public function __construct($method=NULL, $endpoint=NULL, $file=NULL, $module_name=NULL) {
        if($method !== NULL) $this->set_method($method);
        if($endpoint !== NULL) $this->set_endpoint($endpoint);
        if($file !== NULL) $this->set_file($file);
        if($module_name !== NULL) $this->set_module($module_name);
    }

    public function set_endpoint($endpoint) {
        $this->_endpoint = $endpoint;
    }

    public function set_method($method) {
        $this->_method = $method;
    }

    public function set_file($file) {
        $this->_file = $file;
    }

    public function set_module($module_name) {
        $this->_module_name = $module_name;
    }

    public function get_module() {
        return $this->_module_name;
    }

    public function add_data($data) {
        foreach ($data['a_input'] as $input) {
            $this->add_input($input['name'], $input['validate'], '');
        }
        foreach ($data['_a_header'] as $header) {
            $this->add_header($header['name'], '');
        }
    }

    public function add_input($var_name, $validate, $description) {
        if(isset($this->_a_input[$var_name])) throw new \Exception("Input {$var_name} is duplicate in {$this->_endpoint}");
        $this->_a_input[$var_name] = $validate;
        $this->_a_input_desc[] = [
            'name' => $var_name,
            'validate' => $validate,
            'description' => $description,
        ];
    }

    public function add_header($header, $description) {
        if(in_array($header, $this->_a_header)) throw new \Exception("Header {$header} is duplicate in {$this->_endpoint}");
        $this->_a_header[] = $header;
        $this->_a_header_desc[] = [
            'name' => $header,
            'description' => $description,
        ];
    }

    public function add_error_code($error_code) {
        if(in_array($error_code, $this->_a_error_code)) throw new \Exception("Error code {$error_code} is duplicate in {$this->_endpoint}");
        $this->_a_error_code[] = $error_code;
    }

    public function add_result($result) {
        $this->_results[] = $result;
    }

    public function get_endpoint() {
        return $this->_endpoint;
    }

    public function get_file() {
        return $this->_file;
    }

    public function get_url() {
        return site_url($this->_endpoint);
    }

    public function get_method() {
        return $this->_method;
    }

    public function get_input() {
        return $this->_a_input;
    }

    public function get_input_desc() {
        return $this->_a_input_desc;
    }

    public function get_header() {
        return $this->_a_header;
    }

    public function get_header_desc() {
        return $this->_a_header_desc;
    }

    public function cache_save() {
        return [
            'method' => $this->_method,
            'endpoint' => $this->_endpoint,
            'file' => $this->_file,
            'module_name' => $this->_module_name,
            'a_input_desc' => $this->_a_input_desc,
            'a_header_desc' => $this->_a_header_desc,
            'a_error_code' => $this->_a_error_code,
        ];
    }

    public function cache_load($data) {
        if(isset($data['method'])) $this->set_method($data['method']);
        if(isset($data['endpoint'])) $this->set_endpoint($data['endpoint']);
        if(isset($data['file'])) $this->set_file($data['file']);
        if(isset($data['module_name'])) $this->set_module($data['module_name']);
        if(isset($data['a_input_desc'])){
            foreach ($data['a_input_desc'] as $input_desc) {
                $name = isset($input_desc['name']) ? $input_desc['name'] : '';
                $validate = isset($input_desc['validate']) ? $input_desc['validate'] : '';
                $description = isset($input_desc['description']) ? $input_desc['description'] : '';
                $this->add_input($name, $validate, $description);
            }
        }
        if(isset($data['a_header_desc'])){
            foreach ($data['a_header_desc'] as $header_desc) {
                $name = isset($header_desc['name']) ? $header_desc['name'] : '';
                $description = isset($header_desc['description']) ? $header_desc['description'] : '';
                $this->add_header($name, $description);
            }
        }
        if(isset($data['a_error_code'])){
            foreach ($data['a_error_code'] as $error_code) {
                $this->add_error_code($error_code);
            }
        }
    }

}
