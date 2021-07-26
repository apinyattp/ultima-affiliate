<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jelala {

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        // $this->_ci->load->config('jelala');
    }

    public function update_campaign($ids, $base_url, $header) {
        $url = $base_url . 'api/integration/affiliate//';

        $data = [
            'ids' => is_array($ids) ? $ids : [$ids]
        ];

        $response = json_decode($this->_ci->gateway->curl_json($url, $data, $header), TRUE);

        $httpcode = $this->_ci->gateway->_httpcode();

        if($httpcode !== 200) return FALSE;

        return $response;

    }

    public function delete_campaign($ids, $base_url, $header) {
        $url = $base_url . 'api/integration/affiliate//';
    
        $data = [
            'ids' => is_array($ids) ? $ids : [$ids]
        ];

        $response = json_decode($this->_ci->gateway->curl_delete($url, $data, $header), TRUE);

        $httpcode = $this->_ci->gateway->_httpcode();

        if($httpcode !== 200) return FALSE;

        return $response;
    }

}
