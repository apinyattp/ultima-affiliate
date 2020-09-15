<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admitad extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('gateway');
    }

    private function _b64xxx() {
        $client_id = 'im9wCh7HgDwKiBL90ClfVvag4BTgZI';
        $client_secret = 'uUbk8kEWlqVcLZwabqEnOwKwduW8qT';

        $data = $client_id . ':' . $client_secret;

        $data_b64_encoded = base64_encode($data);

        return $data_b64_encoded;
    }

    public function token() {

        $body = [
            'client_id' => 'im9wCh7HgDwKiBL90ClfVvag4BTgZI',
            'grant_type' => 'client_credentials',
            'scope' => 'advcampaigns'
        ];

        $url = 'https://api.admitad.com/token/';

        $_b64xxx = $this->_b64xxx();

        $header = [
            'Authorization: Basic ' . $_b64xxx
        ];

        $response = $this->gateway->curl_post($url, $body, $header);

        $result = json_decode($response, TRUE);

        $this->_echo_json(E::SUCCESS, $result);
    }

}
