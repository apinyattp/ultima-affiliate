<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Involve_asia extends MY_Controller {

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    public function __construct() {
        parent::__construct();

        $this->load->library('involve_asia_api');

        header('Content-Type: application/json');
    }

    public function authenticate() {
        $result = $this->involve_asia_api->authenticate();

        echo json_encode($result);    
    }

    public function offers() {
        $a_offer_id = [];
        $a_offer_name = [];
        $offer_type = NULL;
        $a_category = [];

        $result = $this->involve_asia_api->offers($a_offer_id, $a_offer_name, $offer_type, $a_category);

        print_r($result);
    }

}
