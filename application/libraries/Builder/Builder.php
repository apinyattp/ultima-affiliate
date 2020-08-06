<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder;

class Builder {
    private $_reader;
    private $_api;
    private $_config;

    public function __construct($config=[]) {
        $this->_config = [
            'api_read_path' => [],
            'api_cache_path' => APPPATH.'cache/api.json',
            'api_cache_save' => FALSE,
            'api_cache_time' => 1 * 24 * 60 * 60,
        ];
        $this->_config = array_merge($this->_config, $config);
        $this->_add_read_path_modules();
        $this->_add_read_path(APPPATH.'api/');
        $this->_add_read_path(APPPATH.'api-sitemap/');
        self::$instance =& $this;
    }

    private function _add_read_path_modules() {
        if(!is_dir(MODULEPATH)) return;
        $handler = opendir(MODULEPATH);
        while (($module_name = readdir($handler)) !== FALSE) {
            if(in_array($module_name, ['.', '..', 'index.html'])) continue;
            $module_path = MODULEPATH . '/' . $module_name . '/api';
            $this->_add_read_path($module_path.'/', $module_name);
            $module_path = MODULEPATH . '/' . $module_name . '/api-sitemap';
            $this->_add_read_path($module_path.'/', $module_name);
        }
        closedir($handler);
    }

    private function _add_read_path($read_path, $module_name=NULL) {
        $this->_config['read_path'][$read_path] = $module_name;
    }

    public function read($path=FALSE, $module_name=FALSE) {
        if(empty($this->_reader)) {
            $this->_reader = new Reader\Reader($this);
        }

        if($path === FALSE) {
            if($this->cache_load() === TRUE) return;

            $a_path = $this->read_paths();
            if($a_path === FALSE) throw new \Exception('Api read_path is empty');

            foreach ($a_path as $path => $module_name) {
                $this->read($path, $module_name);
            }

            $this->cache_save();
            return;
        }

        if(!is_dir($path)) return;

        $this->_reader->read($path, $module_name);
    }

    public function _api() {
        if(!empty($this->_api)) return $this->_api;

        if(!empty($this->_reader)) {
            $a_endpoint = $this->_reader->get_endpoint();
        }else{
            $a_endpoint = [];
        }
        $this->_api = new Api\Api($this, $a_endpoint);
        return $this->_api;
    }

    public function _document() {
        if(!empty($this->_document)) return $this->_document;

        if(!empty($this->_reader)) {
            $a_data = $this->_reader->get_data();
        }else{
            $a_data = [];
        }
        $this->_document = new Document\Document($this, $a_data);
        return $this->_document;
    }

    public function read_paths() {
        if(!isset($this->_config['read_path'])) return FALSE;
        return $this->_config['read_path'];
    }

    public function validate($endpoint) {
        $method = $_SERVER['REQUEST_METHOD'];
        return $this->_api()->validate($method, $endpoint);
    }

    public function get_endpoint($method, $endpoint) {
        return $this->_api()->get_endpoint($method, $endpoint);
    }

    public function router($uri) {
        return $this->_api()->router($uri);
    }

    public function document($api_file) {
        return $this->_document()->render($api_file);
    }

    static $instance;

    public static function &get_instance() {
        if(empty(self::$instance)) {
            if(class_exists('CI_Controller')) {
                $ci = & get_instance();
                $config = & $ci->config;
            }else{
                $config = &load_class('Config', 'core');
            }
            $config->load('error_code');

            new Builder();
        }
        return self::$instance;
    }

    public function cache_load() {
        if(!$this->_config['api_cache_save']) return FALSE;
        if(!file_exists($this->_config['api_cache_path'])) return FALSE;

        $json = file_get_contents($this->_config['api_cache_path']);
        $a_cache = json_decode($json, TRUE);
        if(empty($a_cache['time']) || time() - $a_cache['time'] > $this->_config['api_cache_time']) return FALSE;
        $this->_reader->cache_load($a_cache['reader']);
        return TRUE;
    }

    public function cache_save() {
        if(!$this->_config['api_cache_save']) return FALSE;
        $a_cache = [
            'time' => time(),
            'reader' => $this->_reader->cache_save(),
        ];
        $json = json_encode($a_cache);

        return file_put_contents($this->_config['api_cache_path'], $json);
    }

}
