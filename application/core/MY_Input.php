<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Input extends CI_Input {

    public function __construct() {
        parent::__construct();
    }

    private $_json_parsed = FALSE;
    private $_custom_input = [];

    private function _use_get($key) {
        $use_get = FALSE;
        $use_get = $use_get || strpos(base_url(), 'http://localhost/') !== FALSE;

        if($use_get && isset($_GET[$key])) return TRUE;

        return FALSE;
    }

    public function get($key = NULL, $xss_clean = NULL) {
        if(isset($this->_custom_input['get'][$key])) return $this->_custom_input[$method][$key];
        if($this->is_export_data()) return $this->json($key, $xss_clean);
        return parent::get($key, $xss_clean);
    }

    public function post($key = NULL, $xss_clean = NULL) {
        if($this->_use_get($key)) return $this->get($key, $xss_clean);
        if(isset($this->_custom_input['post'][$key])) return $this->_custom_input['post'][$key];
        $content_type = $this->get_request_header('Content-Type');
        if($this->is_export_data() || preg_match("/application\/json/", $content_type)) {
            return $this->json($key, $xss_clean);
        }

        return parent::post($key, $xss_clean);
    }

    public function json($key = NULL, $xss_clean = NULL) {
        if($this->_use_get($key)) return $this->get($key, $xss_clean);
        if(isset($this->_custom_input['json'][$key])) return $this->_custom_input[$method][$key];

        if($this->_json_parsed === FALSE) {
            $json_str = $this->body();
            $a_json = json_decode($json_str, TRUE);
            if($a_json === FALSE) {
                $this->_json_parsed = [];
            }else{
                $this->_json_parsed = $a_json;
            }
        }

        if($key === NULL) return $this->_json_parsed;

        return $this->_fetch_from_array($this->_json_parsed, $key, $xss_clean);
    }

    public function body() {
        if(!empty($_GET['json_param'])) return $_GET['json_param'];
        return file_get_contents("php://input");
    }

    public function arrays($array, $key) {
        return $this->_fetch_from_array($array, $key);
    }

    public function input_set($method, $key, $value) {
        $this->_custom_input[$method][$key] = $value;
    }

    public function is_export() {
        if($this->json('export')) return TRUE;
        return FALSE;
    }

    public function is_export_data() {
        if($this->json('__export_data__')) return TRUE;
        return FALSE;
    }

}
