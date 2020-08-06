<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Core;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller extends \CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->_init_header();
        $this->_init_errorcode();
        $this->_check_maintenance();
        $this->_validate_api();
    }

    protected function _init_errorcode($language='thai') {
        $this->load->helper('language');
        $this->load->language('errorcode_standard', $language);
        $this->load->language('errorcode_extend', $language);
    }

    protected function _init_header() {
        $this->load->helper('url');
        if(defined('SCGSERVER') || strpos(base_url(), 'http://localhost/') !== FALSE) {
            $origin = $this->input->get_request_header('Origin');
            header('Access-Control-Allow-Origin: '.$origin);
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
            header('Access-Control-Request-Method: GET, POST');
        }
        if($this->input->arrays($_SERVER, 'REQUEST_METHOD') === 'OPTIONS') exit;
        if($this->input->arrays($_SERVER, 'HTTP_URLSCAN_ORIGINAL_VERB') === 'OPTIONS') exit;
    }

    protected function _validate_api() {
        $endpoint = $this->uri->uri_string();
        if(!preg_match('/^api\//', $endpoint)) return;

        $this->load->library('api');
        $result = $this->api->validate($endpoint);

        if($result === TRUE) return;

        $this->_echo_json($result->error_code, $result->data);
        exit;
    }

    protected function _echo_json($code, $data = '__NULL__', $msg = FALSE) {
        $a_json = [
            'response_code' => $code,
            'response_msg' => '',
            'result' => $data === '__NULL__' ? (object) [] : $data,
        ];
        if($msg !== FALSE) {
            $a_json['response_msg'] = $msg;
        }else{
            $a_json['response_msg'] = lang('errorcode_'.$code);
        }

        header('Content-Type: application/json');

        echo json_encode($a_json);

        return FALSE;
    }

    private function _check_maintenance() {
        $api_maintenance = $this->config->item('api_maintenance');
        if(!empty($api_maintenance)) {
            $this->_echo_json(\E::HTTP_SERVICE_UNAVAILABLE);
            exit;
        }
    }

}

function module_autoload($class) {
    if(class_exists($class)) return;
    $a_class = explode('\\', $class, 4);
    if(count($a_class) < 4) return;
    list($scope, $module, $type, $class_name) = $a_class;
    if($scope !== 'Module') return;
    $file_path = MODULEPATH.$module;
    $file_path .= DIRECTORY_SEPARATOR.strtolower($type);
    $a_path = explode('\\', $class_name);
    $class_name = array_pop($a_path);
    foreach ($a_path as $path) {
        $file_path .= DIRECTORY_SEPARATOR.strtolower($path);
    }
    $file_path .= DIRECTORY_SEPARATOR.$class_name.'.php';
    require_once($file_path);
}

spl_autoload_register('\Builder\Core\module_autoload');
