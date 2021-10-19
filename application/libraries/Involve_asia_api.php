<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Involve_asia_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    private $_token;
    private $_token_time = 0;

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
        if($this->_token && $this->_token_time < time() - (1.5 * 60 * 60)) {
            return $this->_token;
        }

        $result = $this->authenticate();

        $this->_token = $result['data']['token'];
        $this->_token_time = time();

        return $this->_token;
    }

    public function offers($a_offer_id=[], $a_offer_name=[], $offer_type=NULL, $a_category=[], $page=1, $limit=100) {

        $offer_ids = implode('|', $a_offer_id);
        $offer_names = implode('|', $a_offer_name);
        $categories = implode('|', $a_category);

        $header = [
            'Accept: application/json',
             'Authorization: Bearer ' . $this->_auth()
        ];

        $url = $this->_endpoint . 'offers/all';

        if(!empty($a_offer_id)) {
            $filter_key = 'filters[offer_id]';
            $filter_data = $offer_ids;
        }else{
            $filter_key = 'filters[offer_name]';
            $filter_data = $offer_names;
        }
        $params = [
            'page' => $page,
            'limit' => $limit,
            'sort_by' => 'relevant',
            'filters[country]' => 'Thailand',
            $filter_key => $filter_data
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function all_offers($page=1, $limit=10, $country='Thailand') {

        $header = [
            'Accept: application/json',
             'Authorization: Bearer' . $this->_auth()
        ];

        $url = $this->_endpoint . 'offers/all';

        $params = [
            'page' => $page,
            'limit' => $limit,
            'sort_by' => 'relevant',
            'filters[country]' => $country
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);

        return json_decode($result, TRUE);
    }

    public function conversion($start_date, $end_date, $a_offer_id=[], $a_status=[], $page=1, $limit=100) {
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
            $params['filters[offer_id]'] = implode('|', $a_offer_id);
        }

        if(!empty($a_status)) {
            $params['filters[conversion_status]'] = implode('|', $a_status);
        }

        var_dump($params);

        $result = $this->_ci->gateway->curl_post($url, $params, $header);

        return json_decode($result, TRUE);
    }

}
