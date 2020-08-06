<?php
/**
 * @author Nutdanai Tippanontakul
 */

class Doc_auth {

    private $_ci;
    private $_token;

    public function __construct() {
        $this->_init();
    }

    private function _init() {
        $this->_ci = &get_instance();

        $this->_ci->load->config('document/config');

        $token = $this->_ci->input->cookie('token');
        if($token) $this->_token = $token;
    }

    public function token($token=FALSE) {
        if($token !== FALSE) $this->_token = $token;
        return $this->_token;
    }

    public function verify_token($token=FALSE) {
        if(preg_match('/^http(s)?:\/\/([^\/]*.)?localhost\//', base_url())) return TRUE;

        $this->_ci->load->library('doc_gateway');
        $token = $this->token($token);

        if(!$token) return FALSE;

        $data = [
            'token' => $token
        ];

        $result = $this->_webgen_curl('POST', 'api/google/verify', $data);

        if(!$result) {
            log_message('debug', 'Doc_auth: no response');
            return FALSE;
        }

        if(empty($result['response_code']) && $result['response_code'] != '200') {
            log_message('debug', 'Doc_auth: response error :'.json_encode($result));
            return FALSE;
        }

        $this->_ci->input->set_cookie('token', $token, (24 * 60 * 60));

        return TRUE;
    }

    private function _webgen_curl($method, $uri, $data=[], $header=[]) {
        $baseurl = $this->_ci->config->item('webgen_baseurl');
        $url = $baseurl . $uri;

        $body_string = http_build_query($data);

        $response = $this->_ci->doc_gateway->curl($method, $url, $body_string, $header);

        return json_decode($response, TRUE);
    }

    public function webgen_redirect_uri() {
        $uri = 'auth';
        $data = [
            'backurl' => site_url('document/auth'),
        ];
        return $this->_ci->config->item('webgen_base_url_website').$uri.'?'.http_build_query($data);
    }

}
