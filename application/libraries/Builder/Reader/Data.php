<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Reader;

class Data {
    private $_a_line = [];
    private $_a_endpoint = [];
    private $_a_page = [];
    private $_endpoint;
    private $_module_name = NULL;

    public function __construct($module_name=NULL) {
        $this->set_module($module_name);
    }

    public function set_module($module_name) {
        $this->_module_name = $module_name;
    }

    public function add_endpoint($method, $endpoint, $file) {
        $this->add_line("endpoint", $endpoint, ['method' => $method, 'file' => $file]);
        $this->_endpoint = new Endpoint($method, $endpoint, $file, $this->_module_name);
        $this->_a_endpoint[$endpoint][$method] = $this->_endpoint;
        return $this->_endpoint;
    }

    public function add_page($line) {
        $this->add_line('page', $line);
        $this->_a_page[] = $line;
    }

    public function add_sitemap($method, $endpoint) {
        $this->add_line("sitemap", $endpoint, ['method' => $method]);
    }

    public function add_input($var_name, $validate, $description) {
        $this->add_line('input_table', $var_name, ['validate' => $validate, 'description' => $description]);
        if(!$this->_endpoint) return;
        $this->_endpoint->add_input($var_name, $validate, $description);
    }

    public function add_header($header, $description) {
        $this->add_line('header_table', $header, ['description' => $description]);
        if(!$this->_endpoint) return;
        $this->_endpoint->add_header($header, $description);
    }

    public function add_error_code($error_code) {
        $this->add_line('error_code_table', $error_code);
        if(!$this->_endpoint) return;
        $this->_endpoint->add_error_code($error_code);
    }

    public function add_result($result) {
        $this->add_line('result', $result);
        if(!$this->_endpoint) return;
        $this->_endpoint->add_result($result);
    }

    public function add_json($json) {
        $this->add_line('json', $json);
    }

    public function add_textline($line) {
        $this->add_line('text', $line);
    }

    public function add_hr() {
        $this->add_line('hr', '');
    }

    public function add_line($type, $value, $data=[]) {
        $this->_a_line[] = [
            'type' => $type,
            'value' => $value,
            'data' => $data,
        ];
    }

    public function get_endpoint($index=FALSE, $method=FALSE) {
        if($index === FALSE && $method === FALSE) return $this->_a_endpoint;
        if($method === FALSE && isset($this->_a_endpoint[$index])) return $this->_a_endpoint[$index];
        if(isset($this->_a_endpoint[$index][$method])) return $this->_a_endpoint[$index][$method];
        return FALSE;
    }

    public function get_page() {
        return $this->_a_page;
    }

    public function get_line($index=FALSE) {
        if($index === FALSE) return $this->_a_line;
        if(isset($this->_a_line[$index])) return $this->_a_line[$index];
        return FALSE;
    }

    public function cache_save() {
        $a_endpoint = [];
        foreach ($this->_a_endpoint as $uri => $_a_endpoint) {
            foreach ($_a_endpoint as $method => $endpoint) {
                $a_endpoint[] = $endpoint->cache_save();
            }
        }
        return [
            'module_name' => $this->_module_name,
            'a_line' => $this->_a_line,
            'a_endpoint' => $a_endpoint,
            'a_page' => $this->_a_page,
        ];
    }

    public function cache_load($reader, $data) {
        if(isset($data['module_name'])) $this->set_module($data['module_name']);
        if(isset($data['a_line'])) $this->_a_line = $data['a_line'];
        if(isset($data['a_page'])) $this->_a_page = $data['a_page'];
        if(isset($data['a_endpoint'])) {
            foreach($data['a_endpoint'] as $endpoint_cache) {
                $endpoint = new Endpoint();
                $endpoint->cache_load($endpoint_cache);
                $uri = $endpoint->get_endpoint();
                $method = $endpoint->get_method();
                $this->_a_endpoint[$uri][$method] = $endpoint;

                $reader->add_endpoint($method, $uri, $endpoint);
            }
        }
    }

}
