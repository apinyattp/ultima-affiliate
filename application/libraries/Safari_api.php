<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Safari_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    private $_token;
    private $_token_time = 0;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/safari');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_main_id = $this->_ci->config->item('main_id');
        $this->_api_secret = $this->_ci->config->item('api_secret');
        $this->_api_key = $this->_ci->config->item('api_key');
    }

    public function conversion($start_date, $end_date) {

        $header = [
            'Accept: application/json',
            'apikey: ' . $this->_api_key,
            'apisecret: ' . $this->_api_secret,
        ];

        $url = $this->_endpoint . 'get_order_main';

        $params = [
            'main_id' => $this->_main_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        return json_decode($result, TRUE);
    }

}
