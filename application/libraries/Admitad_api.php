<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitad_api {

    private $_ci;

    private $_endpoint;
    private $_client_id;
    private $_client_secret;
    private $_base64_header;
    private $_language;
    private $_website;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/admitad');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_client_id = $this->_ci->config->item('client_id');
        $this->_client_secret = $this->_ci->config->item('client_secret');
        $this->_base64_header = $this->_ci->config->item('base64_header');
        $this->_language = $this->_ci->config->item('lang');
        $this->_website = $this->_ci->config->item('website');
    }

    private function _b64xxx() {
        $client_id = $this->_cliend_id;
        $client_secret = $this->_client_secret;

        $data = $client_id . ':' . $client_secret;

        $data_b64_encoded = base64_encode($data);

        return $data_b64_encoded;
    }

    private function _auth($scope) {

        $body = [
            'language' => $this->_language,
            'client_id' => $this->_client_id,
            'grant_type' => 'client_credentials',
            'scope' => $scope
        ];

        $url = $this->_endpoint . 'token/';

        $_b64xxx = $this->_base64_header;

        $header = [
            'Authorization: Basic ' . $_b64xxx
        ];

        $response = $this->_ci->gateway->curl_post($url, $body, $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    private function _header($scope) {

        $auth = $this->_auth($scope);

        $header = [
            'Authorization: Bearer ' . $auth['access_token']
        ];

        return $header;
    }

    public function me() {

        $header = $this->_header('private_data');

        $url =  $this->_endpoint . 'me/';

        $response = $this->_ci->gateway->curl_get($url, [], $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    public function advcampaigns() {
        $header = $this->_header('advcampaigns_for_website');

        $data = [
            'language' => $this->_language
        ];

        $url =  $this->_endpoint . 'advcampaigns/website/'.$this->_website.'/';

        $response = $this->_ci->gateway->curl_get($url, $data, $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    public function advcampaign($campaign_id) {

        $header = $this->_header('advcampaigns_for_website');

        $data = [
            'language' => $this->_language
        ];

        $url =  $this->_endpoint . 'advcampaigns/' .$campaign_id. '/website/' . $this->_website .'/';

        $response = $this->_ci->gateway->curl_get($url, $data, $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    public function report($campaign=NULL, $date_start=NULL, $date_end=NULL, $order_by=NULL, $limit=50, $offset=NULL) {
        $header = $this->_header('statistics');

        $data = [
            'language' => $this->_language,
            'campaign' => $campaign,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'order_by' => $order_by,
            'limit' => $limit,
            'offset' => $offset
        ];

        $url = $this->_endpoint . 'statistics/actions/';

        $response = $this->_ci->gateway->curl_get($url, $data, $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    public function currency_exchange_rate($base, $target, $date) {
        $header = $this->_header('public_data');

        $data = [
            'language' => $this->_language,
            'base' => $base,
            'target' => $target,
            'date' => date('d.m.Y', strtotime($date))
        ];

        $url = $this->_endpoint . 'currencies/rate/';

        $response = $this->_ci->gateway->curl_get($url, $data, $header);

        $result = json_decode($response, TRUE);

        return $result;
    }

    public function rate($base, $target, $date) {

        $date = date('Y-m-d', strtotime($date));

        $this->_ci->load->model('data_model');
        $exchange_rate =  $this->_ci->data_model->get_by_date('admitad', $date);
        if(empty($exchange_rate)) {
            $exchange_rate =  $this->currency_exchange_rate($base, 'THB', $date);

            $this->_ci->data_model->create('admitad', $exchange_rate['base'], $exchange_rate['target'], $exchange_rate['rate'], $exchange_rate['date']);
        }

        return $exchange_rate['rate'];
    }
}
