<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jelala {

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('jelala');
        $this->baseurl = $this->_ci->config->item('baseurl');
        $this->header = $this->_ci->config->item('header');
    }

    // affiliate flag
    // Authorization: basic x9rjl70un1pcvwk6evj66gt17fnrqyrus4omsx9n
    // baseurl: https://jelala.com/

    // Update flag
    // method POST:/api/integration//affiliate/flag/
    // INPUT: {"ids":[1,2,3]}

    // Delete flag
    // method DELETE: /api/integration//affiliate/flag/
    // INPUT: {"ids":[1,2,3]}

    public function update($ids) {

        $url = $this->baseurl . 'api/integration//affiliate/flag/';
    
        $data = [
            'ids' => $ids
        ];

        $header = $this->header;
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);

        return $response;

    }

    public function delete() {

    }

}
