<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Involve_asia_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/involve_asia');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_api_secret = $this->_ci->config->item('api_secret');
        $this->_api_key = $this->_ci->config->item('api_key');
    }

    public function authenticate() {
        
        $params = [
            'secret' => $this->_api_secret,
            'key' => $this->_api_key
        ];

        $url = $this->_endpoint . 'authenticate';

        $header = [
            'Accept: application/json'
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        
        return json_decode($result, TRUE);
    }

    public function _auth() {
        $result = $this->authenticate();

        return $result['data']['token'];
    }

    public function offers($a_offer_id=[], $a_offer_name=[], $offer_type=NULL, $a_category=[], $page=1, $limit=25) {

        $offer_ids = implode('|', $a_offer_id);
        $offer_names = implode('|', $a_offer_name);
        $categories = implode('|', $a_category);

        $header = [
            'Accept: application/json',
             'Authorization: Bearer ' . $this->_auth()
        ];

        $url = $this->_endpoint . 'offers/all';

        $params = [
            'page' => $page,
            'limit' => $limit,
            'sort_by' => 'relevant',
            'filters[country]' => 'Thailand',
            'filters[offer_id]' => $offer_ids
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        var_dump($result);
        return json_decode($result, TRUE);
    }

    public function conversion($start_date, $end_date, $a_offer_id=[], $page=1, $limit=100) {
        // https://api.involve.asia/api/conversions/range

        $header = [
            'Accept: application/json',
             'Authorization: Bearer ' . $this->_auth()
        ];

        $url = $this->_endpoint . 'conversions/range';

        $params = [
            'page' => $page,
            'limit' => $limit,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        if(!empty($a_offer_id)) {
            $offer_ids = implode('|', $a_offer_id);
            $params['filters[offer_id]'] = $offer_ids;
        }

        $result = $this->_ci->gateway->curl_post($url, $params, $header);

        return json_decode($result, TRUE);
    }

}
